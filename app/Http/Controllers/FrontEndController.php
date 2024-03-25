<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use App\Time;
use App\User;
use App\Booking;
use Illuminate\Support\Facades\Auth;
use App\Prescription;
use App\Mail\AppointmentMail;
use App\SearchHistory;
use App\Services\VectorizationService;
use Carbon\Carbon;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WordTokenizer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Math\Distance\Cosine;
use Phpml\Math\Distance\Euclidean;


class FrontEndController extends Controller
{
   
//change this
    public function index(Request $request)
    {

        // Set timezone
        date_default_timezone_set('Asia/Kathmandu');
    
        // Fetch the list of appointments
      
        $doctors = Appointment::get();
        

        // Get the authenticated user
        $user = Auth::user();
    
        // Initialize variables
        $doctorId = null;
        $userSymptoms = null;
        $mostSimilarDoctors = null; // Initialize $mostSimilarDoctors here
    
        if ($user) {
            // Retrieve symptoms of the authenticated user
            $userSymptoms = $user->symptoms;
            
            // Retrieve all prescriptions
            $prescriptions = Prescription::all();
    
            // Calculate cosine similarity between user symptoms and each prescription's symptoms
            $maxSimilarity = -1;
    
            foreach ($prescriptions as $prescription) {
                // Split prescription's symptoms into an array
                $prescriptionSymptoms = explode(',', $prescription->symptoms);
                // Calculate cosine similarity between user symptoms and prescription's symptoms
                $similarity = $this->cosineSimilarity($userSymptoms, $prescriptionSymptoms);
                // Check if similarity is higher than previous maximum
                if ($similarity > $maxSimilarity) {
                    $maxSimilarity = $similarity;
                    $mostSimilarDoctors = $prescription->doctor;
                    // Assign $doctorId based on the most similar doctor
                    $doctorId = $mostSimilarDoctors->id;
                } elseif ($similarity == 0) {
                    $mostSimilarDoctors = 0;
                }
            }
        }
    
        // Fetch the appointment date for the most similar doctor from the database
        if ($doctorId) {
            $appointment = Appointment::where('user_id', $doctorId)->first();
            if ($appointment) {
                $appointmentDate = $appointment->date;
            } else {
                $appointmentDate = now()->format('Y-m-d'); // Default to current date if no appointment found
            }
        } else {
            $appointmentDate = now()->format('Y-m-d'); // Default to current date if no doctor found
        }
    
        return view('welcome', compact('doctors', 'mostSimilarDoctors', 'userSymptoms', 'doctorId', 'appointmentDate'));
    }
    
    
//change this
public function recommended_show(Request $request, $doctorId, $date)
{
    // Find the appointment for the specified doctor ID and date
    $appointment = Appointment::where('user_id', $doctorId)
                                ->where('date', $date)
                                ->first();

    // Check if appointment exists
    if (!$appointment) {
        abort(404, 'Appointment not found');
    }

    // Format the appointment date using Carbon
    $appointmentDate = Carbon::createFromFormat('m-d-Y', $date)->format('m-d-Y');
    
    // Retrieve the available times for the appointment
    $times = Time::where('appointment_id', $appointment->id)
                    ->where('status', 0)
                    ->get();

    // Fetch user details using the provided doctor ID
    $user = User::find($doctorId);

    // Pass the necessary data to the appointment view
    return view('appointment', compact('times', 'appointmentDate', 'user', 'doctorId', 'date'))->with('success', 'Appointment created successfully.');

}



    public function show($doctorId, $date)
    {
        $appointment = Appointment::where('user_id', $doctorId)->where('date', $date)->first();
        $times = Time::where('appointment_id', $appointment->id)->where('status', 0)->get();
        $user = User::where('id', $doctorId)->first();
        $doctor_id = $doctorId;
        return view('appointment', compact('times', 'date', 'user', 'doctorId'));
    }


   
    public function store(Request $request)
    {
        // Set timezone
        date_default_timezone_set('Asia/Kathmandu');

        $request->validate(['time' => 'required']);
        $check = $this->checkBookingTimeInterval();
        if ($check) {
            return redirect()->back()->with('errMessage', 'You already made an appointment. Please check your email for the appointment!');
        }

        $doctorId = $request->doctorId;
        $time = $request->time;
        $appointmentId = $request->appointmentId;
        $date = $request->date;
        Booking::create([
            'user_id' => auth()->user()->id,
            'doctor_id' => $doctorId,
            'time' => $time,
            'date' => $date,
            'status' => 0
        ]);
        $doctor = User::where('id', $doctorId)->first();
        Time::where('appointment_id', $appointmentId)->where('time', $time)->update(['status' => 1]);

        // Send email notification
        $mailData = [
            'name' => auth()->user()->name,
            'time' => $time,
            'date' => $date,
            'doctorName' => $doctor->name
        ];
        try {
            \Mail::to(auth()->user()->email)->send(new AppointmentMail($mailData));
        } catch (\Exception $e) {
        }

        return redirect()->back()->with('message', 'Your appointment was booked for ' . $date . ' at ' . $time . ' with ' . $doctor->name . '.');
    }

    // check if user already make a booking.
    public function checkBookingTimeInterval()
    {
        return Booking::orderby('id', 'desc')
            ->where('user_id', auth()->user()->id)
            ->whereDate('created_at', date('y-m-d'))
            ->exists();
    }

    public function myBookings()
    {
        $appointments = Booking::latest()->where('user_id', auth()->user()->id)->get();
        return view('booking.index', compact('appointments'));
    }

    public function destroy($id){
        dd($id);
        $appointments = Booking::findOrFail($id);
       
        $appointments->delete();
        return redirect()->back()->with('success', 'Appointment deleted successfully');
    }

    public function myPrescription()
    {
        $prescriptions = Prescription::where('user_id', auth()->user()->id)->get();
        return view('my-prescription', compact('prescriptions'));
    }

    public function showSimilarPrescriptions(Request $request)
    {

        $querySymptoms = $request->input('symptoms');
        $queryTokens = $this->tokenizeName($querySymptoms);

        // Fetch all prescriptions
        $allPrescriptions = Prescription::where('symptoms', 'like', '%' . $querySymptoms . '%')->get();
        $similarities = [];

        foreach ($allPrescriptions as $prescription) {
            $symptomsTokens = $this->tokenizeName($prescription->symptoms);
            $similarity = $this->cosineSimilarity($queryTokens, $symptomsTokens);

            $matchPercentage = round($similarity * 100, 2);
            // dd($matchPercentage);
            if ($matchPercentage >= 50) {
                $similarities[] = [
                    'prescription' => $prescription,
                    'similarity' => $similarity,
                    'matchPercentage' => $matchPercentage,
                    'doctor' => $prescription->doctor // Accessing associated doctor directly
                ];
            }
        }

        // Sort by similarity in descending order
        usort($similarities, function ($A, $B) {
            return $B['similarity'] - $A['similarity'];
        });


        return view('search', compact('querySymptoms', 'similarities', 'doctor'));
    }

 
    public function storeSearch(Request $request)
    {
        $userId = Auth::id();
        $searchQuery = $request->input('query');

        SearchHistory::create([
            'user_id' => $userId,
            'search_query' => $searchQuery,
        ]);

        return response()->json(['message' => 'Search details stored successfully']);
    }
    public function showSearchHistory()
    {
        // Retrieve the authenticated user's ID
        $userId = Auth::id();

        // Retrieve the search history for the authenticated user
        $searchHistory = SearchHistory::where('user_id', $userId)
            ->orderBy('created_at', 'desc') // Optional: Order by creation date, newest first
            ->get();

        dd($searchHistory);
        // Pass the search history data to the view for display
        return view('search')->with('searchHistory', $searchHistory);
    }


   
    // Function to calculate cosine similarity
    private function cosineSimilarity($vector1, $vector2)
    {
        $vector1 = explode(',', $vector1);
        // dd($vector1);
        // $vector2 = explode(',', $vector2);
        // dd($vector2);
        $vector1 = array_map('ord', $vector1);
        $vector2 = array_map('ord', $vector2);
        $dotProduct = array_sum(array_map(function ($x, $y) {
            // dd($x, $y);
            return $x * $y;
        }, $vector1, $vector2));
        $magnitude1 = sqrt(array_sum(array_map(function ($x) {
            return $x * $x;
        }, $vector1)));
        $magnitude2 = sqrt(array_sum(array_map(function ($x) {
            return $x * $x;
        }, $vector2)));
        // dd($magnitude2);
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        } else {
            return $dotProduct / ($magnitude1 * $magnitude2);
        }
    }
}

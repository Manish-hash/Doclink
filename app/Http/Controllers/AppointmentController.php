<?php

namespace App\Http\Controllers;

use App\SearchHistory;
use Illuminate\Http\Request;
use App\Appointment;
use App\Prescription;
use Illuminate\Support\Facades\Auth;

use App\Time;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myAppointments = Appointment::latest()->where('user_id', auth()->user()->id)->get();
        return view('admin.appointment.index', compact('myAppointments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.appointment.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation, unique:table,column with the column id == user_id
        $this->validate($request, [
            'date' => 'required|unique:appointments,date,NULL,id,user_id,' . \Auth::id(),
            'time' => 'required'
        ]);

        $appointment = Appointment::create([
            'user_id' => auth()->user()->id,
            'date' => $request->date
        ]);
        foreach ($request->time as $time) {
            Time::create([
                'appointment_id' => $appointment->id,
                'time' => $time
                // default status => 0
            ]);
        }
        return redirect()->back()->with('message', 'An appointment created for ' . $request->date);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    


    // public function cancel($id)
    // {
    //     $appointments = Appointment::findOrFail($id);
    //     $appointments->delete();
    //     return redirect()->route('booking.index')->with('success', 'deleted successfully');
    // }

    public function delete($id)
{
    $appointment = Appointment::findOrFail($id);

    // Check if the authenticated user is authorized to cancel the appointment
    if ($appointment->user_id !== auth()->user()->id) {
        return redirect()->back()->with('error', 'You are not authorized to cancel this appointment.');
    }

    // Proceed with cancellation logic
    $appointment->delete();

    return redirect()->back()->with('success', 'Appointment canceled successfully.');
}
    
   


    public function search(Request $request)
    {
        // $query = request('query');
        $searchQuery = $request->input('query');

        $doctors = Prescription::where('symptoms', 'like', '%' . $searchQuery . '%')->get();
        // dd($doctors);
        $userId = Auth::id();
    
        SearchHistory::create([
            'user_id' => $userId,
            'search_query' => $searchQuery,
        ]);
        session(['doctors' => $doctors]);
        
        return view('search', compact('doctors'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Check specific date for appointment time
    public function check(Request $request)
    {
        $date = $request->date;
        $appointment = Appointment::where('date', $date)->where('user_id', auth()->user()->id)->first();
        if (!$appointment) {
            return redirect()->to('/appointment')->with('errMessage', 'Appointment time is not available for this date');
        };
        $appointmentId = $appointment->id;
        $times = Time::where('appointment_id', $appointmentId)->get();
        return view('admin.appointment.index', compact('times', 'appointmentId', 'date'));
    }
    // Update app time
    // delete the old time array and create a new time array
    public function updateTime(Request $request)
    {
        $appointmentId = $request->appointmentId;
        $date = Appointment::where('id', $appointmentId)->get('date')->first()->date;
        Time::where('appointment_id', $appointmentId)->delete();
        foreach ($request->time as $time) {
            Time::create([
                'appointment_id' => $appointmentId,
                'time' => $time,
                'status' => 0
            ]);
        }
        return redirect()->route('appointment.index')->with('message', 'Appointment time for ' . $date . ' is updated successfully!');
    }

   
}

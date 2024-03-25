@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 justify-content-center">
        <div class="col-md-8">
        </div>
    </div>

    @auth
    @if ($mostSimilarDoctors)

    <div class="card mb-4">
        <div class="card-body">
            <div class="card-header bg-primary text-white">Recommended Doctor</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-sm-8">
                        <p style="font-size: 18px;"><strong>Name:</strong> {{ $mostSimilarDoctors->name }}</p>
                        <p style="font-size: 18px;"><strong>Department:</strong> {{ $mostSimilarDoctors->department }}
                        </p>
                        <p style="font-size: 18px;"><strong>Education:</strong> {{ $mostSimilarDoctors->education }}</p>
                        <!-- Button to book appointment for recommended doctor -->



                        @if (Auth::check() && auth()->user()->role->name == 'patient')
                        <td>
                            <a
                                href="{{ route('recommend.appointment', ['doctorId' => $mostSimilarDoctors->id, 'date' =>   $appointmentDate]) }}">
                                <button class="btn btn-success">Book Appointment</button>
                            </a>
                        </td>
                        @else
                        <td class="text-danger">For patients ONLY</td>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <p style="color: red;">Please Enter the Symptoms first</p>
    @endif
    @endauth




    {{-- Display doctors --}}
    <div class="card">
        <div class="card-body">

            <div class="card-header bg-primary text-white">List of Available Doctors:
            </div>
            <br>
            <div>
                <form action="" method="GET" class="col-5">
                    <div class="input-group">
                        <input type="text" class="form-control" name="query" placeholder="Search by name or department">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
            <div class="card-body table-responsive-sm">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{-- <th>Photo</th> --}}
                            <th>Name</th>
                            <th>Department</th>
                            <th>Book</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse ($doctors as $key=>$doctor)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $doctor->doctor->name }}</td>
                            <td>{{ $doctor->doctor->education }}</td>
                            @if (Auth::check() && auth()->user()->role->name == 'patient')
                            <td>
                                <a href="{{ route('create.appointment', [$doctor->user_id, $doctor->date]) }}"><button
                                        class="btn btn-success">Book Appointment</button></a>
                            </td>
                            @else
                            <td class="text-danger">For patients ONLY</td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No doctors available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


<style>
/* Add this CSS to your existing CSS file or create a new one */

.card-header {
    font-size: 18px;
    font-weight: bold;
}

.card-body {
    padding: 20px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #218838;
}

.text-danger {
    color: #dc3545;
}
</style>
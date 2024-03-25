@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">My appointments: {{ $appointments->count() }}</div>

                    <div class="card-body table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Doctor</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Date for</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th> <!-- Add a new column for action -->
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $key => $appointment)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $appointment->doctor->name }}</td>
                                        <td>{{ $appointment->time }}</td>
                                        <td>{{ $appointment->date }}</td>
                                        <td>
                                            @if ($appointment->status == 0)
                                                <p>Not Visited</p>
                                            @else
                                                <p>Checked-In</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($appointment->status == 0)
                                                <form action="{{ route('appointments.delete', $appointment->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Cancel</button>
                                                </form>
                                            @else
                                                <p>Appointment checked-in, cannot cancel</p>
                                            @endif
                                        </td>
                                        <!-- <td>
                                             Delete button 
                                             <form action="{{ route('appointments.delete', $appointment->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td> -->
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">You have no appointments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

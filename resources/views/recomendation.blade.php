@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: center; align-items: center; height: 50vh;">
        @if ($mostSimilarDoctors)
            <div style="text-align: center; border: 1px solid #ddd; margin: 10px; padding: 10px;">
                <h1 style="color: blue;">Recommended Doctor</h1>
                <p style="font-size: 18px;"><strong>Name:</strong> {{ $mostSimilarDoctors->name }}</p>
                <p style="font-size: 18px;"><strong>Department:</strong> {{ $mostSimilarDoctors->department }}</p>
                <p style="font-size: 18px;"><strong>Education:</strong> {{ $mostSimilarDoctors->education }}</p>
                <p style="font-size: 18px;"><strong>Date:</strong> {{ $mostSimilarDoctors->date }}</p>
                <!-- Add more details here -->
            </div>
        @else
            <p style="color: red;">No doctor recommendation available.</p>
        @endif
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Search Results</h1>

        @if (count($doctors) > 0)
            <ul class="list-group">
                @foreach ($doctors as $doctor)
                    <li class="list-group-item">
                        <h3>{{ $doctor->name_of_disease }}</h3>
                        <p><strong>Doctor name:</strong> {{ $doctor->doctor->name }}</p>
                        <p><strong>Expertise:</strong> {{ $doctor->doctor->education }}</p>

                        <p><strong>Available for Date:</strong> {{ $doctor->date }}</p>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No results found for your search query.</p>
        @endif 
    </div>
@endsection

<style>
    /* public/css/custom.css */

    .container {
        margin-top: 50px;
    }

    h1 {
        margin-bottom: 20px;
    }

    .list-group-item {
        border-radius: 10px;
        margin-bottom: 10px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .list-group-item:hover {
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .list-group-item h3 {
        color: #333;
        margin-bottom: 5px;
    }

    .list-group-item p {
        margin-bottom: 5px;
        font-size: 16px;
    }

    .list-group-item p strong {
        font-weight: bold;
    }
</style>

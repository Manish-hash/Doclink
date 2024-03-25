@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
        <div class="row ">

            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">User Profile</div>

                    <div class="card-body">
                        <p>Name: {{ auth()->user()->name }}</p>
                        <p>Email: {{ auth()->user()->email }}</p>
                        <p>Address: {{ auth()->user()->address }}</p>
                        <p>Phone: {{ auth()->user()->phone_number }}</p>
                        <p>Gender: {{ auth()->user()->gender }}</p>
                        <p>Bio: {{ auth()->user()->description }}</p>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Update Profile</div>

                    <div class="card-body">
                        <form action="{{ route('profile.store') }}" method="post">@csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ auth()->user()->name }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ auth()->user()->address }}">

                            </div>
                            <div class="form-group">
                                <label>Phone number</label>
                                <input type="text" name="phone_number" class="form-control"
                                    value="{{ auth()->user()->phone_number }}">

                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">select gender</option>
                                    <option value="male" @if (auth()->user()->gender === 'male') selected @endif>Male</option>
                                    <option value="female" @if (auth()->user()->gender === 'female') selected @endif>Female</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="form-group">
                                    <label>Bio</label>
                                    <textarea name="description" class="form-control">{{ auth()->user()->description }}</textarea>

                                </div>
                                <div class="form-group">
                                    <label>Symptoms</label>
                                    <select name="symptoms" class="form-control">
                                        <option value="">Please select a symptom</option>
                                        <option value="Runny nose, cough, sneezing"
                                            {{ auth()->user()->symptoms == 'Runny nose, cough, sneezing' ? 'selected' : '' }}>
                                            Runny nose, cough, sneezing</option>
                                        <option value="Fever, chills, headache"
                                            {{ auth()->user()->symptoms == 'Fever, chills, headache' ? 'selected' : '' }}>
                                            Fever, chills, headache</option>
                                        <option value="Body aches, cough"
                                            {{ auth()->user()->symptoms == 'Body aches, cough' ? 'selected' : '' }}>Body
                                            aches, cough</option>
                                        <option value="Itchy eyes, sneezing"
                                            {{ auth()->user()->symptoms == 'Itchy eyes, sneezing' ? 'selected' : '' }}>
                                            Itchy eyes, sneezing</option>
                                        <option value="Nausea, abdominal pain, diarrhea"
                                            {{ auth()->user()->symptoms == 'Nausea, abdominal pain, diarrhea' ? 'selected' : '' }}>
                                            Nausea, abdominal pain, diarrhea</option>
                                        <option value="Throbbing pain, sensitivity to light"
                                            {{ auth()->user()->symptoms == 'Throbbing pain, sensitivity to light' ? 'selected' : '' }}>
                                            Throbbing pain, sensitivity to light</option>
                                        <option value="Pimples, blackheads, whiteheads"
                                            {{ auth()->user()->symptoms == 'Pimples, blackheads, whiteheads' ? 'selected' : '' }}>
                                            Pimples, blackheads, whiteheads</option>
                                    </select>
                                </div>
                                <div class="form-group">

                                    <button class="btn btn-primary" type="submit">Update Profile</button>

                                </div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Update Image</div>
                    <form action="{{ route('profile.pic') }}" method="post" enctype="multipart/form-data">@csrf
                        <div class="card-body">
                            @if (!auth()->user()->image)
                                <img src="/images/blank.png" width="120">
                            @else
                                <img src="/profile/{{ auth()->user()->image }}" width="120">
                            @endif
                            <br>
                            <input type="file" name="file" class="form-control" required="">
                            <br>
                            @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <button type="submit" class="btn btn-primary">Update Image</button>

                        </div>
                    </form>
                </div> --}}
        </div>

    </div>
    </div>
@endsection

@extends('layouts.app')

@section('title')
    {{ "Profile" }}
@endsection

@section('header')
    {{ "Profile" }}
@endsection

@section('content')
    <!-- Add content for profile page -->
    
    <div class="main_box"> 
                @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="box_header_ticket">
            <p class="box_header_title">Info</p>
        </div>
            <form action="{{ route('user.update', ['user' => $user->id]) }}" method="POST">
                {{ csrf_field() }}
                @method('PUT')
                <div class="op_box">
                    <p>Username</p>
                    <input id="username" type="text" name="username" value="{{ old('username') ?? $user->username }}"></input>
                    @if ($errors->has('username'))
                        <span class="error">
                            {{ $errors->first('username') }}
                        </span>
                    @endif                    
                </div>
                <div class="op_box">
                    <p>Name</p>
                    <input id="name" type="text" name="name" value="{{ old('name') ?? $user->name }}"></input>
                    @if ($errors->has('name'))
                        <span class="error">
                            {{ $errors->first('name') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Birthday</p>
                    <input id="birthday" type="date" name="birthday" value="{{ old('birthday') ?? $user->birthday}}"></input>
                    @if ($errors->has('birthday'))
                        <span class="error">
                            {{ $errors->first('birthday') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Country</p>
                    <input id="country" type="text" name="country" value="{{ old('country') ?? $user->country }}"></input>
                    @if ($errors->has('country'))
                        <span class="error">
                            {{ $errors->first('country') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Gender</p>
                    <input id="gender" type="text" name="gender" value="{{ old('gender') ?? $user->gender }}"></input>
                    @if ($errors->has('gender'))
                        <span class="error">
                            {{ $errors->first('gender') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Image</p>
                    <input id="url" type="text" name="url" value="{{ old('url') ?? $user->url }}"></input>
                    @if ($errors->has('url'))
                        <span class="error">
                            {{ $errors->first('url') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Email</p>
                    <input id="email" type="email" name="email" value="{{ old('email') ?? $user->email }}"></input>
                    @if ($errors->has('email'))
                        <span class="error">
                            {{ $errors->first('email') }}
                        </span>
                    @endif   
                </div>
                <div class="save_button_settings">
                    <div class="btnSubmit">
                        <button type="submit">SAVE</button>
                    </div>
                </div>
            </form>
            <p class="box_header_title">Change password</p>
            <form action="{{ route('change.password') }}" method="POST">
                {{ csrf_field() }}
                <div class="op_box">
                    <p>Last password</p>
                    <input id="last_password" type="password" name="last_password" value="{{ old('last_password') }}" required>
                </div>
                <div class="op_box">
                    <p>New password</p>
                    <input id="new_password" type="password" name="new_password" value="{{ old('new_password') }}">
                </div>
                <div class="btnSubmit">
                    <button type="submit">SAVE</button>
                </div>
            </form>

            <form method="GET" action="{{ route('logout') }}">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
@endsection
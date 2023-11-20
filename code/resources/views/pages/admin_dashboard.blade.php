@extends('layouts.app')

@section('title')
    {{ "Admin Dashboard" }}
@endsection

@section('header')
    {{ "Admin Dashboard" }}
@endsection

@section('content')
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Birthday</th>
                <th>Reputation</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @include('partials.user_row', ['user' => $user])
            @endforeach
        </tbody>
    </table>

    <h2>Create New Account</h2>
    <form action="{{ route('admin.register') }}" method="POST">
    {{ csrf_field() }}

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
        @if ($errors->has('name'))
        <span class="error">
        {{ $errors->first('name') }}
        </span>
        @endif

        <label for="username">Username</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
        @if ($errors->has('username'))
        <span class="error">
            {{ $errors->first('username') }}
        </span>
        @endif

        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @if ($errors->has('email'))
            <span class="error">
            {{ $errors->first('email') }}
            </span>
        @endif

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
        @endif

        <label for="password-confirm">Confirm Password</label>
        <input id="password-confirm" type="password" name="password_confirmation" required>

        <button type="submit">Add Account</button>
    </form>
@endsection

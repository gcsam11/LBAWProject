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
@endsection

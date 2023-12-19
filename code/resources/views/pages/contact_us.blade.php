<!-- resources/views/about_us.blade.php -->
@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <h2>Contact Us</h2>
    
    <form enctype="multipart/form-data" action="{{ route('contact_us.create') }}" method="POST">
        <label for="first_name">First Name*</label>
        <input type="text" id="first_name" name="first_name" required>
        <label for="last_name">Last Name*</label>
        <input type="text" id="last_name" name="last_name" required>
        <label for="email">Email*</label>
        <input type="text" id="email" name="email" required>
        <label for="message">Message*</label>
        <textarea id="message" name="message" required></textarea>
        <br>
        <button type="submit">Submit</button>
    </form>
@endsection

@extends('layouts.auth')

@section('title')
    Create Appointment
@endsection

@section('content')
    <book-appointment :user_id="{{ auth()->id() }}"></book-appointment>
@endsection

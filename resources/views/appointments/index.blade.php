@extends('layouts.auth')

@section('title')
    Appointments
@endsection

@section('content')
    <view-appointments :user_id="{{ user()->id }}"></view-appointments>
@endsection

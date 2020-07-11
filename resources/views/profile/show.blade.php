@extends('layouts.auth')

@section('title')
    Profile - {{ $user->name }}
@endsection

@section('content')
    <user-profile :user_id="{{ user()->id }}" :profile_id="{{ $user->id }}"></user-profile>
@endsection

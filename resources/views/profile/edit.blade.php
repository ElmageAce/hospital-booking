@extends('layouts.auth')

@section('title')
    Edit Profile - {{ user()->name }}
@endsection

@section('content')
    <edit-profile :user_id="{{ user()->id }}"></edit-profile>
@endsection


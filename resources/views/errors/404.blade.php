@extends('errors.layout')

@section('title', '404')

@section('content')
    <h1>404</h1>
    <h3 class="text-uppercase">Not Found!</h3>
    <p class="text-muted m-t-30 m-b-30 text-uppercase">The resource you're trying to access does not exists or has been moved.</p>

    <a href="{{ route('dashboard') }}" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back to home</a>

@endsection

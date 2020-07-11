@extends('errors.layout')

@section('title', '500')

@section('content')
    <h1>500</h1>
    <h3 class="text-uppercase text-danger">Server Error!</h3>
    <p class="text-muted m-t-30 m-b-30 text-uppercase">
        {{ $exception->message }}
    </p>

    <a href="{{ route('dashboard') }}" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back to home</a>

@endsection

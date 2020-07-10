@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')

<div class="login-box">
    <div class="white-box">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form class="form-horizontal form-material" id="loginform" method="POST" action="{{ route('password.email') }}">
            @csrf
            <h3 class="box-title m-b-20">Reset Password</h3>

            <div class="form-group">
                <div class="col-xs-12">
                    <label for="email" hidden>Email</label>
                    <input class="form-control" id="email" type="text" placeholder="Email" name="email"
                           value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <div class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>
            <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-info btn-block btn-rounded text-uppercase waves-effect waves-light">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

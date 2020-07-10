@extends('layouts.guest')

@section('title', 'Email Verification')

@section('content')

    <div class="login-box">
        <div class="white-box">

            <app-logo></app-logo>

            <h2 class="text-center">{{ __('Verify Your Email Address') }}</h2>

            @if (session('resent'))
                <div class="alert alert-success alert-dismissable text-white" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <p class="text-center">
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
            </p>

            <form class="form-horizontal form-material mt-5" id="loginform" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <div class="form-group text-center">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">
                            {{ __('click here to request another') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

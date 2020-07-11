<!DOCTYPE html>
<html lang="en">

@include('partials.guest.head')

<body>

@include('partials.preloader')

    <section id="wrapper" class="login-register"
             style="background: url({{ asset('assets/plugins/images/login-register.jpg') }}) no-repeat center center / cover !important;">

        @yield('content')

    </section>

    @include('partials.guest.scripts')

</body>

</html>

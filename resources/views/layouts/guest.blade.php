<!DOCTYPE html>
<html lang="en">

@include('partials.guest.head')

<body>

    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>

    <section id="wrapper" class="login-register"
             style="background: url({{ asset('assets/plugins/images/login-register.jpg') }}) no-repeat center center / cover !important;">

        @yield('content')

    </section>

    @include('partials.guest.scripts')

</body>

</html>

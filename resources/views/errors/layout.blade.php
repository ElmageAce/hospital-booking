<!DOCTYPE html>
<html lang="en">

@include('partials.auth.head')

<body>

<section id="wrapper" class="error-page">
    <div class="error-box" style="background: url({{ asset('assets/plugins/images/error-bg.jpg') }}) no-repeat center center #fff !important;">

        <div class="error-body text-center">
            @yield('content')
        </div>

        @include('partials.auth.footer')
    </div>
</section>

@include('partials.auth.scripts')

</body>
</html>

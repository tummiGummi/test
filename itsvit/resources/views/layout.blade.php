<!doctype html>
<html lang="en">
<head>
    <title>
        @yield('title')
    </title>

    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">

    @yield('css')
</head>
<body>
@include('layouts.nav')
<main role="main" class="container">
    @yield('content')
</main>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@yield('script')

</body>
</html>

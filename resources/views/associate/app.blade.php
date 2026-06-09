
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
    <title>Login | Asif Associates</title>
    <!-- Simple bar CSS -->
    <!-- <link rel="stylesheet" href="css/simplebar.css"> -->
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}"> -->
    <!-- Date Range Picker CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}"> -->
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app-light.css') }}" id="lightTheme">
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}" id="darkTheme" disabled> -->
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  </head>
  <body class="light ">
    <div class="wrapper vh-100">
      <div class="row align-items-center h-100 login-row">
        @yield('content')
      </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
  </body>
</html>
</body>
</html>
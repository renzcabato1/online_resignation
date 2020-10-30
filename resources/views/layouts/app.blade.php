<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('/login_design/images/favicon.ico')}}">
  
    <link rel="stylesheet" href="{{ asset('/css/all.css')}}">
</head>
<body>
    <div id = "myDiv" style="display:none;" class="loader">
    </div>
    <div id="app">
        <main >
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('/js/script.js')}}" ></script>
    <script src="{{ asset('/js/all.js')}}" defer></script>
</body>
</html>

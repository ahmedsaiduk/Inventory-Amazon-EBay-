<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <meta name="description" content="our description">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Material Design Lite">
        <meta name="mobile-web-app-capable" content="yes">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SellerPier') }}</title>

        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>

        <!-- Add to homescreen for Chrome on Android -->
        <!-- <link rel="icon" sizes="192x192" href="https://getmdl.io/assets/favicon.png"> -->
        <!-- Add to homescreen for Safari on iOS -->
        <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">
        <!-- Tile icon for Win8 (144x144 + tile color) -->
        <!-- <meta name="msapplication-TileImage" content="https://getmdl.io/assets/favicon.png"> -->
        <meta name="msapplication-TileColor" content="#3372DF">
        <link rel="shortcut icon" href="{{ asset('images/sellerpier-logo.png') }}">
        <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
        <!--
        <link rel="canonical" href="http://www.example.com/">
        -->
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" /> -->
        <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/material.blue_grey-blue.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/semantic.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/select.semanticui.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/buttons.semanticui.css') }}">
    </head>
    <body>
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header" id="app">
            @yield('content')
            
            <!-- <h6 class="text-center" style="color: #C0C0C0;"> Copyright © 2017, SellerPier</h6> -->
        </div>
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/semantic.min.js') }}"></script>
    
        <!-- Notes -->
        @include('layouts.notifications')
        <!-- end of Notes -->
        <!-- custom scripts -->
        @stack('js')
        <!-- end of custom scripts -->
    </body>
</html>
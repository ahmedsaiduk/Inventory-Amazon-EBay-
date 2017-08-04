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
    <!-- <link rel="canonical" href="http://www.example.com/"> -->
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>
<!-- Transparent header -->
<style>
    .layout-transparent {
        background-image: url('/images/spBanner1.jpg'); 
        background-repeat: no-repeat;
        height: 500px;
    /* Background image is fixed in the viewport so that it doesn't move when 
    the content's height is greater than the image's height */
    /*background-attachment: fixed;*/
    background-size: 100%;
    }
    .layout-transparent .mdl-layout__header,
    .layout-transparent .mdl-layout__drawer-button {
    /* This background is dark, so we set text to white. Use 87% black instead if
    your background is light. */
    color: white;
    }
</style>

<body>
    <div class="layout-transparent mdl-layout mdl-js-layout" id="app">
        <div class="mdl-layout__header mdl-layout__header--transparent">
            <div class="mdl-layout__header-row">
                <!-- Title -->
                <span class="mdl-layout-title">
                    <h3 style="font-family: 'Lobster', cursive; font-size: 48px;">
                    <img src="/images/sellerpier-logo.png" alt="logo" class="logo">
                    </h3>
                </span>
                <!-- Add spacer, to align navigation to the right -->
                <div class="mdl-layout-spacer"></div>
                <!-- Navigation -->
                <nav class="mdl-navigation">
                    @if (Auth::guest())	
                    <a class="mdl-navigation__link mdl-color-text--white" href="{{ url('/login') }}">Login</a>
                    <a class="mdl-navigation__link mdl-color-text--white" href="{{ url('/register') }}">Register</a>
                    @else
                    <a class="mdl-navigation__link mdl-color-text--white" href="{{ url('/home') }}">Dashboard</a>
                    @endif
                </nav>
            </div>
        </div>
        <!-- Fixed drawer, no header -->
        <div class="mdl-layout-drawer mdl-cell--hide-desktop">
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title">Title</span>
                <nav class="mdl-navigation">
                    <a class="mdl-navigation__link" href="#">Link</a>
                    <a class="mdl-navigation__link" href="#">Link</a>
                    <a class="mdl-navigation__link" href="#">Link</a>
                    <a class="mdl-navigation__link" href="#">Link</a>
                </nav>
            </div>
        </div>
        <main class="mdl-layout__content">
            <!-- content goes here -->

        </main>
    </div>
    <script src="/js/app.js"></script>
</body>
</html>
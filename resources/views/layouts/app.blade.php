<!--

Copyright © 2017 Jonathan Salmon (jonathan.salmon@hotmail.co.uk). All rights reserved.

-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @yield('title', 'Home')</title>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <!-- Styles -->
    @yield('styles')
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ route('home', $site->slug) }}">
                        {{ config('app.name', 'Hires') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li>{{ link_to_route('items.index', 'Catalog', $site->slug) }}</li>
                        @if (CAuth::check())
                        <li>{{ link_to_route('bookings.index', 'Bookings', $site->slug) }}</li>
                        @endif
                        @if (CAuth::checkAdmin())
                        <li>{{ link_to_route('admin.index', 'Settings', $site->slug) }}</li>
                        @endif
                        @if (CAuth::checkAdmin(1))
                        <li>{{ link_to_route('bank.index', 'Treasurer', $site->slug) }}</li>
                        @endif
                        <li>{{ link_to_route('terms', 'Terms & Conditions', $site->slug) }}</li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (!CAuth::check())
                          <li>{{ link_to_route('login', 'Login', $site->slug) }}</li>
                        @else
                            <li>
                                {{ link_to_route('logout', 'Logout (' . CAuth::user()->username . ')', $site->slug) }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                    @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    @yield('scripts')
</body>
</html>

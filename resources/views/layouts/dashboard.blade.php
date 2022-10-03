<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.3.4/dist/flowbite.min.css" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard-grid.css') }}" rel="stylesheet">
    <link href="{{ asset('css/overflow.css') }}" rel="stylesheet">
</head>

<body>
    <div class="main-container">
        <div class="main">
            <div class="navbar">
                <div class="logo">
                    <a href="{{ url('/') }}" class="flex items-center py-3 px-2 text-white">
                        <img class="h-12 w-12 mr-1 text-blue-400 invert-logo" src="{{ asset('images/kolibri.png') }}">
                        <span class="font-bold">Kolibri-API</span>
                    </a>
                </div>
                <div class="nav-items">
                    <div class="hidden md:flex items-center space-x-1">
                        @if (auth()->check() && auth()->user()->is_admin)
                            <a href="{{ url('/dashboard/home') }}"
                                class="font-semibold py-5 px-3 text-white background-link">Dashboard</a>
                        @else
                            <a href="{{ url('/user/dashboard/home') }}"
                                class="font-semibold py-5 px-3 text-white background-link">Dashboard</a>
                        @endif
                    </div>
                </div>
                <div class="account">
                    @guest
                        <div class="hidden md:flex items-center space-x-2 mr-1 ml-1">
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"
                                    class=" font-semibold py-2 px-3 text-white view-link">Login</a>
                            @endif
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="font-semibold py-2 px-3 text-white view-link">Register</a>
                            @endif
                        </div>
                    @else
                        <div class="hidden md:flex items-center space-x-2 mr-4">
                            <button id="dropdownDividerButton" data-dropdown-toggle="dropdownDivider"
                                class="text-white font-medium rounded-lg text-xl px-4 py-2.5 text-center inline-flex items-center"
                                type="button">{{ Auth::user()->name }}
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="dropdownDivider"
                                class="hidden z-10 w-44 text-base list-none bg-transparent rounded shadow business-dark-grey">
                                <div class="py-1" aria-labelledby="dropdownDividerButton">
                                    <a href="{{ route('admin.profile') }}"
                                        class="block py-2 px-4 text-sm text-white background-dropdown-items font-medium">Profile</a>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('logout') }}"
                                        class="block py-2 px-4 text-sm text-white background-dropdown-items font-medium"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>

            @include('layouts.sidebar')

            <div class="content overflow-auto">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>
</body>

</html>

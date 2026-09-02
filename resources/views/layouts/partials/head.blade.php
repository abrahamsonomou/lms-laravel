<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link rel="shortcut icon" type="image/x-icon" href="{{ asset('geeks/assets/images/favicon/favicon.ico') }}" />

<script src="{{ asset('geeks/assets/js/vendors/darkMode.js') }}"></script>

<link href="{{ asset('geeks/assets/fonts/feather/feather.css') }}" rel="stylesheet" />
<link href="{{ asset('geeks/assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('geeks/assets/libs/simplebar/dist/simplebar.min.css') }}" rel="stylesheet" />

<link rel="stylesheet" href="{{ asset('geeks/assets/css/theme.min.css') }}" />

<title>@yield('title', config('app.name', 'LMS')) | {{ config('app.name', 'LMS') }}</title>

@stack('styles')

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <main>
        <section class="container d-flex flex-column vh-100">
            <div class="row align-items-center justify-content-center g-0 h-lg-100 py-8">
                <div class="col-lg-5 col-md-8 py-8 py-xl-0">
                    <div class="card shadow">
                        <div class="card-body p-6 d-flex flex-column gap-4">
                            <div>
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('geeks/assets/images/brand/logo/logo-icon.svg') }}" class="mb-4" alt="{{ config('app.name') }}" />
                                </a>
                                <div class="d-flex flex-column gap-1">
                                    <h1 class="mb-0 fw-bold">@yield('auth-title')</h1>
                                    @hasSection('auth-subtitle')
                                        <span>@yield('auth-subtitle')</span>
                                    @endif
                                </div>
                            </div>

                            @include('layouts.partials.flash')

                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('layouts.partials.scripts')
</body>
</html>

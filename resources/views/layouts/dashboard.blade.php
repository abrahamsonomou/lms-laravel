<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <div id="db-wrapper">
        @include('layouts.partials.dashboard-sidebar')

        <main id="page-content">
            @include('layouts.partials.dashboard-topbar')

            <section class="container-fluid p-4">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                            <div class="mb-2 mb-md-0">
                                <h1 class="mb-0 h2 fw-bold">@yield('page-title', 'Tableau de bord')</h1>
                                @hasSection('page-subtitle')
                                    <p class="mb-0">@yield('page-subtitle')</p>
                                @endif
                            </div>
                            @yield('page-actions')
                        </div>
                    </div>
                </div>

                @include('layouts.partials.flash')

                @yield('content')
            </section>
        </main>
    </div>

    @include('layouts.partials.scripts')
</body>
</html>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | PC MAROC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Skote CSS & Icons (Fix: 'build/' au lieu de 'assets/') -->
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    @stack('css')
</head>

<body data-sidebar="dark">
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @include('partials.flash-messages')
                    @yield('content')
                </div>
            </div>
            
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6"><script>document.write(new Date().getFullYear())</script> © PC MAROC .</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Skote JS Core (Fix: 'build/' au lieu de 'assets/') -->
    <script src="{{ asset('build/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('build/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('build/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('build/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
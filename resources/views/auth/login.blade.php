@extends('layouts.master-without-nav')

@section('title')
@lang('translation.Login')
@endsection

@section('css')
<!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">
@endsection

@section('body')
<body class="auth-body-bg">
@endsection

@section('content')
<div>
    <div class="container-fluid p-0">
        <div class="row g-0">

            <div class="col-xl-9">
                <div class="auth-full-bg pt-lg-5 p-4">
                    <div class="w-100">
                        <div class="bg-overlay"></div>
                        <div class="d-flex h-100 flex-column">

                            <div class="p-4 mt-auto">
                                <div class="row justify-content-center">
                                    <div class="col-lg-7">
                                        <div class="text-center">

                                            <div dir="ltr">
                                                <div class="owl-carousel owl-theme auth-review-carousel" id="auth-review-carousel">
                                                    <div class="item">
                                                        <div class="py-3">
                                                        </div>
                                                    </div>

                                                    <div class="item">
                                                        <div class="py-3">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-xl-3">
                <div class="auth-full-page-content p-md-5 p-4">
                    <div class="w-100">

                        <div class="d-flex flex-column h-100">
                            <div class="mb-4 mb-md-5">
                                <!-- 👈 هنا تم تعويض التصاور القديمة بـ النص PC MAROC بخط كبير ونقي -->
                                <a href="#" class="d-block auth-logo text-decoration-none">
                                    <h1 style="font-weight: 800; font-size: 38px; color: #556ee6; letter-spacing: 1px; margin: 0;">PC MAROC</h1>
                                </a>
                            </div>
                            <div class="my-auto">

                                <div>
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p class="text-muted">Sign in to continue.</p>
                                </div>

                                <div class="mt-4">
                                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" id="username" placeholder="Enter Email" autocomplete="email" autofocus required>
                                            @error('email')
                                                 <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                             </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                           
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="userpassword" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" required>
                                                <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-4 mt-md-5 text-center">
                                <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Crafted with <i class="mdi mdi-heart text-danger"></i> PC MAROC</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- container-fluid -->
</div>
@endsection

@section('script')
<!-- owl.carousel js -->
<script src="{{ URL::asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>
<!-- auth-2-carousel init -->
<script src="{{ URL::asset('build/js/pages/auth-2-carousel.init.js') }}"></script>
@endsection
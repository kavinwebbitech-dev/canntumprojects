@extends('frontend.layouts.app')
@section('content')
    <style>
        .forgot-link {
            background: none;
            border: none;
            padding: 0;
            color: #001e40;
            text-decoration: none;
            font-weight: 700;
        }

        .forgot-link:hover,
        .forgot-link:focus {
            color: #001e40;
            text-decoration: underline;
            box-shadow: none;
        }
    </style>

    <section class="login_detail">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-lg-5 col-md-9">
                    <div class="login_box">
                        <div class="logo text-center mb-3">
                            <a href="#"><img src="{{ url('assets/images/canntum.svg') }}" alt="Logo"
                                    style="width:250px"></a>
                        </div>



                        @if (session('success'))
                            <div id="successMessage" class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('danger'))
                            <div id="dangerMessage" class="alert alert-danger">{{ session('danger') }}</div>
                        @endif

                        <script>
                            setTimeout(function() {
                                $('#successMessage').fadeOut('slow');
                                $('#dangerMessage').fadeOut('slow');
                            }, 5000);
                        </script>

                        <form method="POST" action="{{ route('signin') }}">
                            @csrf
                            <div class="row gy-3">

                                <!-- Phone Input -->
                                <div class="col-12">
                                    <label for="phoneInput" class="form-label">Enter Mobile Number</label>
                                    <input type="text" class="form-control" id="phoneInput" name="phone"
                                        placeholder="Enter your mobile number">
                                </div>

                                <!-- Password -->
                                <div class="col-12">
                                    <label for="passwordInput" class="form-label">Password</label>

                                    <div class="position-relative">
                                        <input type="password" class="form-control pe-5" id="passwordInput" name="password"
                                            placeholder="Enter password">

                                        <!-- Eye Icon -->
                                        <span id="togglePassword"
                                            style="position:absolute; top:50%; right:15px; transform:translateY(-50%); cursor:pointer;">

                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Remember me -->
                                <div class="col-12">
                                    {{-- <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="rememberMe"
                                            name="remember">
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div> --}}
                                    <div class="col-12 text-end">
                                        <a href="{{ route('forgot_password_step1') }}" class="forgot-link">
                                            Forgot Password?
                                        </a>
                                    </div><br>
                                    <small class="mt-2">By signing in, I agree to the Terms of Use and Privacy
                                        Policy.</small>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12">
                                    <button type="submit" class="btn submit_btn mt-3 w-100">Login</button>
                                </div>

                                <div class="col-12 text-center">
                                    <a href="{{ route('auth.google.redirect') }}"
                                        class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 mt-2"
                                        style="border-radius: 6px; padding: 10px;">
                                        <img src="https://developers.google.com/identity/images/g-logo.png"
                                            alt="Google Logo" width="20" height="20">
                                        <span>Continue with Google</span>
                                    </a>
                                </div>
                                <!-- Sign Up link -->
                                <div class="col-12 text-center mt-3">

                                    <p class="mb-0" style="font-size: 16px">Don’t have an account?
                                        <a href="{{ route('register') }}" class="text-primary fw-semibold">Sign Up</a>
                                    </p>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- jQuery Toggle Script -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle icon
            this.innerHTML = type === 'password' ?
                '<i class="fa fa-eye"></i>' :
                '<i class="fa fa-eye-slash"></i>';
        });
    </script>
    <script>
        $(document).ready(function() {
            function toggleFields() {
                if ($('#emailOption').is(':checked')) {
                    $('#emailSection').removeClass('d-none');
                    $('#phoneSection').addClass('d-none');
                } else {
                    $('#emailSection').addClass('d-none');
                    $('#phoneSection').removeClass('d-none');
                }
            }

            toggleFields(); // run once on load
            $('#emailOption, #phoneOption').on('change', toggleFields);
        });
    </script>
@endsection

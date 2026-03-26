@extends('frontend.layouts.app')
@section('content')

    <section class="login_detail">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-lg-5 col-md-9">
                    <div class="login_box">

                        <div class="logo text-center mb-3">
                            <a href="#"><img src="{{ url('assets/images/canntum.png') }}" alt="Logo"></a>
                        </div>

                        <h5 class="login_title text-center mb-4">Sign Up</h5>

                        {{-- ✅ Success Message --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- ✅ Error Message --}}
                        @if (session('danger'))
                            <div class="alert alert-danger">
                                {{ session('danger') }}
                            </div>
                        @endif

                        {{-- ✅ Laravel Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" id="form" action="{{ route('signup') }}">
                            @csrf

                            <div class="row gy-3">

                                <!-- Name -->
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter your name">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-12">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="Enter your mobile number">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter your email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-12">
                                    <label class="form-label">Password</label>

                                    <div class="position-relative">
                                        <input type="password" id="password" name="password"
                                            class="form-control pe-5 @error('password') is-invalid @enderror"
                                            placeholder="Enter your password">

                                        <span class="toggle-password" data-target="password"
                                            style="position:absolute; top:50%; right:15px; transform:translateY(-50%); cursor:pointer;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>

                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-12">
                                    <label class="form-label">Confirm Password</label>

                                    <div class="position-relative">
                                        <input type="password" id="confirm_password" name="confirm_password"
                                            class="form-control pe-5" placeholder="Re-enter your password">

                                        <span class="toggle-password" data-target="confirm_password"
                                            style="position:absolute; top:50%; right:15px; transform:translateY(-50%); cursor:pointer;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <button type="submit" class="btn submit_btn mt-3 w-100">
                                        Continue
                                    </button>
                                </div>

                                <!-- Divider -->
                                <div class="col-12 text-center mt-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <hr class="flex-grow-1">
                                        <span class="px-2 text-muted">or</span>
                                        <hr class="flex-grow-1">
                                    </div>
                                </div>

                                <!-- Google Signup -->
                                <div class="col-12 text-center">
                                    <a href="{{ route('auth.google.redirect') }}"
                                        class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                                        <img src="https://developers.google.com/identity/images/g-logo.png" width="20">
                                        Continue with Google
                                    </a>
                                </div>

                                <!-- Login Link -->
                                <div class="col-12 text-center mt-3">
                                    <p class="mb-0">
                                        Already have an account?
                                        <a href="{{ route('user.login') }}" class="text-primary fw-semibold">
                                            Login
                                        </a>
                                    </p>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ✅ jQuery First --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- ✅ jQuery Validation --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        document.querySelectorAll('.toggle-password').forEach(function(icon) {
            icon.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);

                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                this.innerHTML = type === 'password' ?
                    '<i class="fa fa-eye"></i>' :
                    '<i class="fa fa-eye-slash"></i>';
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $("#form").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        equalTo: 'input[name="password"]'
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Minimum 3 characters required"
                    },
                    phone: {
                        required: "Please enter mobile number",
                        digits: "Only numbers allowed",
                        minlength: "Must be 10 digits",
                        maxlength: "Must be 10 digits"
                    },
                    email: {
                        required: "Please enter email",
                        email: "Enter valid email address"
                    },
                    password: {
                        required: "Please enter password",
                        minlength: "Minimum 6 characters required"
                    },
                    confirm_password: {
                        required: "Please confirm password",
                        equalTo: "Passwords do not match"
                    }
                },
                errorElement: "span",
                errorClass: "text-danger mt-1",
                highlight: function(element) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid");
                }
            });

        });
    </script>

@endsection

@extends('frontend.layouts.app')
@section('content')
    <section class="my_profile">
        <div class="container">
            <div class="row gx-2">
                @include('frontend.user.sidebar')
                <div class="col-lg-10 col-md-12">
                    @if (session('success'))
                        <div id="successMessage" class="alert alert-success">
                            {{ session('success') }}
                        </div>

                        <script>
                            setTimeout(() => {
                                const msg = document.getElementById('successMessage');
                                if (msg) msg.style.display = 'none';
                            }, 5000);
                        </script>
                    @endif
                    <div class="profile_right">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="profile_title">My Profile</h2><br>
                                <h6 class="profile_subtitle">Personal Details</h6>
                            </div>
                            <div>
                                <a href="{{ route('user.profile.edit') }}">
                                    <i class="fa fa-edit fa-lg me-1"></i> 
                                </a>

                            </div>
                        </div>

                        <div class="mt-4 user_profile_detail">
                            <div class="row gy-4">
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingName"
                                            value="{{ auth()->user()->name }}" readonly>
                                        <label for="floatingName">Full Name</label>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingPhone"
                                            value="{{ auth()->user()->phone }}" readonly>
                                        <label for="floatingPhone">Mobile Number</label>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="floatingEmail"
                                            value="{{ auth()->user()->email }}" readonly>
                                        <label for="floatingEmail">Email</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

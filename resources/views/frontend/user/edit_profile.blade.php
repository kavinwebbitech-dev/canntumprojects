@extends('frontend.layouts.app')
@section('content')


 <section class="my_profile">
    <div class="container">
        <div class="row gx-2">
             @include('frontend.user.sidebar')
            <div class="col-lg-10 col-md-12">
                <div class="profile_right">
                    <h2 class="profile_title">My Profile</h2><br>
                    <h6 class="profile_subtitle">Personal Details</h6>
                    
                    @if (session('success'))
                        <div id="successMessage" class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    
                        <script>
                            // Automatically hide the success message after 5 seconds
                            setTimeout(function() {
                                document.getElementById('successMessage').style.display = 'none';
                            }, 5000);
                        </script>
                    @endif
                    
                    <form action="{{ route('user.profile.update') }}" method="POST" class="mt-4 user_profile_detail">
                        @csrf
                        {{-- @method('PUT') --}}
                        <div class="row gy-4">
                            <div class="col-lg-12">
                                <div class="form-floating ">

                                    <input type="email"  name="name" class="form-control" id="floatingName" placeholder="Jawahar"
                                        value="{{ auth()->user()->name }}" readonly>
                                    <label for="floatingName">Full Name</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="number"  name="phone" class="form-control" id="floatingPhone"
                                        placeholder="98876 56788" value="{{ auth()->user()->phone }}">
                                    <label for="floatingPhone">Mobile Number</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="email"  name="email" class="form-control" id="floatingEmail"
                                        placeholder="sample@gmail.com" value="{{ auth()->user()->email }}" readonly>
                                    <label for="floatingEmail">Email</label>
                                </div>
                            </div>
                            <button type="submit" class="btn" style="background:#212529; color:white">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection
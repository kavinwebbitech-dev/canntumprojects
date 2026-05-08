@extends('frontend.layouts.app')
@section('content')

     
<div class="login-detail">
        <div class="container">
            <div class="row gy-3 gx-0 justify-content-center align-items-center ">
                <div class="col-lg-6 col-md-6">
                    <div class="login-box mt-4 mb-4 ">
                        <h2 class="login-title">Forget Password</h2>
                        
                            @if (session('success'))
                                <div id="successMessage" class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            
                            @if (session('danger'))
                                <div id="dangerMessage" class="alert alert-danger">{{ session('danger') }}</div>
                            @endif
                            
                            <script>
                                setTimeout(function () {
                                    $('#successMessage').fadeOut('slow');
                                }, 5000);
                            
                                setTimeout(function () {
                                    $('#dangerMessage').fadeOut('slow');
                                }, 5000);
                            </script>
                            
                      <form class="row gy-3 mt-1" method="POST" action="{{ route('forgot_password_step2') }}">
                          @csrf
                        <div class="col-md-12">
                          <label for="inputEmail4" class="form-label">Email or Phone</label>
                          <input type="text" class="form-control" placeholder="Email or Phone" name="phone_or_email" id="inputEmail4" required>
                        </div>
                         <div class="col-12">
                          <button type="submit" class="btn common-btn">Get OTP </button>
                        </div>
                      </form>




                    </div>
                </div>
            </div>
        </div>
     </div>



@endsection
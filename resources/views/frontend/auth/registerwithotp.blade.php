@extends('frontend.layouts.app')
@section('content')



<section class="login_detail">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xxl-5 col-lg-5 col-md-9">
            <div class="login_box">
                <div class="logo">
                    <a href="#"><img src="<?php echo url(''); ?>/assets/images/canntum.png" alt=""></a>
                </div>
                <h5 class="login_title">Verify OTP</h5>
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
                    <form class="row gy-4" action="{{ route('otpvarification') }}" method="POST">
                        @csrf
                        <input type="hidden" class="form-input" name="user_id" value="{{ $user->id }}" placeholder="Enter Email">
                        <div class="col-md-12 text-center">
                            <input type="text" class="otp-button-size" id="otp1" name="otp[]" maxlength="1" size="1" autofocus>
                            <input type="text" class="otp-button-size" id="otp2" name="otp[]" maxlength="1" size="1">
                            <input type="text" class="otp-button-size" id="otp3" name="otp[]" maxlength="1" size="1">
                            <input type="text" class="otp-button-size" id="otp4" name="otp[]" maxlength="1" size="1">
                        </div>
                        
                        <div class="col-12">
                            <p class="text-center"> <button type="submit" class="btn common_btn">Verify <i class="bi bi-arrow-right"></i></button> </p>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    // JavaScript to move focus to the next input field after one character is entered
    document.getElementById('otp1').addEventListener('input', function() {
        if (this.value.length === 1) {
            document.getElementById('otp2').focus();
        }
    });

    document.getElementById('otp2').addEventListener('input', function() {
        if (this.value.length === 1) {
            document.getElementById('otp3').focus();
        }
    });

    document.getElementById('otp3').addEventListener('input', function() {
        if (this.value.length === 1) {
            document.getElementById('otp4').focus();
        }
    });
</script>

@endsection
@extends('frontend.layouts.app')
@section('content')

<div class="page-banner">
    <div class="page-banner-content">
        <div class="container">
         <h1 class="page-banner-title">Change Password</h1>
         <nav aria-label="breadcrumb">
             <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Change Password</li>
             </ol>
           </nav>
        </div>
     </div>
</div>
  

<div class="profile-detail">
    <div class="container">
       <div class="row gy-4">
          @include('frontend.user.sidebar')
          <div class="col-lg-9 col-md-8">
            <div class="profile-right">
                <div class="edit-box">
                 <div class="edit-heading">
                    <div class="row gy-4 align-items-center">
                        <div class="col-md-12">
                            <h5 class="edit-title">Change Password</h5>
                        </div>
                       
                    </div>
                 </div>
                 
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
                    
                    @if (session('error'))
                        <div id="errorMessage" class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    
                        <script>
                            // Automatically hide the error message after 5 seconds
                            setTimeout(function() {
                                document.getElementById('errorMessage').style.display = 'none';
                            }, 5000);
                        </script>
                    @endif
                    
                    <form class="row gy-4 gx-3" action="{{ route('user.update.password') }}" method="post" id="passwordChangeForm">
                        @csrf
                            
                        <div class="col-md-12">
                            <label for="oldPassword" class="form-label">Current Password</label>
                            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>

                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-md-12">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-md-12">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn common-btn" >Change Password</button>
                        </div>
                        
                      </form>
                </div>
           
            </div>
          </div>
       </div>
    </div>
</div>



@endsection
@extends('frontend.layouts.app')
@section('content')
    <div class="page-banner">
        <div class="page-banner-content">
            <div class="container">
                <h1 class="page-banner-title">JOIN US</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">JOIN US</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="our-gallery mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-10 text-center">
                    <h2 class="gallery-title text-center mt-4"><strong>Unlock your potential at Canntum!</strong></h2>
                </div>
            </div>
            <div class="tab-content mt-5" id="pills-tabContent">
                <div class="terms-and-onditions text-center">
                    <h4>Canntum is always open to new talents and hardworking people! If you are interested or think you can work in a very young, relaxed yet a complete fun team, please mail your resume/ profile to <b><u>careers@canntum.com</u></b> .</h4>
                    <p>We look forward to seeing your profile and you as well! </p>
                </div>
            </div>
        </div>
    </div>
@endsection

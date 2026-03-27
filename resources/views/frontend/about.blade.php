@extends('frontend.layouts.app')

@section('content')
    <!-- Page Banner -->
    <div class="page-banner py-5 border-bottom">
        <div class="page-banner-content text-center">
            <div class="container">
                <h1 class="page-banner-title mb-2">About Us</h1>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section class="about-section py-5">
        <div class="container">

            <!-- Intro -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <p class="lead text-muted fs-5">
                        Your trusted partner for smart and modern eCommerce solutions —
                        delivering reliability, innovation, and customer-focused experiences.
                    </p>
                </div>
            </div>

            <!-- Mission & Vision -->
            <div class="row mb-5">
                <div class="col-md-6 mb-4">
                    <h3 class="fw-semibold text-primary mb-3">Our Mission</h3>
                    <p>
                        Our mission is to make online shopping simple, secure, and accessible.
                        We focus on providing high-quality products, safe payments, and seamless
                        user experiences backed by advanced technology.
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <h3 class="fw-semibold text-primary mb-3">Our Vision</h3>
                    <p>
                        To become a trusted and leading eCommerce brand by offering curated products,
                        user-friendly features, and industry-standard services that empower customers
                        and businesses alike.
                    </p>
                </div>
            </div>

            <!-- Stats -->
            <div class="row text-center py-4 bg-light rounded shadow-sm mb-5">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h2 class="fw-bold text-primary">10K+</h2>
                    <p class="mb-0">Happy Customers</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h2 class="fw-bold text-primary">5K+</h2>
                    <p class="mb-0">Products Delivered</p>
                </div>
                <div class="col-md-4">
                    <h2 class="fw-bold text-primary">4.9★</h2>
                    <p class="mb-0">Customer Rating</p>
                </div>
            </div>

            <!-- Why Choose Us -->
            <div class="mb-5 text-center">

                <h3 class="fw-semibold text-primary mb-3">
                    Why Choose Us
                </h3>

                <ul class="list-unstyled d-inline-block text-start mx-auto">
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                        High Quality & Verified Products
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                        Dedicated Customer Care
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                        Fast & Secure Checkout
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                        Easy Returns & Refunds
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                        100% Safe Payment Transactions
                    </li>
                </ul>

            </div>



            <!-- Commitment -->
            <div class="text-center mx-auto">
                <h3 class="fw-semibold text-primary mb-3">Our Commitment</h3>
                <p>
                    We are committed to offer a trustworthy and seamless shopping experience.
                    With continuous improvement and customer-feedback, we ensure that every customer
                    receives quality products and reliable service.
                </p>
            </div>

        </div>
    </section>
@endsection

@extends('frontend.layouts.app')

@section('content')
    <div class="page-banner py-5 bg-[#272727] border-bottom">
        <div class="page-banner-content text-center">
            <div class="container">
                <h1 class="page-banner-title mb-2">Contact</h1>
                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Care</li>
                    </ol>
                </nav> --}}
            </div>
        </div>
    </div>

    <section class="customer-care py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-semibold text-primary mb-3">We’re here to help you</h2>
                    <p class="text-muted fs-5">
                        Our Customer Care team is dedicated to provide with the best possible support .
                        Whether you need help with an order, product information or general assistance,
                        we’re just a call away. Please check our contact details and working hours below.
                    </p>
                </div>
            </div>

           <div class="row justify-content-between align-items-stretch">

                <!-- LEFT CONTACT BOX -->
                <div class="col-md-6 col-lg-5 mb-4 mb-md-0">
                    <div class="card text-center shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-5 text-uppercase text-primary fw-semibold">
                                Contact
                            </h4>

                            <p class="fs-5 mt-1">
                                <i class="bi bi-telephone-fill text-primary me-2"></i>
                                <strong style="font-size:17px">+91 63747 23745</strong>
                            </p>

                            <h6 class="text-secondary fw-semibold mt-3">
                                Working Hours (IST)
                            </h6>

                            <div class="working-hours text-start mt-3 px-5">

                                <div class="d-flex justify-content-between py-1">
                                    <span>Monday</span><strong>10:00 AM – 07:00 PM</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span>Tuesday</span><strong>10:00 AM – 07:00 PM</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span>Wednesday</span><strong>10:00 AM – 07:00 PM</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span>Thursday</span><strong>10:00 AM – 07:00 PM</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span>Friday</span><strong>10:00 AM – 07:00 PM</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span>Saturday</span><strong>10:00 AM – 07:00 PM</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 text-danger fw-semibold">
                                    <span>Sunday</span><span class="text-center"style="margin-right: 50px;">Closed</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT ASSISTANCE BOX (MATCHED DESIGN) -->
                <div class="col-md-6 col-lg-5 mb-4 mb-md-0">
                    <div class="card text-center shadow-sm border-0 h-lg-50 ">
                        <div class="card-body p-4 ">
                            <h4 class="card-title mb-5 text-uppercase text-primary fw-semibold">
                                Special Assistance
                            </h4>

                            <ul class="list-group list-group-flush text-center fs-6">
                                <li class="list-group-item border-0 px-0">
                                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                                    <strong>Email:</strong>
                                    canntumemporium@gmail.com
                                </li>

                                {{-- Uncomment if needed later
                    <li class="list-group-item border-0 px-0">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        <strong>Address:</strong><br>
                        CANNTUM OFFICE,<br>
                        379(B), Crosscut road,<br>
                        Gandhipuram, Coimbatore,<br>
                        Tamil Nadu, 641012
                    </li>
                    --}}
                            </ul>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row justify-content-center mt-5">

            </div>
        </div>
    </section>
@endsection

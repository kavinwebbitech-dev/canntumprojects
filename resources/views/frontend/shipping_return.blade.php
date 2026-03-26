@extends('frontend.layouts.app') 
@section('content') 

    <div class="page-banner">
        <div class="page-banner-content">
            <div class="container">
                <div class="ps-4">
                    <h1 class="page-banner-title text-center">Shipping & Returns Policy</h1>
                    {{-- <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Shipping & Returns Policy</li>
                        </ol>
                    </nav> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="our-gallery my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-10 text-center">
                {{-- <h2 class="gallery-title text-center"><strong>Shipping  & Returns Policy</strong></h2> --}}
            </div>
        </div>
        <div class="tab-content mt-5" id="pills-tabContent">
            <div class="policy-content">
    
                <!-- Shipping Section -->
                <h3 class="mb-3">Shipping</h3>
                <p>
                    Purchases are shipped from our warehouse. 
                    Please allow the following number of days from receipt of your order.
                </p>
                <ul class="mb-3">
                    <li><strong>For India orders</strong> – All domestic prepaid orders are processed within <b>1-2 business days</b>, whereas COD orders take <b>2 business days</b> to process as we verify the order to ensure a seamless operation.</li>
                    <li>Orders will be delivered within <b>1-5 business days</b> upon confirmation.</li>
                    <li>Delivery to locations which are in low coverage or difficult-to-reach areas may take between <b>10-12 working days</b>.</li>
                </ul>
    
                <h3>Order delivery</h3>
                <ul class="mb-3">
                    <li>Order deliveries will take place between 10:00 AM – 6:00 PM Monday – Saturday (excluding public holidays).</li>
                    <li>Goods will need to be signed upon delivery. If you cannot be there, please suggest an alternative (family member, colleague, neighbour, etc.).</li>
                    <li>Canntum takes no responsibility for goods signed by an alternative person.</li>
                    <li>Canntum is not responsible for damage after delivery.</li>
                    <li>For any complaints, please contact Customer Care within <b>7 business days</b> of order delivery.</li>
                    <li>Shipping and handling rates may vary based on product, packaging, size, volume, and type. Charges are shown at checkout before payment.</li>
                </ul>
    
                <!-- Returns Section -->
                <h3 class="mb-3 mt-4">Return</h3>
                <p>
                    Returns will only be accepted for products that have been 
                    <b>damaged in transit</b>, have <b>physical defects</b>, or in case of an 
                    <b>incorrect product</b> being shipped. 
                    An email with photos of the damaged/incorrect product should be sent within the stipulated time 
                    for us to accept the request.
                </p>
    
                <p>
                    you wish to return all or part of your order, you need to inform us within 
                    <b>48 hours of receipt</b> by writing to us at 
                    <a href="mailto:support@canntum.com">canntumemporium.com</a> 
                    {{-- or contacting us at <a href="tel:+919600550730">+91 9600550730</a>. --}}
                </p>
    
                <ul class="mb-3">
                    <li>Returns will not be accepted if the product has been used, seal broken, or serial number tampered with.</li>
                    <li>On receipt of the returned product, Canntum will inspect and validate the claim.</li>
                    <li>If approved, a <b>refund or replacement</b> will be issued.</li>
                    <li>If found ineligible, the product will be couriered back to you.</li>
                </ul>
     
                <h3>Refund Timeline</h3> 
                <ul>
                    <li>For online payments – Refund within <b>7 working days</b> to original payment source (debit/credit card, bank, wallet, etc.).</li>
                    <li>For COD orders – Refund within <b>14 working days</b> to your bank account after request approval.</li>
                </ul>
    
            </div>
        </div>
    </div>

</div>



@endsection

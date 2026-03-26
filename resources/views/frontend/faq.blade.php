@extends('frontend.layouts.app')
@section('content')
    <div class="page-banner">
        <div class="page-banner-content">
            <div class="container">
                <h1 class="page-banner-title text-center">Frequently Asked Questions</h1>
                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Frequently Asked Questions</li>
                    </ol>
                </nav> --}}
            </div>
        </div>
    </div>

    <div class="our-gallery mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-10">
                <h6 class="gallery-title">Frequently Asked Questions</h6>
            </div>
        </div>
        <div class="accordion" id="faqAccordion">
    
            <!-- Category 1: ORDER -->
            <h5 class="mb-3 mt-4">1) ORDER</h5>
    
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                        How do I order?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Step 1: Click on the product of your choice.<br>
                        Step 2: Click on BUY IT NOW or ADD TO CART<br>
                        Step 3: Click on CHECKOUT<br>
                        Step 4: Enter your details and place your order</p>
                        <p>
                            You will receive a confirmation with tracking details in your WhatsApp and Mail within 30 minutes. 
                            If you have any further questions, you can WhatsApp us 
                            <a href="https://wa.me/916374723745" target="_blank">+91 6374723745</a> 
                            or Mail us at 
                            <a href="mailto:support@canntum.com">support@canntum.com</a>.
                        </p>
                    </div>
                </div>
            </div>
    
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                        Where do I check my order/ tracking details?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>
                            You will receive a confirmation with the order/ tracking details in your WhatsApp and Mail within 
                            30 minutes of ordering for all the orders. 
                            You can check your inbox in WhatsApp from the number <strong>+91 6374723745</strong> 
                            or in Mail from <strong>support@canntum.com</strong>.
                        </p>
                    </div>
                </div>
            </div>
    
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                        Do we have Cash On Delivery (COD) option?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>
                            Yes, of course. We understand your concerns and comfort. 
                            This facility allows you to pay your invoice amount in cash at the time of actual 
                            <strong>Ecom Express</strong> delivery (our delivery partner) at your doorstep. 
                            You can only make COD payments in Indian Rupees. 
                        </p>
                        <p>
                            Currently, we do not accept cheques or demand drafts. Due to the limitations of the couriers 
                            we use, COD is not yet available for all pin codes.
                        </p>
                    </div>
                </div>
            </div>
    
            <!-- Category 2: PRODUCTS -->
            <h5 class="mb-3 mt-4">2) PRODUCTS</h5>
    
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                        Are Canntum products safe for everyone?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>
                            Yes. All Canntum products are tested before packaging. Canntum products are of best quality 
                            with 100% Natural, Synthetic free, Chemical free, Cruelty free specially handcrafted 
                            for our beautiful customers.
                        </p>
                        <p>
                            <strong>Special Note:</strong> All products are personally tested on our Founders before 
                            being open to our customers. So this is a 100% safe solution!
                        </p>
                    </div>
                </div>
            </div>
    
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                        Who all can use Canntum products?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>
                            The Canntum Company creates products for people of age from 6-60 varying according to the products. 
                            Canntum is extremely safe for people of all ages, all genders and all skin types.
                        </p>
                    </div>
                </div>
            </div>
    
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                        Where does Canntum source products from?
                    </button>
                </h2>
                <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>
                            All Canntum products are sourced from the farmers of Tamil Nadu. 
                            Canntum products are all 100% natural with no chemicals.
                        </p>
                    </div>
                </div>
            </div>
    
        </div>
    </div>

</div>


@endsection

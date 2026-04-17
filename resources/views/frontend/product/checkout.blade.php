@extends('frontend.layouts.app')
@section('content')
    <style>
        .no-hover,
        .no-hover:hover,
        .no-hover:focus,
        .no-hover:active {
            background: inherit !important;
            color: inherit !important;
            border-color: inherit !important;
            box-shadow: none !important;
            outline: none !important;
            cursor: pointer;
        }

        .no-hover {
            transition: none !important;
        }

        .payment-box {
            display: flex;
            align-items: center;
            padding: 0% 15px;
            justify-content: center;
            width: 100%;
            /* 🔥 IMPORTANT */
            height: 50px;
            border: 2px solid #ddd;
            /* border-radius: 14px; */
            cursor: pointer;
            background: #fff;
            transition: all 0.3s ease;
            text-align: center;
            margin-top: 30px;
        }

        /* hide radio */
        .payment-box input[type="radio"] {
            display: none;
        }

        /* text */
        .payment-text {
            font-size: 15px;
            font-weight: 500;
            white-space: nowrap;
            /* no wrap */
            display: block;
            /* 🔥 NOT inline */
        }

        /* selected */
        .payment-box:has(input:checked) {
            border-color: #001E40;
            background-color: #f1f7ff;
        }

        /* hover */
        .payment-box:hover {
            border-color: #001E40;
        }
    </style>
    @php
        // $totalgst = (float) str_replace(',', '', $total_gst ?? 0);
        $authUserId = Auth::id();
        $getAllAddress = App\Models\Address::where('user_id', $authUserId)->get();
        $getDefaultAddress = $getAllAddress->firstWhere('make_default', 1);
        $total = 0;
    @endphp


    <div class="modal fade form_modal" id="newAddressModal" tabindex="-1" aria-labelledby="newAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5">Add Address</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row gy-4 gx-3" action="{{ route('user.addNewAddress') }}" method="post" id="myForm">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">First Name*</label>
                            <input type="text" class="form-control" placeholder="Tom" name="fname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name*</label>
                            <input type="text" class="form-control" placeholder="Latham" name="lname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone*</label>
                            <input type="text" class="form-control" placeholder="Enter Your Phone" name="phone"
                                maxlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email*</label>
                            <input type="email" class="form-control" placeholder="tom@mail.com" name="email" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address*</label>
                            <input type="text" class="form-control" placeholder="Enter your address" name="address"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country*</label>
                            <select name="country" id="country" class="form-control" required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 d-flex gap-1 ">
                            <!-- State Dropdown -->
                            <div class="col-md-9">
                                <label class="form-label">State*</label>
                                <select name="state" id="state" class="form-control" required>
                                    <option value="">Select State</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state }}">{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- State Code Input -->
                            <div class="col-md-3">
                                <label class="form-label">State Code</label>
                                <input type="text" id="state_code" name="state_code" class="form-control" readonly>
                            </div>
                        </div>



                        <div class="col-md-6">
                            <label class="form-label">Town / City*</label>
                            <select name="city" id="city" class="form-control" required>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pincode*</label>
                            <input type="text" class="form-control" placeholder="Enter Your Pincode" name="pincode"
                                maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required>
                            <small class="text-danger d-none" id="pincodeError">
                                Pincode must be exactly 6 digits
                            </small>
                        </div>
                        <script>
                            document.querySelector('input[name="pincode"]').addEventListener('input', function() {
                                this.value = this.value
                                    .replace(/\D/g, '') // remove non-digits
                                    .replace(/^0+/, '') // prevent starting with 0
                                    .slice(0, 6); // max 6 digits
                            });
                        </script>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="make_default"
                                    id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Make this my default address</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn common_btn w-100 d-block">Save Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Address Modal -->
    <div class="modal fade address_modal" id="changeAddressModal" tabindex="-1"
        aria-labelledby="changeAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5">Change Shipping Address</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.setDefault') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-1">
                            @if ($getAllAddress->count())
                                @foreach ($getAllAddress as $item)
                                    <div class="modal_address_box">
                                        <label class="address-radio d-flex align-items-start gap-2">
                                            <input type="radio" class="mt-1" name="address"
                                                value="{{ $item->id }}"
                                                @if ($item->make_default) checked @endif>

                                            <div>
                                                <span class="select-text">Select</span>

                                                <address class="address_text mt-1">
                                                    <b>
                                                        {{ $item->shipping_name }}<br>
                                                        {{ $item->shipping_address }}<br>
                                                        {{ $item->city }} - {{ $item->pincode }} <br>
                                                        {{ $item->state }}
                                                    </b>
                                                </address>

                                                <p class="mb-0"><b>Mobile: {{ $item->shipping_phone }}</b></p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                                <p class="mt-4">
                                    <button type="submit" class="btn common_btn w-100 d-block">Confirm Shipping
                                        Address</button>
                                </p>
                            @else
                                <p class="not_found">No address found!</p>
                                <p class="mt-4">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#newAddressModal"
                                        class="btn common_btn w-100 d-block">Add Address</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <section class="inner_pages">
        <div class="container">

            <!-- Step Indicator -->
            <div class="step_detail mb-4">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="step-indicator d-flex align-items-center justify-content-between">
                            <div class="step step1 active text-center">
                                <div class="step-icon">1</div>
                                <p>Cart</p>
                            </div>
                            <div class="indicator-line active flex-grow-1"></div>
                            <div class="step step2 active text-center">
                                <div class="step-icon">2</div>
                                <p>Address & Shipping</p>
                            </div>
                            <div class="indicator-line active flex-grow-1"></div>
                            <div class="step step3 text-center">
                                <div class="step-icon">3</div>
                                <p>Payment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="page_title">Checkout</h2>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        const successAlert = document.getElementById('add-success-alert');
                        const errorAlert = document.getElementById('add-error-alert');

                        if (successAlert) successAlert.classList.remove('show');
                        if (errorAlert) errorAlert.classList.remove('show');
                    }, 5000); // Alerts disappear after 5 seconds
                });
            </script>

            <div class="checkout_detail">
                @if (session('error'))
                    <div class="alert alert-danger" id="coupon-error-alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('addsuccess'))
                    <div class="alert alert-success" id="coupon-success-alert">
                        {{ session('addsuccess') }}
                    </div>
                @endif
                <div class="row gy-4">
                    <!-- Cart & Addresses -->
                    <div class="col-lg-8">
                        @if ($getDefaultAddress)
                            <div class="address_box mb-3">
                                <div class="row gx-3">
                                    <div class="col-md-9">
                                        <h5 class="address_title">Shipping Address</h5>
                                        <address class="address_text">
                                            <b>
                                                {{ $getDefaultAddress->shipping_name }}<br>
                                                {{ $getDefaultAddress->shipping_address }}<br>
                                                {{ $getDefaultAddress->city }} - {{ $getDefaultAddress->pincode }}
                                                <br>
                                                {{ $getDefaultAddress->state }}
                                                {{-- {{ optional($getDefaultAddress->state)->name }} --- IGNORE --}}

                                            </b>
                                        </address>
                                        <p><b>Mobile: {{ $getDefaultAddress->shipping_phone }}</b></p>
                                    </div>
                                    <div class="col-md-3 d-flex justify-content-end">
                                        <p class="text-end">
                                            <a data-bs-toggle="modal" data-bs-target="#changeAddressModal"
                                                href="#">Change</a>
                                            @if ($getDefaultAddress)
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#editaddress"
                                                    data-id="{{ $getDefaultAddress->id }}"
                                                    data-fname="{{ explode(' ', $getDefaultAddress->shipping_name)[0] ?? '' }}"
                                                    data-lname="{{ explode(' ', $getDefaultAddress->shipping_name)[1] ?? '' }}"
                                                    data-email="{{ $getDefaultAddress->shipping_email }}"
                                                    data-phone="{{ $getDefaultAddress->shipping_phone }}"
                                                    data-address="{{ $getDefaultAddress->shipping_address }}"
                                                    data-country="{{ $getDefaultAddress->country }}"
                                                    data-state="{{ $getDefaultAddress->state }}"
                                                    data-city="{{ $getDefaultAddress->city }}"
                                                    data-pincode="{{ $getDefaultAddress->pincode }}"
                                                    data-make_default="{{ $getDefaultAddress->make_default }}"
                                                    class="mx-3">
                                                    Edit
                                                </a>
                                            @endif
                                        </p>
                                        {{-- <p class="text-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#newAddressModal"
                                                class="address_link">
                                                <i class="fa-solid fa-plus"></i> Add New
                                            </a>
                                        </p> --}}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="address_box text-center mb-3">
                                <div class="d-flex justify-content-end mb-2">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#changeAddressModal">
                                        Change
                                    </a>
                                </div>
                                <p>No default address found.</p>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#newAddressModal"
                                    class="btn common_btn mt-2">Add Address</a>
                            </div>
                        @endif

                        @if (session('cart'))
                            @foreach (session('cart') as $id => $details)
                                @php
                                    $price = round($details['offer_price']);
                                    $total += $price * $details['quantity'];
                                @endphp
                            @endforeach
                        @endif
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            setTimeout(function() {
                                let error = document.getElementById('coupon-error-alert');
                                let success = document.getElementById('coupon-success-alert');
                                if (error) error.style.display = 'none';
                                if (success) success.style.display = 'none';
                            }, 4000);
                        });
                    </script>

                    <!-- Order Summary & Coupon -->
                    <div class="col-lg-4">
                        <div class="total_box border rounded-3 p-3">

                            <h5 class="mb-3 fw-bold">ORDER SUMMARY</h5>

                            @php
                                $coupon = session('coupon');
                                $couponDiscount = $coupon ? ($total * $coupon['percentage']) / 100 : 0;
                            @endphp

                            {{-- Coupon Section --}}
                            @if ($coupon)
                                <div class="applied-coupon mb-2 d-flex justify-content-between">
                                    <p>Coupon Applied: <strong>{{ $coupon['code'] }}</strong>
                                        ({{ $coupon['percentage'] }}%)</p>
                                    <form action="{{ route('remove.coupon') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('apply.coupon') }}" method="POST" class="mb-2">
                                    @csrf
                                    <div class="input-group mt-2">
                                        <input type="text" class="form-control" placeholder="Enter Coupon Code"
                                            name="coupon_code" required>
                                        <button type="submit" class="btn search-btn no-hover"
                                            style="border: none">Apply</button>
                                    </div>
                                </form>
                            @endif
                            {{-- Hidden Inputs --}}
                            @if ($getDefaultAddress)
                                <input type="hidden" id="shippingState" value="{{ $getDefaultAddress->state ?? '' }}">
                                <input type="hidden" id="subtotalAmount" value="{{ $subtotal }}">
                                <input type="hidden" id="gstAmount" value="{{ $total_gst }}">
                            @endif
                            <input type="hidden" id="baseSubtotal" value="{{ $total }}">

                            {{-- Price Breakdown --}}
                            <div id="orderSummary" class="mt-3">

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span>₹ <span id="subtotal">{{ number_format($total, 2) }}</span></span>
                                </div>

                                <!-- GST Inject Here -->
                                {{-- <div id="gstBreakup"></div> --}}

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Coupon Discount (-)</span>
                                    <span>₹ <span id="coupon">{{ number_format($couponDiscount, 2) }}</span></span>
                                </div>

                               <div class="d-flex justify-content-between mb-3">
                                    <span>Shipping</span>
                                    <span>
                                        @if($shipping_charge > 0)
                                            ₹ {{ number_format($shipping_charge, 2) }}
                                        @else
                                            FREE
                                        @endif
                                    </span>
                                </div>
                                <hr>

                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span class="d-flex">Total<p class="mx-1 mt-1">(Incl of all taxes)</p></span>
                                    <span>
                                        ₹ {{ number_format($subtotal + $shipping_charge - $couponDiscount, 2) }}
                                    </span>
                                </div>


                            </div>

                            {{-- Payment Form --}}
                            <form id="checkoutForm" method="POST" action="{{ route('payment.process') }}">
                                @csrf
                                @if (isset($getDefaultAddress) && $getDefaultAddress)
                                    <input type="hidden" name="shipping_address" value="{{ $getDefaultAddress->id }}">
                                @endif
                                <!-- Hidden Inputs -->
                                <input type="hidden" name="total_amount"
                                    value="{{ $total - $couponDiscount }}">
                                <input type="hidden" name="shipping_charge" value="{{ $shipping_charge }}">
                                <input type="hidden" name="coupon_discount"
                                    value="{{ number_format($couponDiscount, 2) }}">

                                <input type="hidden" name="payment_method" id="paymentMethod" value="razorpay">



                                <!-- Payment Method Selection -->
                                <div class="padd-both-30">
                                    <div class="container">
                                        <div class="d-flex  justify-content-between  payment-wrapper">

                                            <div class="">
                                                <label class="payment-box">
                                                    <input type="radio" name="payment_method" value="razorpay" checked>
                                                    <span class="payment-text">Online Payment</span>
                                                </label>
                                            </div>

                                            <div class="">
                                                <label class="payment-box">
                                                    <input type="radio" name="payment_method" value="cod">
                                                    <span class="payment-text">Cash on Delivery</span>
                                                </label>
                                            </div>

                                        </div>


                                    </div>
                                </div>


                                <!-- Confirm Button -->
                                <p class="mt-4 text-center">
                                    <button type="button" class="btn w-100 d-block common_btn"
                                        onclick="submitCheckout()">Confirm</button>
                                </p>
                            </form>
                            <script>
                                function submitCheckout() {

                                    // Check if shipping address exists
                                    const addressInput = document.querySelector('input[name="shipping_address"]');

                                    if (!addressInput || addressInput.value === "") {

                                        alert("Please add your shipping address.");

                                        // Open Add Address Modal automatically
                                        let modal = new bootstrap.Modal(document.getElementById('newAddressModal'));
                                        modal.show();

                                        return; // STOP form submit
                                    }

                                    // Get selected payment method
                                    const selected = document.querySelector('input[name="payment_method"]:checked').value;

                                    // Set hidden field
                                    document.getElementById('paymentMethod').value = selected;

                                    // Submit form
                                    document.getElementById('checkoutForm').submit();
                                }
                            </script>

                        </div>
                        <div class="product-shipping-box mt-4 rounded-none">
                            <div class="d-flex gap-3" style="border-radius: 0;">
                                <div class="flex-fill border p-2 text-center">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="shipping-icon mb-2">
                                            <img src="{{ url('assets/images/fast-delivery.svg') }}" alt=""
                                                style="height:36px;">
                                        </div>
                                        <h6 class="title mb-0">All India Delivery</h6>
                                    </div>
                                </div>
                                <div class="flex-fill border p-2 text-center">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="shipping-icon mb-2">
                                            <img src="{{ url('assets/images/return.svg') }}" alt=""
                                                style="height:36px;">
                                        </div>
                                        <h6 class="title mb-0">Easy returns</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


               <div class="modal fade form_modal" id="editaddress" tabindex="-1" aria-labelledby="editAddressModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5">Edit Address</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form class="row gy-4 gx-3" action="{{ route('user.updateAddress') }}" method="post">
                                @csrf

                                <!-- ðŸ”¥ IMPORTANT HIDDEN ID -->
                                <input type="hidden" name="id" id="edit_id">

                                <div class="col-md-6">
                                    <label class="form-label">First Name*</label>
                                    <input type="text" class="form-control" name="fname" id="edit_fname" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Last Name*</label>
                                    <input type="text" class="form-control" name="lname" id="edit_lname" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone*</label>
                                    <input type="text" class="form-control" name="phone" id="edit_phone"
                                        maxlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email*</label>
                                    <input type="email" class="form-control" name="email" id="edit_email" required>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Address*</label>
                                    <input type="text" class="form-control" name="address" id="edit_address"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Country*</label>
                                    <select name="country" id="edit_country" class="form-control" required>
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->name }}">
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="row gx-2 align-items-end">
                                        <div class="col-md-9">
                                            <label class="form-label">State*</label>
                                            <select name="state" id="edit_state" class="form-control" required>
                                                <option value="">Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state }}">
                                                        {{ $state }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">State-code</label>
                                            <input type="text" id="edit_state_code" name="state_code"
                                                class="form-control text-center" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Town / City*</label>
                                    <select name="city" id="edit_city" class="form-control" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Pincode*</label>
                                    <input type="text" class="form-control" name="pincode" id="edit_pincode"
                                        maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check d-none" id="edit_make_default_container">
                                        <input type="hidden" name="make_default" value="0">
                                        <input type="checkbox" id="edit_make_default" name="make_default"
                                            value="1">
                                        <label class="form-check-label">
                                            Make this my default address
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn common_btn w-100 d-block">
                                        Update Address
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const countryApi = "https://countriesnow.space/api/v0.1/countries/states";
            const cityApi = "https://countriesnow.space/api/v0.1/countries/state/cities";

            /* ================= GST STATE CODE MAP ================= */
            const gstStateCodes = {
                "Jammu and Kashmir": "01",
                "Himachal Pradesh": "02",
                "Punjab": "03",
                "Chandigarh": "04",
                "Uttarakhand": "05",
                "Haryana": "06",
                "Delhi": "07",
                "Rajasthan": "08",
                "Uttar Pradesh": "09",
                "Bihar": "10",
                "Sikkim": "11",
                "Arunachal Pradesh": "12",
                "Nagaland": "13",
                "Manipur": "14",
                "Mizoram": "15",
                "Tripura": "16",
                "Meghalaya": "17",
                "Assam": "18",
                "West Bengal": "19",
                "Jharkhand": "20",
                "Odisha": "21",
                "Chhattisgarh": "22",
                "Madhya Pradesh": "23",
                "Gujarat": "24",
                "Dadra and Nagar Haveli and Daman and Diu": "26",
                "Maharashtra": "27",
                "Karnataka": "29",
                "Goa": "30",
                "Lakshadweep": "31",
                "Kerala": "32",
                "Tamil Nadu": "33",
                "Puducherry": "34",
                "Andaman and Nicobar Islands": "35",
                "Telangana": "36",
                "Andhra Pradesh": "37",
                "Ladakh": "38"
            };

            function removeDiacritics(str) {
                return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            }

            /* ================= LOAD STATES ================= */
            async function loadStates(countryName, stateSelect, selectedState = null) {

                stateSelect.innerHTML = '<option value="">Select State</option>';

                const res = await fetch(countryApi);
                const data = await res.json();

                const selectedCountry = data.data.find(c => c.name === countryName);

                if (selectedCountry && selectedCountry.states.length) {
                    selectedCountry.states.forEach(s => {

                        const stateName = typeof s === "string" ? s : s.name;

                        const opt = document.createElement("option");
                        opt.value = stateName;
                        opt.textContent = stateName;

                        if (selectedState && selectedState === stateName) {
                            opt.selected = true;
                        }

                        stateSelect.appendChild(opt);
                    });
                }
            }

            /* ================= LOAD CITIES ================= */
            async function loadCities(countryName, stateName, citySelect, selectedCity = null) {

                citySelect.innerHTML = '<option value="">Select City</option>';

                const res = await fetch(cityApi, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        country: countryName,
                        state: stateName
                    })
                });

                const data = await res.json();

                if (data.data && data.data.length) {
                    data.data.forEach(c => {

                        const cleanCity = removeDiacritics(c);

                        const opt = document.createElement("option");
                        opt.value = cleanCity;
                        opt.textContent = cleanCity;

                        if (selectedCity && selectedCity === cleanCity) {
                            opt.selected = true;
                        }

                        citySelect.appendChild(opt);
                    });
                }
            }

            /* ================= ADD FORM ================= */
            const country = document.getElementById("country");
            const state = document.getElementById("state");
            const city = document.getElementById("city");
            const stateCode = document.getElementById("state_code");

            if (country) {

                country.addEventListener("change", async function() {
                    await loadStates(this.value, state);
                    city.innerHTML = '<option value="">Select City</option>';
                    stateCode.value = "";
                });

                state.addEventListener("change", async function() {

                    // ✅ Auto GST State Code
                    stateCode.value = gstStateCodes[this.value] || "";

                    await loadCities(country.value, this.value, city);
                });
            }

            /* ================= EDIT FORM ================= */
            const editCountry = document.getElementById("edit_country");
            const editState = document.getElementById("edit_state");
            const editCity = document.getElementById("edit_city");
            const editStateCode = document.getElementById("edit_state_code");

            document.querySelectorAll('[data-bs-target="#editaddress"]').forEach(button => {

                button.addEventListener("click", async function() {

                    const selectedCountry = this.dataset.country;
                    const selectedState = this.dataset.state;
                    const selectedCity = this.dataset.city;

                    document.getElementById('edit_id').value = this.dataset.id;
                    document.getElementById('edit_fname').value = this.dataset.fname;
                    document.getElementById('edit_lname').value = this.dataset.lname;
                    document.getElementById('edit_email').value = this.dataset.email;
                    document.getElementById('edit_phone').value = this.dataset.phone;
                    document.getElementById('edit_address').value = this.dataset.address;
                    document.getElementById('edit_country').value = selectedCountry;
                    document.getElementById('edit_pincode').value = this.dataset.pincode;
                    document.getElementById('edit_make_default').checked =
                        this.dataset.make_default == 1;

                    // ✅ Load States
                    await loadStates(selectedCountry, editState, selectedState);

                    // ✅ Auto GST State Code (EDIT)
                    editStateCode.value = gstStateCodes[selectedState] || "";

                    // ✅ Load Cities
                    await loadCities(selectedCountry, selectedState, editCity, selectedCity);

                    // ✅ Update state code when user changes state in edit
                    editState.addEventListener("change", function() {
                        editStateCode.value = gstStateCodes[this.value] || "";
                    });
                });
            });

        });
    </script>
  
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const gstContainer = document.getElementById("gstBreakup");

            if (!gstContainer) return;

            let subtotal = parseFloat(document.getElementById("subtotalAmount")?.value || 0);
            let gstAmount = parseFloat(document.getElementById("gstAmount")?.value || 0);
            let state = (document.getElementById("shippingState")?.value || "").toLowerCase().trim();

            // Calculate GST % from subtotal and gst amount
            // gstRate = (gstAmount / subtotal) * 100  — rounded to nearest integer
            let gstRate = (subtotal > 0) ? Math.round((gstAmount / subtotal) * 100) : 0;

            let cgst = gstAmount / 2;
            let sgst = gstAmount / 2;

            gstContainer.innerHTML = "";

            if (gstAmount <= 0) return; // No GST, show nothing

            if (state === "tamil nadu") {
                // Same state → split into CGST + SGST (each half the GST rate)
                let halfRate = gstRate / 2;

                gstContainer.innerHTML = `
                    <div class="d-flex justify-content-between mb-2">
                        <span>CGST (${halfRate}%)</span>
                        <span>₹ ${cgst.toFixed(2)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>SGST (${halfRate}%)</span>
                        <span>₹ ${sgst.toFixed(2)}</span>
                    </div>
                `;
            } else {
                // Different state → IGST (full rate)
                gstContainer.innerHTML = `
                    <div class="d-flex justify-content-between mb-2">
                        <span>IGST (${gstRate}%)</span>
                        <span>₹ ${gstAmount.toFixed(2)}</span>
                    </div>
                `;
            }
        });
    </script>





@endsection

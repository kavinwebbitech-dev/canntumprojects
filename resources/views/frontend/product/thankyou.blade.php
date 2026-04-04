@extends('frontend.layouts.app')
@section('content')
    <style>
        .invoice-btn {
            background: #fff;
            border: 1px solid var(--orange-color);
            padding: 11px 20px;
            border-radius: 30px;
            color: var(--orange-color);
            font-size: 12px;
            text-transform: capitalize;
            font-weight: 500;
            outline: none;
            margin-right: 10px;
        }

        .invoice-btn:hover {
            background: var(--orange-color);
            color: #fff;
        }
    </style>

    <section class="inner_pages">
        <div class="container">

            <div class="confirmed_detail">
                <div class="row gy-4">
                    <div class="col-lg-8">
                        <div class="order_box mb-4">
                            <div class="body">
                                <div class="row gy-4 align-items-center">

                                    <div class="col-lg-4 col-md-5 text-center">
                                        <div class="mb-3">
                                            <svg width="110" height="110" viewBox="0 0 120 120">
                                                <circle cx="60" cy="60" r="50" fill="#eaf7ef" />
                                                <circle cx="60" cy="60" r="45" fill="none" stroke="#28a745"
                                                    stroke-width="6" />
                                                <path d="M40 62 L55 75 L80 45" fill="none" stroke="#28a745"
                                                    stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <img src="assets/images/canntum.svg" style="max-width:180px;" class="img-fluid"
                                            alt="Canntum Logo">
                                    </div>

                                    <div class="col-lg-8 col-md-7">
                                        <div class="address-detail">
                                            <h5 class="address-title text-success fw-bold">
                                                Thank You! <span class="mx-1">Your Order is Confirmed</span>
                                            </h5>
                                            <p class="address-text mb-2">Your order <span><b><a
                                                            href="{{ route('user.order.details', $order->id) }}"
                                                            class="common">
                                                            &nbsp;{{ $order->payment_order_id }}&nbsp;
                                                        </a></b></span> has been Confirmed.</p>
                                            <p class="address-text">Your items will be delivered on or before <span
                                                    class="common"><b>1-5 days</b></span>. Any information will be
                                                redirected to the contact details you provided.</p>
                                            <h5 class="address-subtitle">Email</h5>
                                            <p class="address-text">{{ auth()->user()->email }}</p>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="address-subtitle">Phone</h5>
                                                    <p class="address-text mb-0">{{ auth()->user()->phone }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="address_box1">
                            <div class="row gx-3">
                                <div class="col-md-12">
                                    <h5 class="address_title">Shipping Address</h5>
                                    <address class="address_text">
                                        {{ $shipping_address->shipping_name }}<br>
                                        {{ $shipping_address->shipping_address }}<br>
                                        {{ $shipping_address->city }} - {{ $shipping_address->pincode }}<br>
                                        {{ $shipping_address->state }}<br>
                                        {{ $shipping_address->country }}<br>
                                        <br>
                                    </address>
                                    <p class="address_text">{{ $shipping_address->shipping_email ?? 'N/A' }}</p>
                                    <p class="address_text">{{ $shipping_address->shipping_phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="cart_list">
                            <div class="row gy-4">
                                @php $order_list = App\Models\OrderDetail::where('order_id', $order->id)->get(); @endphp
                                @foreach ($order_list as $key => $item)
                                    @php
                                        $product_img = App\Models\Product::where('id', $item->product_id)->first();
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="total_box">
                            <div class="payment_detail">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <h6 class="payment_title">Order ID</h6>
                                        <h6 class="payment_text">{{ $order->payment_order_id }}</h6>
                                    </div>
                                    <div class="col-1">
                                        <div class="vr" style="height: 30px;"></div>
                                    </div>
                                    <div class="col-5">
                                        <h6 class="payment_title text-end">Payment Method</h6>
                                        <h6 class="payment_text text-end" style="text-transform: uppercase;">
                                            {{ $order->payment_method }}
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            @php
                                $orderItems = App\Models\OrderDetail::where('order_id', $order->id)->get();

                                $subtotal = 0;
                                $calculated_gst = 0;
                                $totalGstRate = 0;
                                $itemCount = 0;

                                foreach ($orderItems as $item) {
                                    $price = (float) $item->offer_price;
                                    $qty = (int) $item->quantity;
                                    $gstPercent = (float) ($item->product_gst ?? 0);

                                    $lineTotal = $price * $qty;
                                    $subtotal += $lineTotal;

                                    $lineGst = ($lineTotal * $gstPercent) / 100;
                                    $calculated_gst += $lineGst;

                                    $totalGstRate += $gstPercent;
                                    $itemCount++;
                                }

                                // Average GST rate across items (for display label)
                                $avgGstRate = $itemCount > 0 ? round($totalGstRate / $itemCount) : 0;

                                // Fallback to stored GST if calculated is 0
                                $final_gst = $calculated_gst > 0 ? $calculated_gst : (float) ($order->gst ?? 0);

                                // If avgGstRate is still 0 but we have gst amount, derive from amounts
                                if ($avgGstRate == 0 && $subtotal > 0 && $final_gst > 0) {
                                    $avgGstRate = round(($final_gst / $subtotal) * 100);
                                }

                                $coupon = (float) ($order->coupon_discount ?? 0);
                                $shippingCharge = (float) ($order->shipping_charge ?? 0);

                                $finalTotal = $subtotal + $final_gst + $shippingCharge - $coupon;

                                $cgst = $final_gst / 2;
                                $sgst = $final_gst / 2;
                                $halfRate = $avgGstRate / 2;

                                $storeState = strtolower(trim('Tamil Nadu'));
                                $shippingState = strtolower(trim($shipping_address->state ?? ''));
                            @endphp

                            <h1 class="total_title">ORDER SUMMARY</h1>

                            <dl class="row mt-3 gy-1">

                                <dd class="col-6">
                                    <p>Subtotal</p>
                                </dd>
                                <dd class="col-6">
                                    <p class="text-end">₹ {{ number_format($subtotal, 2) }}</p>
                                </dd>

                                {{-- @if ($shippingState == $storeState)
                                  
                                    <dd class="col-6">
                                        <p>CGST @if ($halfRate > 0)
                                                ({{ $halfRate }}%)
                                            @endif
                                        </p>
                                    </dd>
                                    <dd class="col-6">
                                        <p class="text-end">₹ {{ number_format($cgst, 2) }}</p>
                                    </dd>

                                    <dd class="col-6">
                                        <p>SGST @if ($halfRate > 0)
                                                ({{ $halfRate }}%)
                                            @endif
                                        </p>
                                    </dd>
                                    <dd class="col-6">
                                        <p class="text-end">₹ {{ number_format($sgst, 2) }}</p>
                                    </dd>
                                @else
                                   
                                    <dd class="col-6">
                                        <p>IGST @if ($avgGstRate > 0)
                                                ({{ $avgGstRate }}%)
                                            @endif
                                        </p>
                                    </dd>
                                    <dd class="col-6">
                                        <p class="text-end">₹ {{ number_format($final_gst, 2) }}</p>
                                    </dd>
                                @endif --}}

                                <dd class="col-6">
                                    <p>Coupon Discount (-)</p>
                                </dd>
                                <dd class="col-6">
                                    <p class="text-end">₹ {{ number_format($coupon, 2) }}</p>
                                </dd>

                                <dd class="col-6">
                                    <p>Shipping</p>
                                </dd>
                                <dd class="col-6">
                                    <p class="text-end">
                                        @if ($shippingCharge > 0)
                                            ₹ {{ number_format($shippingCharge, 2) }}
                                        @else
                                            FREE
                                        @endif
                                    </p>
                                </dd>

                            </dl>

                            <div class="final_cost">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center gap-1">
                                            <h4 class="total_rate mb-0">Total</h4>
                                            <p class="mb-0 text-muted">(Incl of all taxes)</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="total_rate text-end">
                                            ₹ {{ number_format($finalTotal, 2) }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-4">
                                <a href="{{ route('home') }}" class="btn w-100 d-block common_btn">
                                    <i class="bi bi-arrow-left me-2"></i>Return Shopping
                                </a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        window.addEventListener('keydown', function(e) {
            if (e.keyCode === 116 || (e.ctrlKey || e.metaKey) && e.keyCode === 82) {
                e.preventDefault();
                alert('Page refresh is disabled. Please use the designated buttons.');
            }
        });

        // Flag: skip the leave-dialog when user clicks an intentional navigation link
        let allowLeave = false;

        document.querySelectorAll('a[href]').forEach(function(link) {
            link.addEventListener('click', function() {
                allowLeave = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (allowLeave) return; // user clicked a link — no dialog
            var message = 'Are you sure you want to leave? Any unsaved changes will be lost.';
            e.returnValue = message;
            return message;
        });
    </script>
@endsection

@extends('frontend.layouts.app')
@section('content')
    <style>
        .review-btn {
            border: 1px solid #272727 !important;
            background: #272727;
            font-size: 15px;
            font-weight: 400;
            color: #fff;
            border-radius: 30px;
            padding: 12px 20px;
        }


        .review-btn:hover {
            color: #212529 !important;
        }

        .review-submit-btn {
            font-size: 15px;
            font-weight: 500;
            color: #272727;
            /* border-radius: 30px !important; */
            border-color: #272727;
        }

        .review-submit-btn:hover {
            color: #001e40 !important;
        }

        .auto-resize {
            min-height: 200px !important;
            min-width: 500px !important;
            resize: none;
            /* optional: disable manual resize */
            overflow-y: scroll;
        }
    </style>

    <section class="order_detail">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="row gy-4">

                        {{-- Order Header --}}
                        <div class="col-lg-12">
                            <div class="order_box">
                                <div class="heading">
                                    <div class="row gy-4 align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="title">Order Details
                                                <span>{{ $orders->created_at->format('F j, Y') }}</span>
                                                <span>{{ $orderDetailsCount }} Products</span>
                                            </h5>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="text-end">
                                                <span class="order_status order_delivered">
                                                    @if ($orders->order_status == 0)
                                                        <span class="order-status in-order">Order Pending</span>
                                                    @elseif($orders->order_status == 1)
                                                        <span class="order-status in-order">Order Confirmed</span>
                                                    @elseif($orders->order_status == 2)
                                                        <span class="order-status in-order">Order Cancelled</span>
                                                    @elseif($orders->order_status == 3)
                                                        <span class="order-status in-order">Return Requested</span>
                                                    @elseif($orders->order_status == 4)
                                                        <span class="order-status in-order">Order Returned</span>
                                                    @else
                                                        <span class="order-status in-order">Order Waiting</span>
                                                    @endif
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="address_detail">
                                        <h5 class="address_title"><strong>Shipping Address</strong></h5>
                                        <h6 class="address_name">{{ $shippingAddress->shipping_name }}</h6>
                                        <p class="address_text">
                                            {{ $shippingAddress->shipping_address }}.
                                            {{ $shippingAddress->city ?? '' }},&nbsp;{{ $shippingAddress->state ?? '' }} -
                                            {{ $shippingAddress->pincode }}
                                        </p>
                                        <h5 class="address_subtitle">Email</h5>
                                        <p class="address_text">{{ $shippingAddress->shipping_email }}</p>
                                        <h5 class="address_subtitle">Phone</h5>
                                        <p class="address_text">{{ $shippingAddress->shipping_phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Progress --}}
                        <div class="col-lg-4">
                            <div class="detail_progress">
                                <div class="navigation_menu" id="navigation_menu">
                                    <ul class="navigation_tabs" id="navigation_tabs">
                                        <li
                                            class="{{ in_array($orders->shipping_status, [1, 2, 3, 4]) ? 'tab_inactive' : 'tab_active' }}">
                                            <a href="#">Order received</a>
                                        </li>
                                        <li
                                            class="{{ in_array($orders->shipping_status, [2, 3, 4]) ? 'tab_inactive' : 'tab_active' }}">
                                            <a href="#">Shipped</a>
                                        </li>
                                        <li
                                            class="{{ in_array($orders->shipping_status, [3, 4]) ? 'tab_inactive' : 'tab_active' }}">
                                            <a href="#">On the way</a>
                                        </li>
                                        <li class="{{ $orders->shipping_status == 4 ? 'tab_inactive' : 'tab_active' }}">
                                            <a href="#">Delivered</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Product List --}}
                        <div class="col-lg-8">
                            <div class="cart_list">
                                <div class="row gy-3">
                                    @foreach ($orders_details as $key => $item)
                                        @php
                                            $product_img = App\Models\Product::where('id', $item->product_id)->first();
                                        @endphp

                                        <div class="col-lg-12">
                                            <div class="cart_item">
                                                <div class="row gy-4">
                                                    <div class="col-lg-3 col-md-4">
                                                        <a href="{{ route('product.details.show', $product_img->id) }}">
                                                            <div class="img_box">
                                                                @php
                                                                    // ✅ Use selected_image first (stored at order time)
                                                                    if (!empty($item->selected_image)) {
                                                                        $detailImgUrl = url(
                                                                            'public/variant_images/' .
                                                                                $item->selected_image,
                                                                        );
                                                                    } elseif (
                                                                        !empty($item->image_index) ||
                                                                        $item->image_index === 0
                                                                    ) {
                                                                        // Fallback: get from product_detail variant images by index
                                                                        $pDetail = App\Models\ProductDetail::find(
                                                                            $item->product_detail_id,
                                                                        );
                                                                        $vImages =
                                                                            $pDetail && $pDetail->images
                                                                                ? json_decode($pDetail->images, true)
                                                                                : [];
                                                                        $idx = $item->image_index ?? 0;
                                                                        $detailImgUrl = !empty($vImages[$idx])
                                                                            ? url(
                                                                                'public/variant_images/' .
                                                                                    $vImages[$idx],
                                                                            )
                                                                            : url(
                                                                                'public/product_images/' .
                                                                                    ($product_img->product_img ?? ''),
                                                                            );
                                                                    } else {
                                                                        // Last resort: product main image
                                                                        $detailImgUrl = url(
                                                                            'public/product_images/' .
                                                                                ($product_img->product_img ?? ''),
                                                                        );
                                                                    }
                                                                @endphp

                                                                @if (!empty($product_img))
                                                                    <img src="{{ $detailImgUrl }}"
                                                                        alt="{{ $item->productname ?? 'Product Image' }}"
                                                                        style="width:100%; height:100%; object-fit:cover;">
                                                                @else
                                                                    <div class="no-image-placeholder">
                                                                        <p>No image available</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="col-lg-9 col-md-8">
                                                        <h5 class="product_title">
                                                            {{ $product_img->product_name ?? 'Unknown Product' }}
                                                        </h5>
                                                        <ul class="product_filter_list">
                                                            <li>Qty : <span>{{ $item->quantity ?? 0 }}</span></li>
                                                        </ul>
                                                        <h6 class="product_rate">
                                                            ₹ {{ round($item->offer_price ?? 0) }}
                                                            @if (!empty($product_img))
                                                                <span
                                                                    style="text-decoration: line-through; color: #888; margin-left: 5px;">
                                                                    ₹ {{ round($product_img->orginal_rate ?? 0) }}
                                                                </span>
                                                            @endif
                                                        </h6>
                                                    </div>

                                                    @if ($orders->shipping_status == 4)
                                                        @php
                                                            $review = !empty($item->product_id)
                                                                ? App\Models\Review::where(
                                                                    'product_id',
                                                                    $item->product_id,
                                                                )
                                                                    ->where('order_id', $item->order_id)
                                                                    ->where('user_id', $item->user_id)
                                                                    ->first()
                                                                : null;
                                                        @endphp
                                                        <div class="col-lg-9 col-md-8">
                                                            @if ($review)
                                                                <a class="btn review-submit-btn">Review submitted</a>
                                                            @else
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                                    data-order-id="{{ $item->id }}"
                                                                    class="btn review-btn write-review-btn">
                                                                    <i class="bi bi-pencil me-2"></i>Review
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Order Summary Box --}}
                <div class="col-lg-4">
                    <div class="total_box">
                        <div class="payment_detail">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h6 class="payment_title">Order Id</h6>
                                    <h6 class="payment_text">{{ $orders->payment_order_id }}</h6>
                                </div>
                                <div class="col-1">
                                    <div class="vr" style="height: 30px;"></div>
                                </div>
                                <div class="col-5">
                                    <h6 class="payment_title text-end">Payment Method</h6>
                                    <h6 class="payment_text text-end">
                                        {{ strtoupper($orders->payment_method ?? 'COD') }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <h1 class="total_title">ORDER SUMMARY</h1>

                        @php

                            $subtotal = 0;
                            $totalGst = 0;
                            $couponDiscount = $orders->coupon_discount ?? 0;

                            foreach ($orders_details as $detail) {
                                $itemSubtotal = $detail->offer_price * $detail->quantity;
                                $itemGst = $itemSubtotal * ($detail->product_gst / 100);
                                $subtotal += $itemSubtotal;
                                $totalGst += $itemGst;
                            }
                            $shipping_charge = (float) ($orders->shipping_charge ?? 0);
                            $grandTotal = $subtotal + $totalGst + $shipping_charge - $couponDiscount;

                            // Determine if same state (Tamil Nadu) for CGST/SGST or IGST
                            $shippingState = strtolower(trim($shippingAddress->state ?? ''));
                            $isSameState = $shippingState === 'tamil nadu';

                            // GST rate % (from first item — assumes same GST rate across items)
                            $gstRate = $orders_details->first()->product_gst ?? 0;
                            $halfGstRate = $gstRate / 2;
                        @endphp

                        <dl class="row mt-3 gy-1">
                            <dd class="col-6">
                                <p>Subtotal</p>
                            </dd>
                            <dd class="col-6">
                                <p class="text-end">₹ {{ number_format($subtotal, 2) }}</p>
                            </dd>

                            {{-- @if ($isSameState)
                                <dd class="col-6">
                                    <p>CGST ({{ $halfGstRate }}%)</p>
                                </dd>
                                <dd class="col-6">
                                    <p class="text-end">₹ {{ number_format($totalGst / 2, 2) }}</p>
                                </dd>

                                <dd class="col-6">
                                    <p>SGST ({{ $halfGstRate }}%)</p>
                                </dd>
                                <dd class="col-6">
                                    <p class="text-end">₹ {{ number_format($totalGst / 2, 2) }}</p>
                                </dd>
                            @else
                                <dd class="col-6">
                                    <p>IGST ({{ $gstRate }}%)</p>
                                </dd>
                                <dd class="col-6">
                                    <p class="text-end">₹ {{ number_format($totalGst, 2) }}</p>
                                </dd>
                            @endif --}}

                            <dd class="col-6">
                                <p>Coupon Discount (-)</p>
                            </dd>
                            <dd class="col-6">
                                <p class="text-end">₹ {{ number_format($couponDiscount, 2) }}</p>
                            </dd>

                            <dd class="col-6">
                                <p>Shipping</p>
                            </dd>
                            <dd class="col-6">
                                <p class="text-end">
                                    @if ($shipping_charge > 0)
                                        ₹ {{ number_format($shipping_charge, 2) }}
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
                                    {{-- ✅ FIXED: subtotal + GST - coupon --}}
                                    <h5 class="total_rate text-end">
                                        ₹ {{ number_format($grandTotal, 2) }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <p class="mt-4">
                            <a href="{{ route('home') }}" class="btn w-100 d-block common_btn">Return to Home</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
        .wrapper {
            margin: 0 auto;
            max-width: 960px;
            width: 100%;
        }

        .master {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding-top: 40px;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        h2 {
            line-height: 160%;
            margin-bottom: 20px;
            text-align: center;
        }

        .rating-component {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-bottom: 10px;
        }

        .rating-component .status-msg {
            margin-bottom: 10px;
            text-align: center;
        }

        .rating-component .status-msg strong {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .rating-component .stars-box {
            align-self: center;
            margin-bottom: 15px;
        }

        .rating-component .stars-box .star {
            color: #ccc;
            cursor: pointer;
        }

        .rating-component .stars-box .star.hover {
            color: #ff5a49;
        }

        .rating-component .stars-box .star.selected {
            color: #ff5a49;
        }

        .feedback-tags {
            min-height: 119px;
        }

        .feedback-tags .tags-container {
            display: none;
        }

        .feedback-tags .tags-container .question-tag {
            text-align: center;
            margin-bottom: 40px;
        }

        .feedback-tags .tags-box {
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .feedback-tags .tags-container .make-compliment {
            padding-bottom: 20px;
        }

        .feedback-tags .tags-container .make-compliment .compliment-container {
            align-items: center;
            color: #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .feedback-tags .tags-container .make-compliment .compliment-container .fa-smile-wink {
            color: #ff5a49;
            cursor: pointer;
            font-size: 40px;
            margin-top: 15px;
            animation-name: compliment;
            animation-duration: 2s;
            animation-iteration-count: 1;
        }

        .feedback-tags .tags-container .make-compliment .compliment-container .list-of-compliment {
            display: none;
            margin-top: 15px;
        }

        .feedback-tags .tag {
            border: 1px solid #ff5a49;
            border-radius: 5px;
            color: #ff5a49;
            cursor: pointer;
            margin-bottom: 10px;
            margin-left: 10px;
            padding: 10px;
        }

        .feedback-tags .tag.choosed {
            background-color: #ff5a49;
            color: #fff;
        }

        .list-of-compliment ul {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }

        .list-of-compliment ul li {
            align-items: center;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-bottom: 10px;
            margin-left: 20px;
            min-width: 90px;
        }

        .list-of-compliment ul li:first-child {
            margin-left: 0;
        }

        .list-of-compliment ul li .icon-compliment {
            align-items: center;
            border: 2px solid #ff5a49;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 70px;
            margin-bottom: 15px;
            overflow: hidden;
            padding: 0 10px;
            transition: 0.5s;
            width: 70px;
        }

        .list-of-compliment ul li .icon-compliment i {
            color: #ff5a49;
            font-size: 30px;
            transition: 0.5s;
        }

        .list-of-compliment ul li.actived .icon-compliment {
            background-color: #ff5a49;
            transition: 0.5s;
        }

        .list-of-compliment ul li.actived .icon-compliment i {
            color: #fff;
            transition: 0.5s;
        }

        .button-box .done {
            background-color: #ff5a49;
            border: 1px solid #ff5a49;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
            display: none;
            min-width: 100px;
            padding: 10px;
        }

        .button-box .done:disabled,
        .button-box .done[disabled] {
            border: 1px solid #ff9b95;
            background-color: #ff9b95;
            color: #fff;
            cursor: initial;
        }

        .submited-box {
            display: none;
            padding: 20px;
        }

        .submited-box .loader,
        .submited-box .success-message {
            display: none;
        }

        .submited-box .loader {
            border: 5px solid transparent;
            border-top: 5px solid #4dc7b7;
            border-bottom: 5px solid #ff5a49;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes compliment {
            1% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-30deg);
            }

            50% {
                transform: rotate(30deg);
            }

            75% {
                transform: rotate(-30deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    {{-- Review Modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Write a Review</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="review-form" action="{{ route('add.review') }}" method="post">
                        @csrf
                        <input type="hidden" name="order_id" id="order-id" value="">
                        <div class="wrapper">
                            <div class="master">
                                <h1>Review And rating</h1>
                                <h2>How was your experience about our product?</h2>
                                <div class="rating-component">
                                    <div class="status-msg">
                                        <label><input class="rating_msg" type="hidden" name="rating_msg"
                                                value="" /></label>
                                    </div>
                                    <div class="stars-box">
                                        <i class="star fa fa-star" title="1 star" data-message="Poor"
                                            data-value="1"></i>
                                        <i class="star fa fa-star" title="2 stars" data-message="Too bad"
                                            data-value="2"></i>
                                        <i class="star fa fa-star" title="3 stars" data-message="Average quality"
                                            data-value="3"></i>
                                        <i class="star fa fa-star" title="4 stars" data-message="Nice"
                                            data-value="4"></i>
                                        <i class="star fa fa-star" title="5 stars" data-message="very good quality"
                                            data-value="5"></i>
                                    </div>
                                    <div class="starrate">
                                        <label><input class="ratevalue" type="hidden" name="rate_value"
                                                value="" /></label>
                                    </div>
                                </div>
                                <div class="feedback-tags">
                                    <div class="tags-container" data-tag-set="1">
                                        <div class="question-tag">Why was your experience so bad?</div>
                                    </div>
                                    <div class="tags-container" data-tag-set="2">
                                        <div class="question-tag">Why was your experience so bad?</div>
                                    </div>
                                    <div class="tags-container" data-tag-set="3">
                                        <div class="question-tag">Why was your average rating experience?</div>
                                    </div>
                                    <div class="tags-container" data-tag-set="4">
                                        <div class="question-tag">Why was your experience good?</div>
                                    </div>
                                    <div class="tags-container" data-tag-set="5">
                                        <div class="make-compliment">
                                            <div class="compliment-container">
                                                Give a compliment
                                                <i class="far fa-smile-wink"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tags-box">
                                        <textarea class="tag form-control auto-resize" name="comment" placeholder="Please enter your review"></textarea>
                                    </div>
                                </div>
                                <div class="button-box">
                                    <input type="submit" class="done btn btn-warning" disabled="disabled"
                                        value="Add review" />
                                </div>
                                <div class="submited-box">
                                    <div class="loader"></div>
                                    <div class="success-message">Thank you!</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var reviewButtons = document.querySelectorAll('.write-review-btn');
        var orderIdInput = document.getElementById('order-id');

        reviewButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                orderIdInput.value = this.getAttribute('data-order-id');
            });
        });

        $(".rating-component .star").on("mouseover", function() {
            var onStar = parseInt($(this).data("value"), 10);
            $(this).parent().children("i.star").each(function(e) {
                e < onStar ? $(this).addClass("hover") : $(this).removeClass("hover");
            });
        }).on("mouseout", function() {
            $(this).parent().children("i.star").each(function() {
                $(this).removeClass("hover");
            });
        });

        $(".rating-component .stars-box .star").on("click", function() {
            var onStar = parseInt($(this).data("value"), 10);
            var stars = $(this).parent().children("i.star");
            var ratingMessage = $(this).data("message");

            $('.rating-component .starrate .ratevalue').val(onStar);
            $(".fa-smile-wink").show();
            $(".button-box .done").show().removeAttr("disabled");

            stars.removeClass("selected");
            for (var i = 0; i < onStar; i++) {
                $(stars[i]).addClass("selected");
            }

            $(".status-msg .rating_msg").val(ratingMessage);
            $(".status-msg").html(ratingMessage);
            $("[data-tag-set]").hide();
            $("[data-tag-set=" + onStar + "]").show();
        });

        $(".feedback-tags .tag").on("click", function() {
            $(this).toggleClass("choosed");
        });

        $(".compliment-container .fa-smile-wink").on("click", function() {
            $(this).fadeOut("slow", function() {
                $(".list-of-compliment").fadeIn();
            });
        });

        $(".done").on("click", function() {
            $(".rating-component").hide();
            $(".feedback-tags").hide();
            $(".button-box").hide();
            $(".submited-box").show();
            $(".submited-box .loader").show();
            setTimeout(function() {
                $(".submited-box .loader").hide();
                $(".submited-box .success-message").show();
            }, 1500);
        });
    });
</script>

@extends('frontend.layouts.app')
@section('content')

    <section class="inner_pages">
        <style>
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }

            .item-count-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                /* min-width: 22px; */
                height: 22px;
                margin-left: 4px;
                border-radius: 50%;
                background-color: #fff;
                color: #001e40;
                font-size: 12px;
                font-weight: 600;
                line-height: 1;
            }
        </style>

        <div class="container">
            @if (empty(session('cart')))
                <section class="cart_detail">
                    <div class="text-center">
                        <div class="mb-4" style="color: #d0d0d0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                        </div>
                        <h4 style="font-weight: 500; color: #444;">Your cart is empty</h4>
                        <p style="color: #aaa; font-size: 15px;">You haven't added any products to your cart yet.</p>
                        <a href="{{ route('home') }}" class="btn mt-2"
                            style="border: 1px solid #333; padding: 10px 28px; font-size: 15px; color: #333; border-radius: 2px;">
                            Continue Shopping
                        </a>
                    </div>
                </section>
            @else
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cart</li>
                    </ol>
                </nav>

                <h2 class="page_title">Cart Details</h2>

                <div class="cart_detail">
                    <div class="row gy-4">

                        {{-- LEFT SIDE - CART ITEMS --}}
                        <div class="col-lg-8">
                            <div class="cart_list">
                                <div class="row gy-4">

                                    @php
                                        $total = 0;
                                        $totalItems = 0;
                                    @endphp

                                    @foreach ($cart as $id => $details)
                                        @php
                                            $productDetail = $productDetails[$id];
                                            $product = $productDetail->product;
                                            $totalItems += $details['quantity'];

                                            $discounted = round($details['offer_price']);

                                            $lineTotal = $discounted * $details['quantity'];
                                            $total += $lineTotal;
                                        @endphp

                                        <div class="col-lg-12" id="row_{{ $id }}">
                                            <div class="cart_item">


                                                <div class="close_btn mb-4">
                                                    <a href="javascript:void(0)" class="remove-item"
                                                        data-id="{{ $id }}">
                                                        <i class="bi bi-x-lg"></i>
                                                    </a>
                                                </div>

                                                <div class="row gy-4">

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="img_box">

                                                            @php
                                                                $displayImages = [];

                                                                // Variant images
                                                                if ($productDetail && $productDetail->images) {
                                                                    $variantImages = json_decode(
                                                                        $productDetail->images,
                                                                        true,
                                                                    );
                                                                    if (
                                                                        !empty($variantImages) &&
                                                                        is_array($variantImages)
                                                                    ) {
                                                                        $displayImages = $variantImages;
                                                                    }
                                                                }

                                                                // fallback product image
                                                                if (empty($displayImages)) {
                                                                    $displayImages[] = $details['product_img'];
                                                                }
                                                            @endphp

                                                            {{-- MAIN IMAGE --}}
                                                            <img id="main_img_{{ $id }}"
                                                                src="{{ url('public/variant_images/' . $details['product_img']) }}"
                                                                style="width:100%; margin-bottom:10px;">

                                                            {{-- THUMBNAILS --}}
                                                            {{-- <div class="d-flex gap-2 flex-wrap">

                                                                @foreach ($displayImages as $img)
                                                                    <img src="{{ url('public/product_images/' . $img) }}"
                                                                        class="variant-thumb" data-id="{{ $id }}"
                                                                        data-img="{{ url('public/product_images/' . $img) }}"
                                                                        style="width:45px;height:45px;cursor:pointer;border:1px solid #ddd;padding:2px;">
                                                                @endforeach

                                                            </div> --}}

                                                        </div>
                                                    </div>
                                                    <script>
                                                        $(document).ready(function() {

                                                            $('.variant-thumb').click(function() {

                                                                var img = $(this).data('img');
                                                                var id = $(this).data('id');

                                                                $('#main_img_' + id).attr('src', img);

                                                            });

                                                        });
                                                    </script>

                                                    {{-- PRODUCT DETAILS --}}
                                                    <div class="col-lg-9 col-md-8">

                                                        <h5 class="product_title">{{ $product->product_name }}</h5>

                                                        {{-- ACCORDION --}}
                                                        <div class="accordion mt-3" id="descAccordion{{ $id }}">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseDesc{{ $id }}">
                                                                        Product Detail
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseDesc{{ $id }}"
                                                                    class="accordion-collapse collapse">
                                                                    <div class="accordion-body">
                                                                        {!! $product->description !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- QUANTITY --}}
                                                        <div class="d-flex align-items-center mt-3">
                                                            <div class="input-group" style="width:130px;">
                                                                <button
                                                                    class="btn btn-outline-secondary btn-sm decrease-qty">−</button>

                                                                <input type="number"
                                                                    class="form-control text-center quantity"
                                                                    id="quantity_{{ $id }}"
                                                                    value="{{ $details['quantity'] }}" min="1"
                                                                    max="{{ $productDetail->quantity }}"
                                                                    style="background: antiquewhite;">

                                                                <button
                                                                    class="btn btn-outline-secondary btn-sm increase-qty">+</button>
                                                            </div>

                                                            <h6 class="product_rate ms-3 mb-0"
                                                                id="subtotal_{{ $id }}">
                                                                ₹ {{ $lineTotal }}
                                                            </h6>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>{{-- END LEFT 8 COL --}}

                        {{-- RIGHT SIDE - SUMMARY --}}
                        <div class="col-lg-4">
                            <div class="total_box">
                                <h1 class="total_title">ORDER SUMMARY</h1>
                                <dl class="row mt-3 gy-1">
                                    <dd class="col-6">
                                        <p>Subtotal</p>
                                    </dd>
                                    <dd class="col-6">
                                        <p class="text-end">₹ {{ round($total) }}</p>
                                    </dd>

                                    <dd class="col-6">
                                        <p>Shipping</p>
                                    </dd>
                                    <dd class="col-6">
                                        <p class="text-end">FREE</p>
                                    </dd>
                                </dl>

                                <div class="final_cost">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="total_rate">Order Total</h4>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="total_rate text-end">₹ {{ round($total) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        Total Items:
                                        <span class="item-count-badge mt-3">{{ $totalItems }}</span>
                                    </span>

                                    <p class="text-end mt-3 mb-0">Incl of all taxes</p>
                                </div>


                                @if (Auth::check())
                                    <p class="mt-4"><a href="{{ route('product.proceed_to_checkout') }}"
                                            class="btn w-100 d-block common_btn">Checkout</a></p>
                                @else
                                    <p class="mt-4"><a href="{{ route('user.login') }}"
                                            class="btn w-100 d-block common_btn">Checkout</a></p>
                                @endif

                            </div>

                            {{-- Shipping Info --}}
                            <div class="product-shipping-box mt-4">
                                <div class="d-flex gap-3">
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

                        </div>{{-- END RIGHT SUMMARY --}}

                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- REMOVE --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.variant-thumb').click(function() {

                var img = $(this).data('img');
                var id = $(this).data('id');

                $('#main_img_' + id).attr('src', img);

            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('.remove-item').on('click', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                $.ajax({
                    url: '{{ url('remove-from-cart') }}',
                    method: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#row_' + id).remove();
                            $('#total').text('₹ ' + response.total);
                            window.location.reload();
                        }
                    }
                });
            });
        });
    </script>

    {{-- QUANTITY --}}
    <script>
        $(document).ready(function() {

            $(document).on('click', '.increase-qty', function() {
                var input = $(this).siblings('.quantity');
                var currentVal = parseInt(input.val());
                var max = parseInt(input.attr('max')) || 999;

                if (currentVal < max) {
                    input.val(currentVal + 1).trigger('change');
                }
            });

            $(document).on('click', '.decrease-qty', function() {
                var input = $(this).siblings('.quantity');
                var currentVal = parseInt(input.val());
                var min = parseInt(input.attr('min')) || 1;

                if (currentVal > min) {
                    input.val(currentVal - 1).trigger('change');
                }
            });

            $(document).on('change', '.quantity', function() {
                var id = $(this).attr('id').replace('quantity_', '');
                var quantity = $(this).val();

                $.ajax({
                    url: '{{ url('update-cart') }}',
                    method: 'POST',
                    data: {
                        id: id,
                        quantity: quantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // $('#subtotal_' + id).text('₹ ' + parseFloat(response.subtotal)
                            //     .toFixed(2));
                            // $('#total').text('₹ ' + parseFloat(response.total).toFixed(2));
                            location.reload();
                        } else {
                            location.reload();
                        }
                    }
                });
            });
        });
    </script>

    <script>
        function validateQuantity(input) {
            let maxQuantity = input.getAttribute('max');
            let currentQuantity = input.value;

            if (parseInt(currentQuantity) > parseInt(maxQuantity)) {
                alert('The quantity entered exceeds the available stock.');
                input.value = '1';

                setTimeout(() => {
                    location.reload();
                }, 5000);
            }
        }
    </script>

@endsection

@extends('frontend.layouts.app')
@section('content')
    <style>
        .amount_strike {
            text-decoration: line-through !important;
            font-size: 14px;
            color: #6c757d;
            margin-left: 5px;
        }

        .offer_text {
            background: #f7ece6;
            padding: 1px 3px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        .empty-products {
            text-align: center;
        }

        .empty-products i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 16px;
            display: block;
        }

        .empty-products h4 {
            color: #555;
            margin-bottom: 8px;
        }

        .empty-products p {
            color: #999;
            margin-bottom: 24px;
        }

        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card_body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex: 1;
            min-height: 95px;
        }

        /* Fix price + button row — never wrap */
        .card_body .row.align-items-center {
            flex-wrap: nowrap !important;
            align-items: center !important;
        }

        /* Price section — shrink if needed but don't overflow */
        .card_body .col-7 {
            flex: 1 1 auto;
            min-width: 0;
            overflow: hidden;
        }

        /* Button section — never shrink */
        .card_body .col-5 {
            flex: 0 0 auto;
            width: 105px;
        }

        /* Keep height same even when no discount badge */
        .card_text {
            min-height: 28px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Price text */
        .card_text .amount_strike {
            font-size: 13px;
        }

        .offer_text {
            white-space: nowrap;
            font-size: 11px;
        }

        /* Button never wraps */
        .shop-btn {
            white-space: nowrap;
            font-size: 12px;
            padding: 5px 10px;
        }
    </style>

    <section class="product_list">

        @php
            $catname = App\Models\ProductCategory::where('id', $cat_id)->first();
        @endphp

        <div class="container">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ $catname->name }}
                    </li>
                </ol>
            </nav>

            <!-- Title -->
            <div class="filter_box">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="product_title">{{ $catname->name }}</h1>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="list_product mt-5">
                <div style="padding: 0 5px" class="row gy-4">

                    @if ($product && count($product) > 0)
                        @foreach ($product as $item)
                            <div class="col-lg-3 col-md-4 col-sm-6 d-flex justify-content-center">

                                <div class="v-card-outer">

                                    <!-- IMAGE -->
                                    <div class="v-card-media-frame">

                                        <!-- Wishlist -->
                                        <button class="v-card-wishlist-btn add-wishlist-btn"
                                            data-product-id="{{ $item->id }}" id="add-wishlist-btn-{{ $item->id }}">
                                            <img src="https://img.icons8.com/material-outlined/24/000000/like--v1.png"
                                                class="v-card-wishlist-icon">
                                        </button>

                                        <a id="adding-wishlist-{{ $item->id }}" class="v-card-wishlist-btn added-msg"
                                            style="display:none;">
                                            ❤️
                                        </a>

                                        <!-- Badge -->
                                        @if ($item->new_arrival == 1)
                                            <div class="v-card-badge-layer">
                                                <img src="{{ url('') }}/assets/images/cart-badge.svg"
                                                    class="v-card-badge-icon">
                                                <p class="v-card-badge-text">New Arrival</p>
                                            </div>
                                        @endif

                                        <!-- Product Image -->
                                        <a href="{{ route('product.details.show', $item->id) }}">
                                            <img src="{{ url('public/product_images/' . $item->product_img) }}"
                                                class="v-card-main-img">
                                        </a>

                                    </div>

                                    <!-- INFO -->
                                    <div class="v-card-info-block">

                                        <h5 class="v-card-headline text-truncate">
                                            <a href="{{ route('product.details.show', $item->id) }}">
                                                {{ $item->product_name }}
                                            </a>
                                        </h5>

                                        @php
                                            $product_price = App\Models\ProductDetail::where(
                                                'product_id',
                                                $item->id,
                                            )->first();
                                            $price = $product_price
                                                ? $product_price->price -
                                                    ($product_price->price * $item->discount) / 100
                                                : 0;
                                        @endphp

                                        <div class="v-card-action-row">

                                            <!-- PRICE -->
                                            <div class="v-card-val-stack">
                                                <span class="v-card-active-price">
                                                    Rs. {{ round($price) }}
                                                </span>

                                                @if (isset($product_price->price) && $item->discount)
                                                    <span class="v-card-was-price">
                                                        {{ $product_price->price }}
                                                    </span>
                                                @endif

                                                @if ($item->discount)
                                                    <span class="v-card-pct-tag">
                                                        {{ round($item->discount) }}% Off
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- BUTTON -->
                                            @if ($product_price && $product_price->quantity != 0)
                                                <a href="{{ route('product.details.show', $item->id) }}"
                                                    class="btn v-card-buy-btn add-to-cart-button"
                                                    data-product-id="{{ $item->id }}"
                                                    id="add-cart-btnn-{{ $item->id }}">
                                                    Add to cart
                                                </a>
                                            @else
                                                <span class="v-card-buy-btn" style="opacity:.6;">
                                                    Out of stock
                                                </span>
                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>
                        @endforeach
                    @else
                        <!-- EMPTY -->
                        <div class="col-12 text-center">
                            <h4>Launching soon..!</h4>
                            <p>No products available</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            // Restore cart & wishlist state from session
            @if (session('cart'))
                @foreach (session('cart') as $id => $details)
                    $('#adding-cart-{{ $id }}').show();
                    $('#add-cart-btnn-{{ $id }}').hide();
                @endforeach
            @endif

            @if (session('wishlist'))
                @foreach (session('wishlist') as $id => $details)
                    $('#adding-wishlist-{{ $id }}').show();
                    $('#add-wishlist-btn-{{ $id }}').hide();
                @endforeach
            @endif

            // Add to Cart — open modal
            $('.add-to-cart-button').on('click', function() {
                var productId = $(this).data('product-id');
                $.ajax({
                    url: '{{ url('get-product-details') }}',
                    method: 'GET',
                    data: {
                        id: productId
                    },
                    success: function(response) {
                        $('#productDetailsBody').html(response);
                        $('#productDetailsModal').modal('show');
                    }
                });
            });

            // Buy Now
            $('.add-to-cart-button-buy-product').on('click', function() {
                var productId = $(this).data('product-id');
                $.ajax({
                    type: 'GET',
                    url: '{{ url('add-to-cart-buy-product') }}/' + productId,
                    success: function() {
                        window.location.href = '{{ url('/cart') }}';
                    },
                    error: function(error) {
                        console.error('Error adding to cart:', error);
                    }
                });
            });

            // Add to Wishlist
            $('.add-to-wishlist-button').on('click', function() {
                var productId = $(this).data('product-id');
                $.ajax({
                    type: 'GET',
                    url: '{{ url('add-to-wishlist') }}/' + productId,
                    success: function() {
                        $("#adding-wishlist-" + productId).show();
                        $("#add-wishlist-btn-" + productId).hide();
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error adding to wishlist:', error);
                    }
                });
            });

        });
    </script>

@endsection

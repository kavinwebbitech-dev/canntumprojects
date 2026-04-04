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
        @php $catname = App\Models\ProductCategory::where('id', $cat_id)->first(); @endphp
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $catname->name }}</li>
                </ol>
            </nav>

            <div class="filter_box">
                <form id="sort-form" action="" method="GET">
                    <div class="row gy-4 align-items-center">
                        <div class="col-lg-6">
                            <h1 class="product_title">{{ $catname->name }}</h1>
                        </div>
                        <div class="col-lg-6">
                            <div class="row gx-2 gy-3">
                                <div class="col-lg-4 col-md-4 col-6"></div>
                                <div class="col-lg-4 col-md-4 col-6"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="list_product">
                <div class="mt-3">
                    <div class="product_collections">
                        <div class="row gy-4">

                            @if ($product && count($product) > 0)
                                @foreach ($product as $key => $item)
                                    <div class="col-lg-3 col-md-4">
                                        <div class="card">
                                            <div class="card_img">
                                                <a href="{{ route('product.details.show', $item->id) }}">
                                                    <img src="{{ url('public/product_images/' . $item->product_img) }}"
                                                        alt="">
                                                </a>

                                                @if ($item->new_arrival == 1)
                                                    <div class="trending_bg">
                                                        <img src="{{ url('') }}/assets/images/cart-badge.svg"
                                                            alt="">New Arrival
                                                    </div>
                                                @endif

                                                <a href="javascript:void(0);" data-product-id="{{ $item->id }}"
                                                    id="add-wishlist-btn-{{ $item->id }}"
                                                    class="whislist_icon add-wishlist-btn add-to-wishlist-button">
                                                    <i class="bi bi-heart"></i>
                                                </a>

                                                <a id="adding-wishlist-{{ $item->id }}" class="whislist_icon added-msg"
                                                    style="display: none">
                                                    <i class="bi bi-heart-fill"></i>
                                                </a>
                                            </div>

                                            <div class="card_body">
                                                <h5 class="card_title text-truncate">
                                                    <a href="{{ route('product.details.show', $item->id) }}">
                                                        {{ $item->product_name }}
                                                    </a>
                                                </h5>
                                                <div class="row align-items-center">
                                                    <div class="col-7">
                                                        @php
                                                            $product_price = App\Models\ProductDetail::where(
                                                                'product_id',
                                                                $item->id,
                                                            )
                                                                ->limit(1)
                                                                ->get()
                                                                ->first();
                                                            $price = $product_price
                                                                ? $product_price->price -
                                                                    $product_price->price * ($item->discount / 100)
                                                                : 0;
                                                        @endphp

                                                        <p class="card_text">
                                                            Rs. {{ round($price) ?? '' }}
                                                            @if (isset($product_price->price) && $item->discount)
                                                                <span
                                                                    class="amount_strike">{{ $product_price->price }}
                                                                </span>
                                                            @endif
                                                            &nbsp;
                                                            @if ($item->discount)
                                                                <span class="offer_text">
                                                                    {{ round($item->discount) }} % Off
                                                                </span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-5">
                                                        <p class="text-end">
                                                            @if ($product_price && $product_price->quantity != 0 && $product_price->quantity != '')
                                                                <a href="{{ route('product.details.show', $item->id) }}"
                                                                    data-product-id="{{ $item->id }}"
                                                                    id="add-cart-btnn-{{ $item->id }}"
                                                                    class="btn shop-btn add-cart-btn add-to-cart-button">
                                                                    Add to Cart
                                                                </a>
                                                                <a href="javascript:void(0);"
                                                                    data-product-id="{{ $item->id }}"
                                                                    id="adding-cart-{{ $item->id }}"
                                                                    style="display: none" class="btn shop-btn">
                                                                    <i class="fas fa-shopping-cart"></i> Added
                                                                </a>
                                                            @else
                                                                <div class="out-of-stock text-center">
                                                                    <a class="cart-btn"
                                                                        style="background: white; color: #272727;border: 1px solid #272727; font-size:12px !important; padding:7px 6px">Out of
                                                                        stock</a>
                                                                </div>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                {{-- Empty Products State --}}
                                <div class="col-12">
                                    <div class="empty-products">
                                        <i class="bi bi-bag-x"></i>
                                        <h4>Launching soon..!</h4>
                                        <p>There are no products available in this category yet.</p>
                                        <a href="{{ route('home') }}" class="btn"
                                            style="border: 1px solid #001E40; color: #001E40 !important;">Continue
                                            Shopping</a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="productDetailsModal" tabindex="-1" role="dialog"
            aria-labelledby="productDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                        <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="productDetailsBody">
                        <!-- Product details will be populated here -->
                    </div>
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

@extends('frontend.layouts.app')
@section('content')

    <style>
        .add-cart-btn {
            border: 1px;
            padding: 6px 14px;
            font-size: 13px;
        }

        .remove-top-btn {
            position: absolute;
            top: 1px;
            right: 2px;
            width: 28px;
            height: 28px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
            z-index: 10;
            text-decoration: none;
        }

        .remove-top-btn:hover {
            background: #f71517;
            color: #fff;
        }

        .scroll-desc {
            max-height: 320px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .scroll-desc::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-desc::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 10px;
        }

        .scroll-desc::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .offer_text {
            background: #f7ece6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        /* Fixed image container — uniform height, image fills cleanly */
        .wishlist-img-wrap {
            width: 100%;
            height: 300px;
            overflow: hidden;
            border-radius: 8px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        /* Card should NOT stretch to sibling height */
        .wishlist-card {
            height: auto !important;
        }

        .empty-wishlist {
            text-align: center;
        }

        .empty-wishlist i {
            font-size: 64px;
            color: #ddd;
            /* margin-bottom: 1rem; */
            display: block;
        }

        .butto {
            border: 1px solid #333;
            padding: 10px 28px;
            font-size: 15px;
            color: #333;
            border-radius: 2px;
        }
        

        .empty-wishlist h4 {
            color: #555;
            margin-bottom: 8px;
        }

        .empty-wishlist p {
            color: #999;
            margin-bottom: 24px;
        }
    </style>

    <section class="inner_pages">
        @if (session('wishlist') && count(session('wishlist')) > 0)
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                    </ol>
                </nav>

                <h2 class="page_title">Wishlist</h2>

                <div class="wishlist_product">
                    <div class="mt-4">
                        <div class="product_collections">
                            <div class="row gy-4">
                                @foreach (session('wishlist') as $id => $details)
                                    @php
                                        $product = App\Models\Product::find($id);
                                        $product_details = App\Models\ProductDetail::where('product_id', $id)->first();
                                    @endphp

                                    <div class="col-lg-6 col-md-12">
                                        {{-- Remove h-100 so cards don't stretch to match tallest sibling --}}
                                        <div class="card wishlist-card pt-3 position-relative">
                                            <div class="card-body">
                                                <a href="#" class="remove-item-wishlist remove-top-btn"
                                                    data-id="{{ $id }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </a>
                                                <div class="row">
                                                    <div class="col-md-5">

                                                        {{-- Fixed-height image wrapper --}}
                                                        <div class="wishlist-img-wrap mb-2">
                                                            <a href="{{ route('product.details.show', $id) }}">
                                                                <img src="{{ url('public/product_images/' . $details['product_img']) }}"
                                                                    alt="{{ $details['product_name'] }}">
                                                            </a>
                                                        </div>

                                                        <h6 class="text-center mb-2">
                                                            <a href="{{ route('product.details.show', $id) }}">
                                                                {{ $details['product_name'] }}
                                                            </a>
                                                        </h6>

                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <p class="mb-0">
                                                                Rs. {{ $product_details->price }}
                                                                @if ($product->discount)
                                                                    <span
                                                                        class="offer_text ms-1">{{ round($product->discount) }}%</span>
                                                                @endif
                                                            </p>
                                                            <a href="{{ route('product.details.show', $product->id) }}"
                                                                class="shop-btn btn-sm add-cart-btn border">
                                                                Add to cart
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-7">
                                                        <div class="accordion" id="accordion{{ $id }}">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#desc{{ $id }}">
                                                                        Product Detail
                                                                    </button>
                                                                </h2>
                                                                <div id="desc{{ $id }}"
                                                                    class="accordion-collapse collapse">
                                                                    <div class="accordion-body scroll-desc text-sm">
                                                                        {!! $product->description !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="container">
                <div class="empty-wishlist">
                    <i class="bi bi-heart"></i>
                    <h4 style="font-weight: 500; color: #444;">Your wishlist is empty</h4>
                    <p>You haven't added any products to your wishlist yet.</p>
                    <a href="{{ route('home') }}" class="btn butto"
                        style="border: 1px solid #001E40;  color: #001E40 !important;">Continue Shopping</a>
                </div>
            </div>
        @endif
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.move-to-cart').click(function(e) {
                e.preventDefault();
                var productId = $(this).data('id');
                $.ajax({
                    url: '{{ route('wishlist.moveToCart') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            $('.remove-item-wishlist').click(function(e) {
                e.preventDefault();
                var productId = $(this).data('id');
                $.ajax({
                    url: '{{ route('wishlist.remove') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>

@endsection

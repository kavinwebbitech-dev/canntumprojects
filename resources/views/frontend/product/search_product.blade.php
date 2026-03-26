@extends('frontend.layouts.app')
@section('content')
    <style>
        .green-btn {
            border: 1px solid #008000 !important;
            background: #008000;
            font-size: 15px;
            font-weight: 400;
            color: #fff;
            border-radius: 25px;
            padding: 12px 25px;
        }

        .green-btn:focus,
        .green-btn:hover {
            background: #008000 !important;
            color: #fff !important;
        }

        .green-btn i {
            margin-left: 5px;
        }
    </style>



    <section class="product_list">
        <div class="container">
            <div class="list_product">
                <div class="mt-5">
                    <div class="product_collections ">
                        <div class="row gy-4">

                            @forelse ($product as $key => $item)
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
                                                        alt="">
                                                    New Arrival
                                                </div>
                                            @endif

                                            <!-- Wishlist -->
                                            <a href="javascript:void(0);"
                                                style="display:flex;align-items:center;justify-content:center;"
                                                data-product-id="{{ $item->id }}"
                                                id="add-wishlist-btn-{{ $item->id }}"
                                                class="whislist_icon add-wishlist-btn add-to-wishlist-button">
                                                <i class="bi bi-heart"></i>
                                            </a>

                                            <a id="adding-wishlist-{{ $item->id }}"
                                                data-product-id="{{ $item->id }}" style="display:none"
                                                class="whislist_icon remove-to-wishlist-button">
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
                                                <div class="col-6">
                                                    @php
                                                        $product_price = App\Models\ProductDetail::where(
                                                            'product_id',
                                                            $item->id,
                                                        )->first();
                                                        $price = $product_price
                                                            ? $product_price->price -
                                                                $product_price->price * ($item->discount / 100)
                                                            : 0;
                                                    @endphp

                                                    <p class="card_text">
                                                        Rs. {{ round($price) }}
                                                        @if ($item->discount)
                                                            <span class="offer_text">
                                                                {{ $item->discount }} %
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="col-6 text-end">
                                                    @if ($product_price && $product_price->quantity > 0)
                                                        <a href="{{ route('product.details.show', $item->id) }}"
                                                            class="btn shop-btn add-cart-btn">
                                                            Add to Cart
                                                        </a>
                                                    @else
                                                        <a class="btn cart-btn" style="background:white;color:#b33425;">
                                                            Out of stock
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @empty

                                <!-- No Products Found Section -->
                                <div class="col-12 text-center py-2">
                                    <h5 class="fw-bold">No Products Found</h5>

                                    <a href="{{ route('home') }}" class="btn btn-dark mt-3">
                                        Continue Shopping
                                    </a>
                                </div>
                            @endforelse

                        </div>

                    </div>
                    <!--<nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mt-5">
                                <li class="page-item">
                                    <a class="page-link prev"><i class="bi bi-chevron-left"></i></a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link next" href="#"><i class="bi bi-chevron-right"></i></a>
                                </li>
                            </ul>
                        </nav>-->
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
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
        });
    </script>

    <script>
        $(document).ready(function() {
            // Event listener for Add to Cart button
            $('.add-to-cart-button').on('click', function() {
                var productId = $(this).data('product-id');

                // Fetch product details using AJAX
                $.ajax({
                    url: '{{ url('get-product-details') }}',
                    method: 'GET',
                    data: {
                        id: productId
                    },
                    success: function(response) {
                        // Populate modal with product details
                        $('#productDetailsBody').html(response);
                        // Open the modal
                        $('#productDetailsModal').modal('show');
                    }
                });
            });
        });


        $(document).ready(function() {
            $('.add-to-cart-button-buy-product').on('click', function() {
                var productId = $(this).data('product-id');
                $.ajax({
                    type: 'GET',
                    url: '{{ url('add-to-cart-buy-product') }}/' + productId,
                    success: function(data) {
                        /*$("#adding-cart-" + productId).show();
                        $("#add-cart-btn-buy-product-" + productId).hide();*/
                        window.location.href = '{{ url('/cart') }}';
                    },
                    error: function(error) {
                        console.error('Error adding to cart:', error);
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        function filter() {
            $('#search-form').submit();
        }

        function updatePriceInputs() {
            document.getElementById('input-min').value = document.querySelector('.range-min').value;
            document.getElementById('input-max').value = document.querySelector('.range-max').value;
            filter();
        }
    </script>


    <script>
        $(document).ready(function() {
            $('.add-to-wishlist-button').on('click', function() {
                var productId = $(this).data('product-id');

                $.ajax({
                    type: 'GET',
                    url: '{{ url('add-to-wishlist') }}/' + productId,
                    success: function(data) {
                        $("#adding-wishlist-" + productId).show();
                        $("#add-wishlist-btn-" + productId).hide();
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error adding to cart:', error);
                    }
                });
            });

            $('.remove-to-wishlist-button').on('click', function() {
                var productId = $(this).data('product-id');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('wishlist.remove') }}',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            $("#adding-wishlist-" + productId).hide();
                            $("#add-wishlist-btn-" + productId).show();
                            window.location.reload();
                        } else {
                            console.error(data.message);
                        }
                    },
                    error: function(error) {
                        console.error('Error removing from wishlist:', error);
                    }
                });
            });

        });
    </script>
@endsection

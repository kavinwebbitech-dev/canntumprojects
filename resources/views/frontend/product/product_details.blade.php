<style>
    .modal-content {
        border-radius: 8px;
    }

    .product-img img {
        width: 100%;
    }

    .amount_strike {
        text-decoration: line-through;
        font-size: 16px;
        /* margin-left: -5px !important ; */
    }
</style>


<section class="product_detail_inner">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5 col-md-4">
                <div class="product-img">
                    <img src="{{ url('public/product_images/' . $product->product_img) }}" alt="">
                </div>
            </div>

            <div class="col-lg-7 col-md-8">
                <div class="product_detail_content">
                    @php
                        $FirstProductDiscountedPrice =
                            $productDetails->price - $productDetails->price * ($product->discount / 100);
                    @endphp
                    <h2 class="detail_title">{{ $product->product_name ?? '' }}</h2>
                    <h3 class="product_rate">₹ {{ number_format($FirstProductDiscountedPrice ,2) }} <span>MRP (incl of all
                            taxes)</span></h3>
                    <span class="amount_strike">₹ {{ $productDetails->price }}</span> &nbsp; &nbsp; &nbsp;
                    <span class="offer-amount">{{ round($product->discount ?? '' )}}% Off</span>

                    @if (
                        $productDetails->images != null &&
                            $productDetails->color != null &&
                            ($productDetails->images != '' && $productDetails->color != ''))
                        <style>
                            .color_picker input {
                                display: none;
                            }

                            .color_picker label {
                                cursor: pointer;
                                border: 1px solid #b5b5b5;
                                border-radius: 10px;
                                display: inline-block;
                            }

                            .color_picker input:checked+label span {
                                border: 2px solid snow;
                                border-radius: 10px;
                                display: inline-block;
                                width: 20px;
                                height: 20px;
                            }

                            .product_detail_inner .product_detail_content .color_picker label:hover span {
                                outline: unset;
                                outline-offset: unset;
                            }
                        </style>
                        <div class="mt-3">
                            <h5 class="detail_subtitle">SELECT COLOR</h5>
                            <div class="color_picker">
                                @foreach ($colors as $color)
                                    <input type="radio" name="color" id="color-{{ $color->id }}"
                                        value="{{ $color->id }}"
                                        {{ request('color') == $color->id ? 'checked' : '' }}>
                                    <label for="color-{{ $color->id }}"
                                        style="background: {{ $color->code }}; display: inline-block;">
                                        <span></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-3" id="size-container" style="display: none;">
                            <!--   <h5 class="detail_subtitle">SELECT SIZE <span class="size_chart"><i class="bi bi-info-circle"></i>Size Chart</span></h5> -->
                            <div class="d-flex flex-wrap gap-3 d-md-block" id="sizes-list">
                                <!-- Sizes will be dynamically loaded here -->
                            </div>
                        </div>
                    @else
                        <div class="mt-3">
                            <!-- <h5 class="detail_subtitle">SELECT SIZE <span class="size_chart"><i class="bi bi-info-circle"></i>Size Chart</span></h5> -->
                            <form id="size-form" action="" method="GET">
                                <div class="d-flex flex-wrap gap-3 d-md-block">
                                    @foreach ($sizes as $size)
                                        <input type="radio" hidden="hidden" class="btn-check " name="size"
                                            id="size-{{ $size->id }}" value="{{ $size->id }}">
                                        <!--  <label class="btn size_btn" for="size-{{ $size->id }}">{{ $size->name }}</label> -->
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    @endif

                    <style>
                        .green-btn {
                            border: 1px solid #008000 !important;
                            background: #008000;
                            font-size: 15px;
                            font-weight: 400;
                            color: #fff;
                            border-radius: 25px;
                            padding: 12px 25px;
                            margin-right: 19px;
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

                    <div id="error-message" style="color: red; display: none; font-weight: 700;">Please select both
                        color and size.</div>
                    <div id="error-size-message" style="color: red; display: none; font-weight: 700;">Please select
                        size.</div>

                    <div class="mt-5 d-flex flex-wrap">
                        @if (
                            $productDetails->images != null &&
                                $productDetails->color != null &&
                                ($productDetails->images != '' && $productDetails->color != ''))
                            @if ($productDetails->quantity != 0 && $productDetails->quantity != '')
                                <a href="javascript:void(0);" data-product-id="{{ $productDetails->id }}"
                                    id="add-cart-btnn-{{ $productDetails->id }}"
                                    class="btn cart_btn add-cart-btn add-to-cart-buttonn">Add to Cart<i
                                        class="bi bi-cart2 ms-2"></i></a>

                                <a href="javascript:void(0);" data-product-id="{{ $productDetails->id }}"
                                    id="adding-cart-{{ $productDetails->id }}" style="display: none"
                                    class="btn green-btn"><i class="fas fa-shopping-cart"></i> Added</i></a>
                            @else
                                <div class="out-of-stock">
                                    <a class="btn cart_btn" style="background: white; color: #b33425;">(Out of
                                        stock)</a>
                                </div>
                            @endif
                        @else
                            @if ($productDetails->quantity != 0 && $productDetails->quantity != '')
                                <a href="javascript:void(0);" data-product-id="{{ $productDetails->id }}"
                                    id="add-cart-btnn-{{ $productDetails->id }}"
                                    class="btn cart_btn add-cart-btn add-to-cart-buttonn-size">Add to Cart<i
                                        class="bi bi-cart2 ms-2"></i></a>

                                <a href="javascript:void(0);" data-product-id="{{ $productDetails->id }}"
                                    id="adding-cart-{{ $productDetails->id }}" style="display: none"
                                    class="btn green-btn"><i class="fas fa-shopping-cart"></i> Added</i></a>
                            @else
                                <div class="out-of-stock">
                                    <a class="btn cart_btn" style="background: white; color: #b33425;">(Out of
                                        stock)</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const getSizesRoute = "{{ route('api.get-sizes') }}"; // Pass route name from Blade

        // Function to fetch sizes based on selected color
        function fetchSizesForColor(colorId) {
            if (!colorId) {
                document.getElementById('size-container').style.display = 'none';
                return;
            }

            fetch(`${getSizesRoute}?color_id=${colorId}`)
                .then(response => response.json())
                .then(data => {
                    const sizesList = document.getElementById('sizes-list');
                    sizesList.innerHTML = '';

                    if (data.sizes && data.sizes.length > 0) {
                        data.sizes.forEach(size => {
                            const sizeInput = document.createElement('input');
                            sizeInput.type = 'radio';
                            sizeInput.className = 'btn-check check_size';
                            sizeInput.name = 'size';
                            sizeInput.id = `size-${size.id}`;
                            sizeInput.value = size.id;
                            sizeInput.checked = data.selectedSize === size.id;

                            const sizeLabel = document.createElement('label');
                            sizeLabel.className = 'btn size_btn';
                            sizeLabel.htmlFor = `size-${size.id}`;
                            sizeLabel.textContent = size.name;

                            sizesList.appendChild(sizeInput);
                            sizesList.appendChild(sizeLabel);
                        });

                        document.getElementById('size-container').style.display = 'block';
                    } else {
                        document.getElementById('size-container').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching sizes:', error));
        }

        // Event listener for color changes
        document.querySelectorAll('.color_picker input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                fetchSizesForColor(this.value);
            });
        });

        // Optionally, load sizes for a pre-selected color on page load
        const selectedColor = document.querySelector('.color_picker input[type="radio"]:checked');
        if (selectedColor) {
            fetchSizesForColor(selectedColor.value);
        } else {
            document.getElementById('size-container').style.display = 'none';
        }

        // Ensure the form elements are found before attaching event listeners
        const combinedForm = document.getElementById('combined-form');
        const sizeForm = document.getElementById('size-form');

        if (combinedForm) {
            document.querySelectorAll('.color_picker input[type="radio"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    var url = new URL(window.location.href);
                    var params = new URLSearchParams(url.search);
                    params.delete('size');
                    params.set('color', this.value);
                    window.location.href = url.pathname + '?' + params.toString();
                });
            });

            document.querySelectorAll('.check_size[type="radio"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    combinedForm.submit();
                });
            });
        }

        if (sizeForm) {
            document.querySelectorAll('.check_size_without[type="radio"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    sizeForm.submit();
                });
            });
        }

        $(document).ready(function() {
            // Check if the item is in the cart and show the message if necessary
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

            $('.add-to-cart-buttonn').on('click', function() {
                var productId = $(this).data('product-id');
                var colorSelected = $('input[name="color"]:checked').val();
                var sizeSelected = $('input[name="size"]:checked').val();

                if (!colorSelected || !sizeSelected) {
                    $('#error-message').text('Please select both color and size.').show();
                    return;
                } else {
                    $('#error-message').hide();
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ url('add-to-cart') }}/' + productId,
                    success: function(data) {
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error adding to cart:', error);
                    }
                });
            });

            $('.add-to-cart-buttonn-size').on('click', function() {
                var productId = $(this).data('product-id');
                var sizeSelected = $('input[name="size"]:checked').val();

                if (!sizeSelected) {
                    $('#error-size-message').text('Please select a size.').show();
                    return;
                } else {
                    $('#error-size-message').hide();
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ url('add-to-cart') }}/' + productId,
                    success: function(data) {
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error adding to cart:', error);
                    }
                });
            });

            $('.add-to-cart-button-buy-product').on('click', function() {
                var productId = $(this).data('product-id');
                $.ajax({
                    type: 'GET',
                    url: '{{ url('add-to-cart-buy-product') }}/' + productId,
                    success: function(data) {
                        window.location.href = '{{ url('/cart') }}';
                    },
                    error: function(error) {
                        console.error('Error adding to cart:', error);
                    }
                });
            });

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
                        console.error('Error adding to wishlist:', error);
                    }
                });
            });
        });
    });
</script>

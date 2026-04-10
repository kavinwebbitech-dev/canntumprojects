@extends('frontend.layouts.app')

@section('meta_title'){{ $product->product_name }}@stop

@section('meta_description'){{ $product->description }}@stop

@section('meta')
    <meta itemprop="name" content="{{ $product->product_name }}">
    <meta itemprop="description" content="{{ $product->description }}">
    <meta itemprop="image" content="{{ url('public/product_images/' . $product->product_img) }}">
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $product->product_name }}">
    <meta name="twitter:description" content="{{ $product->description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ url('public/product_images/' . $product->product_img) }}">
    <meta name="twitter:data1" content="{{ $product->orginal_rate }}">
    <meta name="twitter:label1" content="Price">
    <meta property="og:title" content="{{ $product->product_name }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ url('public/product_images/' . $product->product_img) }}" />
    <meta property="og:description" content="{{ $product->description }}" />
    <meta property="og:site_name" content="{{ $product->product_name }}" />
    <meta property="og:price:amount" content="{{ $product->orginal_rate }}" />
@endsection

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
      .color-standed
    {
        width: 100%;
        height: 100%;
        min-height: 82px;
        
    }
    .amount_strike {
        text-decoration: line-through !important;
        font-size: 19px;
        color: #6c757d;
    }
    .offer-amount {
        font-size: 12px;
        text-align: center;
        padding: 4px 6px 3px;
        background: #db2828;
        display: inline-block;
        color: #fff !important;
    }
    .green-btn {
        border: 1px solid #272727 !important;
        background: #272727 !important;
        font-size: 15px;
        font-weight: 400;
        color: #fff;
        padding: 12px 25px;
        margin-right: 19px;
    }
    .green-btn:focus, .green-btn:hover {
        background: #272727 !important;
        color: #fff !important;
    }
    .wishlist_btn i { color: #000; transition: color 0.3s; }
    .wishlist_btn:hover i { color: red; }
    .wishlist_btn.active i { color: red; }

    /* ===== REVIEW STYLES ===== */
    .review-section-wrapper {
        position: relative;
        padding: 0 50px;
    }
    .reviewSwiper { overflow: hidden; }
    .reviewSwiper .swiper-slide { height: auto; }
    .reviewSwiper .swiper-wrapper { align-items: stretch; }

    .review-card {
        background: #fdfdfd;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
        padding: 20px;
        text-align: center;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: box-shadow 0.2s;
    }
    .review-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.10); }

    .review-avatar {
        width: 48px;
        height: 48px;
        background: #008000;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        margin: 0 auto 10px;
    }
    .review-name { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 6px; }
    .review-stars i { font-size: 14px; }
    .review-comment {
        font-size: 14px;
        color: #444;
        margin: 10px 0;
        min-height: 48px;
        word-break: break-word;
    }
    .review-date { font-size: 12px; color: #aaa; }

    .review-btn-prev,
    .review-btn-next {
        position: absolute;
        top: 50%;
        transform: translateY(-60%);
        z-index: 10;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #333;
        font-size: 16px;
        transition: background 0.2s, color 0.2s;
    }
    .review-btn-prev { left: 0; }
    .review-btn-next { right: 0; }
    .review-btn-prev:hover, .review-btn-next:hover {
        background: #001E40;
        color: #fff;
        border-color: #001E40;
    }
    .review-btn-prev.swiper-button-disabled,
    .review-btn-next.swiper-button-disabled {
        opacity: 0.35;
        pointer-events: none;
    }

    /* ===== PRODUCT DETAILS ACCORDION — fixed height + scroll ===== */
    .product-details-with-image {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .product-details-image-col {
        flex: 0 0 160px;
        max-width: 160px;
    }

    .product-details-image-col img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .product-details-text-col {
        flex: 1;
        min-width: 0;
    }

    /* The accordion body: fixed height 300px with vertical scroll */
    .product-desc-scroll {
        height: 300px;
        overflow-y: auto;
        font-size: 14px;
        color: #444;
        line-height: 1.7;
        padding-right: 4px;
    }

    /* Custom scrollbar styling */
    .product-desc-scroll::-webkit-scrollbar {
        width: 5px;
        color: #bbbbbb !important;
    }
    .product-desc-scroll::-webkit-scrollbar-track {
        background: #bbbbbb;
        border-radius: 4px;
    }
    .product-desc-scroll::-webkit-scrollbar-thumb {
        background: #272727 !important;
        border-radius: 4px;
    }
    .product-desc-scroll::-webkit-scrollbar-thumb:hover {
        background: #888;
    }

    @media (max-width: 576px) {
        .product-details-with-image {
            flex-direction: column;
        }
        .product-details-image-col {
            flex: 0 0 auto;
            max-width: 100%;
        }
        .product-details-image-col img {
            width: 100%;
            height: 200px;
        }
    }
 
    .new-colr {
        background-color: #272727 !important;
    }
.card_img img {
    width: 100%;
    height: 80px;
    object-fit: contain; /* Prevents logo/image from being cropped weirdly */
    background: #f9f9f9;
}

.product_card {
    border: 1px solid #eee;
    padding: 10px;
    border-radius: 5px;
    transition: 0.3s;
}

.product_card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.img-zoom-container {
  position: relative;
  cursor: crosshair;
}

.img-zoom-lens {
  position: absolute;
  border: 1px solid #d4d4d4;
  /* Size of the lens */
  width: 150px;
  height: 150px;
  background-color: rgba(255, 255, 255, 0.4);
  visibility: hidden; /* Hidden by default */
}

.img-zoom-result {
  border: 1px solid #d4d4d4;
  width: 400px;
  height: 400px;
  position: absolute;
  left: 105%; /* Pushes it to the right of the main image */
  top: 0;
  z-index: 999;
  background-repeat: no-repeat;
  visibility: hidden; /* Hidden by default */
  background-color: white;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Show lens and result on hover */
.img-zoom-container:hover .img-zoom-lens,
.img-zoom-container:hover + .img-zoom-result,
.img-zoom-container:hover ~ .img-zoom-result {
  visibility: visible;
}
.review-card {
    display: flex;
    flex-direction: column;
    height: 100%; /* Ensures all cards in a row have equal height */
    min-height: 350px; /* Set a minimum height for consistency */
    padding: 20px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    text-align: center;
}

.review-comment {
    flex-grow: 1; /* This pushes the date section to the bottom */
    display: flex;
    align-items: center; /* Centers short comments vertically */
    justify-content: center;
    margin: 15px 0;
    font-style: italic;
    color: #555;
    overflow-wrap: break-word; /* Prevents long text from breaking layout */
}

.review-footer {
    margin-top: auto; /* Force to bottom if flex-grow isn't enough */
    border-top: 1px solid #eee;
    padding-top: 10px;
}
ul {
        list-style-type: disc;
}

ol {
        list-style-type: decimal;
}
.butt-col.btn.active,
.butt-col.btn:active {
background-color: #272727 !important;
border-color: #272727 !important;
color: #fff !important;
}
.butt-col.btn:hover,
.butt-col.btn:focus {
background-color: #272727 !important;
border-color: #272727 !important;
color: #fff !important;
}
</style>


<section class="product_detail_inner">
   
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('category.list', $product->category->id) }}">
                        {{ $product->category->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Product Details</li>
            </ol>
        </nav>
        <h1 class="product_title">Product Details</h1>
        <div class="detail_divider mt-1">
            <div class="row gy-4">

                {{-- IMAGE GALLERY --}}
                <div class="col-lg-6">
                    <div class="image-gallery">
                        @php
                            $showVariantImages = false;
                            $imagesToShow = [];
                            if ($productDetail && $productDetail->images) {
                                $decoded = json_decode($productDetail->images, true);
                                if (!empty($decoded) && is_array($decoded)) {
                                    $showVariantImages = true;
                                    $imagesToShow = $decoded;
                                }
                            }
                        @endphp

                        @if ($showVariantImages)
                            <div class="row gy-4 gx-2">
                                <div class="col-lg-3 col-md-3 order-1 order-md-0">
                                    <aside class="thumbnails">
                                        @foreach ($imagesToShow as $key => $image)
                                            <a id="productDetailSideImage" href="javascript:void(0)" class="@if ($key == 0) selected @endif thumbnail"
                                                data-big="{{ url('public/variant_images/' . $image) }}">
                                                <div class="thumbnail-image" id="productDetailSideImage" style="background-image: url({{ url('public/variant_images/' . $image) }})"></div>
                                            </a>
                                        @endforeach
                                    </aside>
                                </div>
                               <div class="col-lg-9 col-md-9 order-0 order-md-1">
                                    <div class="big-img-container " style="position: relative;">
                                        <div class="big-img img-zoom-container">
                                            <img id="myimage" src="{{ url('public/variant_images/' . $imagesToShow[0]) }}" style="width:100%; height:100%;">
                                            <div id="zoom-lens" class="img-zoom-lens"></div>
                                        </div>
                                        
                                        <div id="myresult" class="img-zoom-result"></div>
                                    </div>
                                </div>
                            </div>

                        @elseif ($galleryImages->count() > 0)
                            <div class="row gy-4 gx-2">
                                <div class="col-lg-3 col-md-3 order-1 order-md-0">
                                    <aside class="thumbnails">
                                        @foreach ($galleryImages as $key => $image)
                                            <a href="javascript:void(0)" class="@if ($key == 0) selected @endif thumbnail"
                                                data-big="{{ url('public/' . $image->path) }}">
                                                <div class="thumbnail-image" style="background-image: url({{ url('public/' . $image->path) }})"></div>
                                            </a>
                                        @endforeach
                                    </aside>
                                </div>
                                <div class="col-lg-9 col-md-9 order-0 order-md-1">
                                    <div class="big-img">
                                        <main class="primary" style="background-image: url('{{ url('public/variant_images/' . $galleryImages[0]->path) }}');"></main>
                                    </div>
                                </div>
                            </div>

                        @else
                            <div class="row gy-4 gx-2">
                                <div class="col-lg-12">
                                    <div class="big-img">
                                        <main class="primary" style="background-image: url('{{ url('public/product_images/' . $product->product_img) }}');"></main>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- PRODUCT INFO --}}
                <div class="col-lg-6">
                    <div class="product_detail_content">
                        @php
                            $price = $product->orginal_rate;
                            $discountedPrice = $price - ($price * ($product->discount / 100));
                        @endphp

                        <h2 class="detail_title mb-0">{{ $product->product_name ?? '' }}</h2>
                        <div class="d-flex align-items-center gap-3">
                           @php
                            function formatIndianCurrency($number) {
                                $number = (string) round($number);
                                $len = strlen($number);
                                if ($len <= 3) return $number;

                                $last3 = substr($number, -3);
                                $rest = substr($number, 0, $len - 3);
                                return preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest) . "," . $last3;
                            }
                            @endphp

                            <h3 class="product_rate mb-0">
                                ₹ {{ formatIndianCurrency($discountedPrice) }}
                            </h3>

                            @if ($price && $product->discount)
                                <span class="amount_strike text-decoration-line-through">
                                    ₹ {{ formatIndianCurrency($price) }}
                                </span>
                            @endif
                            @if ($product->discount)
                                <span class="offer-amount text-success">{{ round($product->discount) }}% Off</span>
                            @endif
                        </div>

                        {{-- COLOR SELECTION --}}
                        @php
                            $variantColors = App\Models\ProductDetail::where('product_id', $product->id)
                                ->whereNotNull('color_id')->distinct('color_id')->pluck('color_id');
                        @endphp
                        
                        @if (count($variantColors) > 0)
                            <div class="mt-4">
                                <h5 class="detail_subtitle">SELECT COLOR</h5>
                                <form id="variant-form" action="" method="GET" class="mb-3">
                                    <div class="d-flex gap-2 flex-wrap">
                                        @php $colors = App\Models\Color::whereIn('id', $variantColors)->get(); @endphp
                                        @foreach ($colors as $color)
                                            <input type="radio" class="btn-check" name="color" id="color-{{ $color->id }}"
                                                value="{{ $color->id }}"
                                                @if ($colorId == $color->id || $colors->count() == 1) checked @endif>
                                            <label class="butt-col btn btn-outline-secondary @if ($colorId == $color->id || $colors->count() == 1) active @endif"
                                                for="color-{{ $color->id }}">{{ $color->color }}</label>
                                        @endforeach
                                    </div>
                                </form>
                            </div>
                           @else
                            <div class="color-standed">

                            </div>
                          
                        @endif

                        {{-- SIZE SELECTION --}}
                        @php
                            $variantSizes = [];
                            if ($colorId) {
                                $variantSizes = App\Models\ProductDetail::where('product_id', $product->id)
                                    ->where('color_id', $colorId)->whereNotNull('size_id')
                                    ->distinct('size_id')->pluck('size_id');
                            }
                        @endphp
                        @if (count($variantSizes) > 0)
                            <div class="mt-4">
                                <h5 class="detail_subtitle">SELECT SIZE</h5>
                                <div class="d-flex gap-2 flex-wrap">
                                    @php $sizes = App\Models\Size::whereIn('id', $variantSizes)->get(); @endphp
                                    @foreach ($sizes as $size)
                                        <input type="radio" class="btn-check" name="size" id="size-{{ $size->id }}"
                                            value="{{ $size->id }}"
                                            @if ($sizeId == $size->id || $sizes->count() == 1) checked @endif>
                                        <label class="butt-col btn btn-outline-secondary @if ($sizeId == $size->id || $sizes->count() == 1) active @endif"
                                            for="size-{{ $size->id }}">{{ $size->name }}</label>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="color-standed">

                            </div>
                        @endif
                        
                        
                            





                            @php
                                // If user selected variant (color/size)
                                if (isset($productDetail) && $productDetail) {
                                    $activeVariant = $productDetail;
                                } 
                                // If NO variant (simple product)
                                else {
                                    $activeVariant = App\Models\ProductDetail::where('product_id', $product->id)
                                        ->whereNull('color_id')
                                        ->whereNull('size_id')
                                        ->first();
                                }

                                // Check cart
                                $cart = session('cart', []);
                                $isInCart = $activeVariant && isset($cart[$activeVariant->id]);
                            @endphp

                        {{-- ACTION BUTTONS — out of stock now checks variant quantity specifically --}}
                        <div class="mt-4" style="max-width: 450px;">
                            @if ($activeVariant)

                            {{-- 🟢 IN STOCK --}}
                                    @if ($activeVariant->quantity > 0)

                                        <div class="d-flex align-items-center gap-2">
                                            <div class="flex-grow-1">

                                                {{-- ✅ Already in Cart --}}
                                                @if ($isInCart)
                                                    <a href="javascript:void(0);" class="btn green-btn w-100 mb-2">
                                                        <i class="fas fa-shopping-cart"></i> Added
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0);" 
                                                    data-product-id="{{ $activeVariant->id }}"
                                                    id="add-cart-btnn-{{ $activeVariant->id }}"
                                                    class="btn cart_btn add-to-cart-btn w-100 mb-2">
                                                        Add to Cart
                                                    </a>

                                                    <a href="javascript:void(0);" 
                                                    id="adding-cart-{{ $activeVariant->id }}"
                                                    style="display:none"
                                                    class="btn green-btn w-100 mb-2">
                                                        Added
                                                    </a>
                                                @endif

                                                {{-- 🛒 BUY NOW --}}
                                                <a href="javascript:void(0);" 
                                                data-product-id="{{ $activeVariant->id }}"
                                                class="btn book_btn buy-now-btn w-100 {{ $isInCart ? 'disabled' : '' }}"
                                                style="{{ $isInCart ? 'opacity:0.5; pointer-events:none;' : '' }}">
                                                    Buy Now
                                                </a>

                                            </div>

                                            {{-- ❤️ Wishlist (keep same) --}}
                                            <div class="d-flex flex-column justify-content-center align-items-center" style="margin-left: 30px;">
                                                <a href="javascript:void(0);" data-product-id="{{ $product->id }}"
                                                    id="add-wishlist-btn-{{ $product->id }}"
                                                    class="btn wishlist_btn add-to-wishlist-button p-0">
                                                    <i class="bi bi-heart-fill" style="font-size:22px;"></i>
                                                </a>
                                                <a id="adding-wishlist-{{ $product->id }}" data-product-id="{{ $product->id }}"
                                                    class="btn wishlist_btn remove-to-wishlist-button p-0" style="display: none;">
                                                    <i class="bi bi-heart-fill" style="font-size:22px;"></i>
                                                </a>
                                            </div>
                                        </div>

                                    {{-- 🔴 OUT OF STOCK BUT IN CART --}}
                                    @elseif ($isInCart)

                                        <a href="javascript:void(0);" class="btn green-btn w-100 mb-2">
                                            <i class="fas fa-shopping-cart"></i> Added
                                        </a>

                                        <button class="btn book_btn w-100 disabled" style="opacity:0.5; cursor:not-allowed;">
                                            Buy Now
                                        </button>

                                    {{-- ❌ OUT OF STOCK --}}
                                    @else

                                        {{-- <div class="alert alert-warning text-center" style="font-size: 16px;border-radius: 0;width: 365px;">
                                            <strong>Out of Stock</strong>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center align-items-center" style="margin-left: 30px;">
                                                <a href="javascript:void(0);" data-product-id="{{ $product->id }}"
                                                    id="add-wishlist-btn-{{ $product->id }}"
                                                    class="btn wishlist_btn add-to-wishlist-button p-0">
                                                    <i class="bi bi-heart-fill" style="font-size:22px;"></i>
                                                </a>
                                                <a id="adding-wishlist-{{ $product->id }}" data-product-id="{{ $product->id }}"
                                                    class="btn wishlist_btn remove-to-wishlist-button p-0" style="display: none;">
                                                    <i class="bi bi-heart-fill" style="font-size:22px;"></i>
                                                </a>
                                        </div> --}}

                                        <div class="d-flex justify-content-between align-items-center" style="gap:10px;">

                                            <div class="alert alert-warning text-center mb-0 flex-grow-1"
                                                style="font-size:16px; border-radius:0;">
                                                <strong>Out of Stock</strong>
                                            </div>

                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <a href="javascript:void(0);" 
                                                data-product-id="{{ $product->id }}"
                                                id="add-wishlist-btn-{{ $product->id }}"
                                                class="btn wishlist_btn add-to-wishlist-button p-0">
                                                    <i class="bi bi-heart-fill" style="font-size:22px;"></i>
                                                </a>

                                                <a id="adding-wishlist-{{ $product->id }}" 
                                                data-product-id="{{ $product->id }}"
                                                class="btn wishlist_btn remove-to-wishlist-button p-0" 
                                                style="display: none;">
                                                    <i class="bi bi-heart-fill" style="font-size:22px;"></i>
                                                </a>
                                            </div>

                                        </div>

                                    @endif

                            @else

                                <div class="alert alert-warning text-center" style="font-size: 16px;border-radius: 0;width: 365px;">
                                    <strong>Out of Stock</strong>
                                </div>

                            @endif
                        </div>

                        {{-- SHIPPING INFO --}}
                        <div class="product-shipping-box mt-4">
                            <div class="d-flex gap-3">
                                <div class="flex-fill border p-2 text-center">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="shipping-icon mb-2">
                                            <img src="{{ url('assets/images/fast-delivery.svg') }}" alt="" style="height:36px;">
                                        </div>
                                        <h6 class="title mb-0">All India Delivery</h6>
                                    </div>
                                </div>
                                <div class="flex-fill border p-2 text-center">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="shipping-icon mb-2">
                                            <img src="{{ url('assets/images/return.svg') }}" alt="" style="height:30px;">
                                        </div>
                                        <h6 class="title mb-0">Easy returns</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PRODUCT DESCRIPTION — with left image + fixed height 300px scroll --}}
                       
                    </div>
                </div>


                
            </div><br>
            <div class="row mt-4 gap-2 gap-lg-0">

                <!-- LEFT: FULL HEIGHT IMAGE -->
               <div class="col-md-6">
                    <div class="left-sticky-image">
                        @php
                            $img = !empty($productDetail->gallery_images) ? json_decode($productDetail->gallery_images, true) : [];
                        @endphp

                        {{-- Check if $img is an array AND has at least one image --}}
                        @if (is_array($img) && count($img) > 0 && !empty($img[0]))
                            <img src="{{ url('public/gallery_images/' . $img[0]) }}"
                                id="productDetailSideImage1232" 
                                style="object-fit: inherit; width: 100%; height: 400px !important">
                        @else
                            {{-- Fallback: Show the main product image if gallery is empty --}}
                            <img src="{{ url('public/product_images/' . $product->product_img) }}"
                                id="productDetailSideImage1232" 
                                style="object-fit: inherit; width: 100%; height: 500px !important">
                        @endif
                    </div>
            </div>

                <!-- RIGHT: ACCORDION -->
               <div class="col-md-6">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne">
                                    PRODUCT DETAILS
                                </button>
                            </h2>

                            <div id="collapseOne" class="accordion-collapse collapse show">
                                <div class="accordion-body" style="max-height: 448px; overflow-y: auto;">
                                    {!! $product->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                            
        </div>
    </div>

    {{-- CUSTOMER REVIEWS --}}
    <div class="container mt-5 mb-5">
        <div class="related_products">
            <h1 class="product_title text-center">Customer Reviews ({{ $reviews->count() }})</h1>
            <hr>

            @if ($reviews->count() == 0)
                <div class="py-5 text-center">
                    <p class="text-muted fs-5">No reviews found for this product yet.</p>
                </div>
            @else
                <div class="review-section-wrapper">
                    <div class="review-btn-prev"><i class="bi bi-chevron-left"></i></div>
                    <div class="review-btn-next"><i class="bi bi-chevron-right"></i></div>

                    <div class="swiper reviewSwiper pb-4">
                        <div class="swiper-wrapper">
                            @foreach ($reviews->sortByDesc('created_at')->take(6) as $review)
                                <div class="swiper-slide">
                                    <div class="review-card">
                                <div class="review-header">
                                    <div class="review-avatar mx-auto mb-2">
                                        {{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}
                                    </div>
                                    <div class="review-name"><strong>{{ $review->user->name ?? 'Guest User' }}</strong></div>
                                    <div class="review-stars mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa {{ $i <= $review->star_count ? 'fa-star text-warning' : 'fa-star-o text-muted' }}"></i>
                                        @endfor
                                    </div>
                                </div>

                                    <div class="review-comment">
                                        <p class="mb-0">"{{ $review->command ?? 'No comment.' }}"</p>
                                    </div>

                                    <div class="review-footer">
                                        <span class="review-date text-muted">{{ $review->created_at->format('d M, Y') }}</span>
                                    </div>
                                </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- RELATED PRODUCTS --}}
   <div class="container col-lg-12">
     @php
                $related_products = App\Models\Product::where('deleted', 0)
                    ->where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->limit(3)->latest()->get();
    @endphp

    @if ($related_products->count() > 0)
        <div class="related_products mt-4">
            <h1 class="product_title text-center">Related Products</h1>
            
            <div class="row gy-3">
            
                    @foreach ($related_products as $item)
                        @php
                            $details = App\Models\ProductDetail::where('product_id', $item->id)->first();
                            $discountedPrice = $details ? $details->price - ($details->price * ($item->discount / 100)) : 0;
                        @endphp
                        
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="product_card h-100">
                                <div class="row gx-2 h-100 align-items-center">
                                    <div class="col-5">
                                        <a href="{{ route('product.details.show', $item->id) }}" class="text-decoration-none">
                                            <div class="card_img">
                                                <img src="{{ url('public/product_images/' . $item->product_img) }}" class="img-fluid" alt="{{ $item->product_name }}">
                                                @if ($item->new_arrival == 1)
                                                    <div class="trending_bg">
                                                         <img src="<?php echo url(''); ?>/assets/images/cart-badge.svg" alt="" style="background: transparent;">New Arrival
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-7">
                                        <div class="card_body">
                                            <h5 class="card_title mb-1">
                                                <a href="{{ route('product.details.show', $item->id) }}" class="text-decoration-none text-dark">{{ $item->product_name }}</a>
                                            </h5>
                                            <p class="card_text mb-2">Rs. {{ round($discountedPrice) }}
                                                @if ($item->discount)
                                                    <span class="mx-1 offer_text" style="color: red; font-size: 0.8rem;">{{ round($item->discount) }}% Off</span>
                                                @endif
                                            </p>
                                            <a href="{{ route('product.details.show', $item->id) }}" class="btn cart_btn btn-sm">Add to cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-12 text-center mt-4">
                        <a href="{{ route('category.list', ['id' => $product->category_id]) }}" class="btn showmore_btn px-5">Show More</a>
                    </div>
            
            </div>
        </div>
     @endif
</div>

</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    // ===== REVIEWS SWIPER (single instance, no autoplay) =====
    var reviewCount = Math.min({{ $reviews->count() }}, 6);

    new Swiper(".reviewSwiper", {
        slidesPerView: Math.min(reviewCount, 3),
        spaceBetween: 20,
        loop: false,
        autoplay: false,
        navigation: {
            prevEl: ".review-btn-prev",
            nextEl: ".review-btn-next",
        },
        breakpoints: {
            0:   { slidesPerView: 1 },
            576: { slidesPerView: Math.min(reviewCount, 2) },
            992: { slidesPerView: Math.min(reviewCount, 3) },
        },
    });
</script>

<script>
$(document).ready(function () {

    var selectedImageIndex = 0;

    // ===== THUMBNAIL CLICK =====
    $(document).on('click', '.thumbnail', function () {
    var bigSrc = $(this).data('big');

    $('#myimage').attr('src', bigSrc);

    
    $('#myresult').css("background-image", "url('" + bigSrc + "')");

    
    $('.thumbnail').removeClass('selected');
    $(this).addClass('selected');

    imageZoom("myimage", "myresult");
});

    // ===== COLOR CHANGE =====
    $(document).on('change', 'input[name="color"]', function () {
        var currentUrl = new URL(window.location);
        currentUrl.searchParams.set('color', $(this).val());
        currentUrl.searchParams.delete('size');
        window.location.href = currentUrl.toString();
    });

    // ===== AUTO-SELECT if only 1 color (redirect to set URL param) =====
    $(document).ready(function () {
        var colorInputs = $('input[name="color"]');
        var sizeInputs  = $('input[name="size"]');

        // Auto redirect if only 1 color and not yet in URL
        if (colorInputs.length === 1) {
            var colorVal = colorInputs.val();
            var currentUrl = new URL(window.location);
            if (!currentUrl.searchParams.get('color')) {
                currentUrl.searchParams.set('color', colorVal);
                window.location.href = currentUrl.toString();
            }
        }

        // Auto redirect if only 1 size and not yet in URL
        if (sizeInputs.length === 1) {
            var sizeVal = sizeInputs.val();
            var currentUrl = new URL(window.location);
            if (!currentUrl.searchParams.get('size')) {
                currentUrl.searchParams.set('size', sizeVal);
                window.location.href = currentUrl.toString();
            }
        }
    });

    // ===== SIZE CHANGE =====
    $(document).on('change', 'input[name="size"]', function () {
        var colorId = $('input[name="color"]:checked').val();
        var sizeId = $(this).val();
        if (colorId && sizeId) {
            var currentUrl = new URL(window.location);
            currentUrl.searchParams.set('color', colorId);
            currentUrl.searchParams.set('size', sizeId);
            window.location.href = currentUrl.toString();
        }
    });

    // ===== ADD TO CART =====
    $(document).on('click', '.add-to-cart-btn', function () {
        var productId = $(this).data('product-id');
        $.ajax({
            type: 'GET',
            url: '{{ url('add-to-cart') }}/' + productId,
            data: { image_index: selectedImageIndex },
           success: function () {
                window.location.reload();   
                $('#add-cart-btnn-' + productId).hide();
                $('#adding-cart-' + productId).show();

                // 🔥 Disable Buy Now immediately
                $('.buy-now-btn[data-product-id="' + productId + '"]')
                    .addClass('disabled')
                    .css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
            }
        });
    });
    $(document).on('click', '.buy-now-btn', function () {
    if ($(this).hasClass('disabled')) return false;
});
    // ===== BUY NOW =====
    $(document).on('click', '.buy-now-btn', function () {
        var productId = $(this).data('product-id');
        $.ajax({
            type: 'GET',
            url: '{{ url('add-to-cart-buy-product') }}/' + productId,
            data: { image_index: selectedImageIndex },
            success: function () { window.location.href = '{{ url('/cart') }}'; }
        });
    });

    // ===== ADD TO WISHLIST =====
    $(document).on('click', '.add-to-wishlist-button', function () {
        var productId = $(this).data('product-id');
        $.ajax({
            type: 'GET',
            url: '{{ url('add-to-wishlist') }}/' + productId,
            success: function () {
                $("#adding-wishlist-" + productId).show();
                $("#add-wishlist-btn-" + productId).hide();
                window.location.reload();
            }
        });
    });

    // ===== REMOVE FROM WISHLIST =====
    $(document).on('click', '.remove-to-wishlist-button', function () {
        var productId = $(this).data('product-id');
        $.ajax({
            type: 'POST',
            url: '{{ route('wishlist.remove') }}',
            data: { product_id: productId, _token: '{{ csrf_token() }}' },
            success: function () {
                $("#adding-wishlist-" + productId).hide();
                $("#add-wishlist-btn-" + productId).show();
                window.location.reload();
            }
        });
    });

    // ===== WISHLIST STATUS =====
    @if (session('wishlist'))
        @foreach (session('wishlist') as $id => $details)
            $('#adding-wishlist-{{ $id }}').show();
            $('#add-wishlist-btn-{{ $id }}').hide();
        @endforeach
    @endif

    // ===== CART STATUS =====
    @if (session('cart'))
        @foreach (session('cart') as $id => $details)
            $('#adding-cart-{{ $id }}').show();
            $('#add-cart-btnn-{{ $id }}').hide();
        @endforeach @endif

                function imageZoom(imgID, resultID) {
                    var img, lens, result, cx, cy;
                    img = document.getElementById(imgID);
                    result = document.getElementById(resultID);

                    /* Create lens */
                    lens = document.getElementById("zoom-lens");

                    /* Calculate the ratio between result DIV and lens: */
                    cx = result.offsetWidth / lens.offsetWidth;
                    cy = result.offsetHeight / lens.offsetHeight;

                    /* Set background properties for the result DIV */
                    result.style.backgroundImage = "url('" + img.src + "')";
                    result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";

                    /* Execute a function when someone moves the cursor over the image, or the lens: */
                    lens.addEventListener("mousemove", moveLens);
                    img.addEventListener("mousemove", moveLens);
                    /* And also for touch screens: */
                    lens.addEventListener("touchmove", moveLens);
                    img.addEventListener("touchmove", moveLens);

                    function moveLens(e) {
                        var pos, x, y;
                        /* Prevent any other actions that may occur when moving over the image */
                        e.preventDefault();
                        /* Get the cursor's x and y positions: */
                        pos = getCursorPos(e);
                        /* Calculate the position of the lens: */
                        x = pos.x - (lens.offsetWidth / 2);
                        y = pos.y - (lens.offsetHeight / 2);
                        /* Prevent the lens from being positioned outside the image: */
                        if (x > img.width - lens.offsetWidth) {
                            x = img.width - lens.offsetWidth;
                        }
                        if (x < 0) {
                            x = 0;
                        }
                        if (y > img.height - lens.offsetHeight) {
                            y = img.height - lens.offsetHeight;
                        }
                        if (y < 0) {
                            y = 0;
                        }
                        /* Set the position of the lens: */
                        lens.style.left = x + "px";
                        lens.style.top = y + "px";
                        /* Display what the lens "sees": */
                        result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
                    }

                    function getCursorPos(e) {
                        var a, x = 0,
                            y = 0;
                        e = e || window.event;
                        /* Get the x and y positions of the image: */
                        a = img.getBoundingClientRect();
                        /* Calculate the cursor's x and y coordinates, relative to the image: */
                        x = e.pageX - a.left;
                        y = e.pageY - a.top;
                        /* Consider any page scrolling: */
                        x = x - window.pageXOffset;
                        y = y - window.pageYOffset;
                        return {
                            x: x,
                            y: y
                        };
                    }
                }

                // Initialize the zoom
                window.onload = function() {
                    imageZoom("myimage", "myresult");
                };
  });

document.addEventListener("DOMContentLoaded", initGallery);
window.addEventListener("resize", debounce(initGallery, 300));

function initGallery() {

    const isMobile = window.innerWidth < 768;
    let img = document.getElementById("myimage");

    // 🧹 CLEAN PREVIOUS STATE
    document.getElementById("zoom-lens")?.remove();
    document.getElementById("myresult")?.remove();

    const zoomContainer = document.querySelector(".img-zoom-container");
    if (zoomContainer) {
        zoomContainer.classList.remove("img-zoom-container");
    }

    // remove old image listeners by cloning
    if (img) {
        const newImg = img.cloneNode(true);
        img.parentNode.replaceChild(newImg, img);
        img = newImg;
    }

    // 📱 MOBILE MODE
    if (isMobile) {
        // nothing else → clean scroll works
    }

    // 💻 DESKTOP MODE
    else {
        // recreate zoom elements
        const container = document.querySelector(".big-img");
        if (container && img) {

            container.classList.add("img-zoom-container");

            const lens = document.createElement("div");
            lens.id = "zoom-lens";
            lens.className = "img-zoom-lens";

            const result = document.createElement("div");
            result.id = "myresult";
            result.className = "img-zoom-result";

            container.appendChild(lens);
            container.parentElement.appendChild(result);

            if (typeof imageZoom === "function") {
                imageZoom("myimage", "myresult");
            }
        }
    }

    // 🖼️ THUMBNAIL CLICK (RE-ATTACH SAFE)
    document.querySelectorAll(".thumbnail").forEach(el => {
        el.onclick = function () {
            const src = this.getAttribute("data-big");
            if (img && src) img.src = src;
        };
    });
}

// ⚡ debounce to prevent lag on resize
function debounce(func, delay) {
    let timer;
    return function () {
        clearTimeout(timer);
        timer = setTimeout(func, delay);
    };
}


  </script>

@endsection

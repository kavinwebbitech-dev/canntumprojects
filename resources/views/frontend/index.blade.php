@extends('frontend.layouts.app')
@section('content')

    <style>
        .amount_strike {
            text-decoration: line-through !important;
            font-size: 15px;
            color: #6c757d;
            margin-left: 3px;
        }

        .offer_text {
            background: #f7ece6;
            padding: 1px 3px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        a {
            color: #fff !important;
            text-decoration: none;
        }

        /* Fix card equal height */
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
        .card_body .col-6:first-child,
        .card_body .col-7 {
            flex: 1 1 auto;
            min-width: 0;
            overflow: hidden;
        }

        /* Button section — never shrink */
        .card_body .col-6:last-child,
        .card_body .col-5 {
            flex: 0 0 auto;
            width: auto;
        }

        /* Discount badge placeholder — keeps height same even when no badge */
        .card_text .row {
            min-height: 28px;
            flex-wrap: nowrap;
            align-items: center;
        }

        /* Price text truncate if too long */
        .card_body .price {
            white-space: nowrap;
            font-size: 15px;
        }

        .card_body .amount_strike {
            font-size: 13px;
        }

        .col-auto {
            flex: 0 0 auto !important;
            width: 100px !important;
        }

        .card_body .offer_text {
            white-space: nowrap;
            font-size: 11px;
        }

        /* Button never wraps */
        .shop-btn {
            white-space: nowrap;
            font-size: 12px;
            padding: 5px 10px;
        }
        .coll-6 {
            padding: 0 5px; 
        }
        .pado {
            padding: 0 ; 
            width: auto !important;
        }
        .pri {
            padding: 0 6px; 
            width: auto !important;
        }
    </style>

    <section class="banner">
        <div class="owl-carousel banner-carousel">
            @php
                $banner = App\Models\BannerImages::latest()->get();
            @endphp

            @if ($banner)
                @foreach ($banner as $key => $item)
                    <div class="item">
                        <img src="{{ url('public/banner_images/' . $item->image) }}" alt="Banner {{ $key + 1 }}">
                    </div>
                @endforeach
            @endif
        </div>
    </section>

    <section class="trending_collection">
        <div class="container">
            {{-- <h1 class="trending_title animate__animated animate__fadeInLeft" data-delay="2s">Trending must-haves</h1> --}}
            <div class="mt-lg-5 mt-2">
                <div class="collection_list">
                    <div class="row gy-4 gx-lg-2 gx-xxl-5">
                        @php
                            $product_unique_img = App\Models\Product::where('deleted', 0)
                                ->where('new_arrival', 1)
                                ->where('status', 1)
                                ->latest()
                                ->take(4)
                                ->get();
                        @endphp

                        @foreach ($product_unique_img as $key => $value)
                            <div class="col-lg-3 col-md-6 animate__animated animate__fadeIn" data-delay="2s">
                                <div class="card">
                                    <div class="card_img">
                                        <a href="{{ route('product.details.show', $value->id) }}">
                                            <img src="{{ url('public/product_images/' . $value->product_img) }}"
                                                alt="">
                                        </a>
                                        <div class="trending_bg">
                                            <img src="<?php echo url(''); ?>/assets/images/cart-badge.svg" alt="">
                                            New Arrival
                                        </div>
                                    </div>
                                    @php
                                        $product_price = App\Models\ProductDetail::where(
                                            'product_id',
                                            $value->id,
                                        )->first();
                                        // dd($product_price);
                                    @endphp
                                    <div class="card_body">
                                        <h5 class="card_title text-truncate text-center">
                                            <a style="color:white !important;"
                                                href="{{ route('product.details.show', $value->id) }}">
                                                {{ $value->product_name }}
                                            </a>
                                        </h5>
                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                @if ($product_price)
                                                    <div class="col-6 coll-6">
                                                        @php
                                                            $FirstProductDiscountedPrice =
                                                                $product_price->price -
                                                                $product_price->price * ($value->discount / 100);
                                                        @endphp
                                                        <div class="card_text">
                                                            <div class="row align-items-center">
                                                                <div class="col-auto pri">
                                                                    <span class="price">
                                                                        Rs. {{ round($FirstProductDiscountedPrice) }}
                                                                        @if ($value->discount)
                                                                            <span
                                                                                class="amount_strike">{{ $product_price->price }}
                                                                            </span>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                                @if ($value->discount)
                                                                    <div class="col-auto pado">
                                                                        <span
                                                                            class="offer_text">{{ round($value->discount) }}%
                                                                            Off</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-6 text-end">
                                                @if ($product_price && $product_price->quantity > 0)
                                                    <p class="text-end">
                                                        <a href="{{ route('product.details.show', $value->id) }}"
                                                            class="btn shop-btn">Add to cart</a>
                                                    </p>
                                                @else
                                                    {{-- Out of Stock --}}
                                                    <span class="shop-btn"
                                                        style="font-size: 12px; padding: 7px 6px; cursor: not-allowed; opacity: 0.6;">
                                                        Out of stock
                                                    </span>
                                                @endif
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
    </section>

    <section class="best_product">
        <div class="container">
            <h5 class="product_title animate__animated animate__fadeInLeft" data-delay="2s">Best Selling Products</h5>
            <div class="product_collections mt-5">
                <div class="owl-carousel product-slider">
                    @php
                        $product_unique_img = App\Models\Product::where('deleted', 0)
                            ->where('best_sellers', 1)
                            ->where('status', 1)
                            ->latest()
                            ->get();
                    @endphp

                    @foreach ($product_unique_img as $key => $value)
                        @php
                            $product_price = App\Models\ProductDetail::where('product_id', $value->id)->first();
                        @endphp
                        <div class="item">
                            <div class="card">
                                <div class="card_img">
                                    <a href="{{ route('product.details.show', $value->id) }}">
                                        <img src="{{ url('public/product_images/' . $value->product_img) }}"
                                            alt="">
                                    </a>
                                </div>
                                <div class="card_body">
                                    <h5 class="card_title text-truncate">
                                        <a
                                            href="{{ route('product.details.show', $value->id) }}">{{ $value->product_name }}</a>
                                    </h5>
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            @if ($product_price)
                                                <div class="col-6 coll-6">
                                                    @php
                                                        $FirstProductDiscountedPrice =
                                                            $product_price->price -
                                                            $product_price->price * ($value->discount / 100);
                                                    @endphp
                                                    <div class="card_text">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto pri">
                                                                <span class="price">
                                                                    Rs. {{ round($FirstProductDiscountedPrice) }}
                                                                    @if ($value->discount)
                                                                        <span
                                                                            class="amount_strike">{{ $product_price->price }}
                                                                        </span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            @if ($value->discount)
                                                                <div class="col-auto pado">
                                                                    <span class="offer_text">{{ round($value->discount) }}%
                                                                        Off</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-6 text-end">
                                            @if ($product_price && $product_price->quantity > 0)
                                                <p class="text-end">
                                                    <a href="{{ route('product.details.show', $value->id) }}"
                                                        class="btn shop-btn">Add to cart</a>
                                                </p>
                                            @else
                                                {{-- Out of Stock --}}
                                                <span class="shop-btn"
                                                    style="font-size: 12px; padding: 7px 6px; cursor: not-allowed; opacity: 0.6;">
                                                    Out of stock
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="offer_product">
        <h5 class="product_title animate__animated animate__fadeInLeft" data-delay="2s">offer Products</h5>
        <div class="mt-5">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-4 col-md-4">
                    <div class="product_left animate__animated animate__fadeInUp" data-delay="2s">
                        <h5 class="product_left_title">Popular Categories</h5>
                        <div class="d-flex align-items-start">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-new-arrival-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-new-arrival" type="button" role="tab"
                                    aria-controls="v-pills-new-arrival" aria-selected="true">New Arrivals</button>
                                <button class="nav-link" id="v-pills-best-selling-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-best-selling" type="button" role="tab"
                                    aria-controls="v-pills-best-selling" aria-selected="false">Best Selling</button>
                                <button class="nav-link" id="v-pills-trending-week-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-trending-week" type="button" role="tab"
                                    aria-controls="v-pills-trending-week" aria-selected="false">Trending This
                                    Week</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="product_collections animate__animated animate__fadeInRight" data-delay="2s">
                        <div class="tab-content" id="v-pills-tabContent">
                            <!-- New Arrivals Tab -->
                            <div class="tab-pane fade show active" id="v-pills-new-arrival" role="tabpanel"
                                aria-labelledby="v-pills-new-arrival-tab" tabindex="0">
                                <div class="owl-carousel product-slider1">
                                    @php
                                        $product_unique_img = App\Models\Product::where('deleted', 0)
                                            ->where('new_arrival', 1)
                                            ->where('status', 1)
                                            ->latest()
                                            ->get();
                                    @endphp

                                    @foreach ($product_unique_img as $key => $value)
                                        @php
                                            $product_price = App\Models\ProductDetail::where(
                                                'product_id',
                                                $value->id,
                                            )->first();
                                        @endphp
                                        <div class="item">
                                            <div class="card">
                                                <div class="card_img">
                                                    <a href="{{ route('product.details.show', $value->id) }}">
                                                        <img src="{{ url('public/product_images/' . $value->product_img) }}"
                                                            alt="">
                                                    </a>
                                                </div>
                                                <div class="card_body">
                                                    <h5 class="card_title text-truncate">
                                                        <a
                                                            href="{{ route('product.details.show', $value->id) }}">{{ $value->product_name }}</a>
                                                    </h5>
                                                    <div class="row align-items-center">
                                                        <div class="col-7">
                                                            @php
                                                                $price = $product_price
                                                                    ? $product_price->price -
                                                                        $product_price->price * ($value->discount / 100)
                                                                    : 0;
                                                            @endphp
                                                            <div class="card_text">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto pri">
                                                                        <span class="price" style="padding-left: 3px;">
                                                                            Rs. {{ round($price) }}
                                                                            @if ($product_price && $value->discount)
                                                                                <span
                                                                                    class="amount_strike">{{ $product_price->price }}
                                                                                </span>
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                    @if ($value->discount)
                                                                        <div class="col-auto pado">
                                                                            <span
                                                                                class="offer_text">{{ round($value->discount) }}%
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-5 text-end">
                                                            @if ($product_price && $product_price->quantity > 0)
                                                                <p class="text-end">
                                                                    <a href="{{ route('product.details.show', $value->id) }}"
                                                                        class="btn shop-btn">Add to cart</a>
                                                                </p>
                                                            @else
                                                                {{-- Out of Stock --}}
                                                                <span class="shop-btn"
                                                                    style="font-size: 12px; padding: 7px 6px; cursor: not-allowed; opacity: 0.6;">
                                                                    Out of stock
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Best Selling Tab -->
                            <div class="tab-pane fade" id="v-pills-best-selling" role="tabpanel"
                                aria-labelledby="v-pills-best-selling-tab" tabindex="0">
                                <div class="owl-carousel product-slider1">
                                    @php
                                        $product_unique_img = App\Models\Product::where('deleted', 0)
                                            ->where('best_sellers', 1)
                                            ->where('status', 1)
                                            ->latest()
                                            ->get();
                                    @endphp

                                    @foreach ($product_unique_img as $key => $value)
                                        @php
                                            $product_price = App\Models\ProductDetail::where(
                                                'product_id',
                                                $value->id,
                                            )->first();
                                        @endphp
                                        <div class="item">
                                            <div class="card">
                                                <div class="card_img">
                                                    <a href="{{ route('product.details.show', $value->id) }}">
                                                        <img src="{{ url('public/product_images/' . $value->product_img) }}"
                                                            alt="">
                                                    </a>
                                                </div>
                                                <div class="card_body">
                                                    <h5 class="card_title text-truncate">
                                                        <a
                                                            href="{{ route('product.details.show', $value->id) }}">{{ $value->product_name }}</a>
                                                    </h5>
                                                    <div class="row align-items-center">
                                                        <div class="col-7">
                                                            @php
                                                                $price = $product_price
                                                                    ? $product_price->price -
                                                                        $product_price->price * ($value->discount / 100)
                                                                    : 0;
                                                            @endphp
                                                            <div class="card_text">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto pri">
                                                                        <span class="price" style="padding-left: 3px;">
                                                                            Rs. {{ round($price) }}
                                                                            @if ($product_price && $value->discount)
                                                                                <span
                                                                                    class="amount_strike">{{ $product_price->price }}
                                                                                </span>
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                    @if ($value->discount)
                                                                        <div class="col-auto pado">
                                                                            <span
                                                                                class="offer_text">{{ round($value->discount) }}%
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-5 text-end">
                                                            @if ($product_price && $product_price->quantity > 0)
                                                                <p class="text-end">
                                                                    <a href="{{ route('product.details.show', $value->id) }}"
                                                                        class="btn shop-btn">Add to cart</a>
                                                                </p>
                                                            @else
                                                                {{-- Out of Stock --}}
                                                                <span class="shop-btn"
                                                                    style="font-size: 12px; padding: 7px 6px; cursor: not-allowed; opacity: 0.6;">
                                                                    Out of stock
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Trending This Week Tab -->
                            <div class="tab-pane fade" id="v-pills-trending-week" role="tabpanel"
                                aria-labelledby="v-pills-trending-week-tab" tabindex="0">
                                <div class="owl-carousel product-slider1">
                                    @php
                                        $product_unique_img = App\Models\Product::where('deleted', 0)
                                            ->where('trending_tshirt', 1)
                                            ->where('status', 1)
                                            ->latest()
                                            ->get();
                                    @endphp

                                    @foreach ($product_unique_img as $key => $value)
                                        @php
                                            $product_price = App\Models\ProductDetail::where(
                                                'product_id',
                                                $value->id,
                                            )->first();
                                        @endphp
                                        <div class="item">
                                            <div class="card">
                                                <div class="card_img">
                                                    <a href="{{ route('product.details.show', $value->id) }}">
                                                        <img src="{{ url('public/product_images/' . $value->product_img) }}"
                                                            alt="">
                                                    </a>
                                                </div>
                                                <div class="card_body">
                                                    <h5 class="card_title text-truncate">
                                                        <a
                                                            href="{{ route('product.details.show', $value->id) }}">{{ $value->product_name }}</a>
                                                    </h5>
                                                    <div class="row align-items-center">
                                                        <div class="col-7">
                                                            @php
                                                                $price = $product_price
                                                                    ? $product_price->price -
                                                                        $product_price->price * ($value->discount / 100)
                                                                    : 0;
                                                            @endphp
                                                            <div class="card_text">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto pri">
                                                                        <span class="price" style="padding-left: 3px;">
                                                                            Rs. {{ round($price) }}
                                                                            @if ($product_price && $value->discount)
                                                                                <span
                                                                                    class="amount_strike">{{ $product_price->price }}
                                                                                </span>
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                    @if ($value->discount)
                                                                        <div class="col-auto pado">
                                                                            <span
                                                                                class="offer_text">{{ round($value->discount) }}%
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-5 text-end">
                                                            @if ($product_price && $product_price->quantity > 0)
                                                                <p class="text-end">
                                                                    <a href="{{ route('product.details.show', $value->id) }}"
                                                                        class="btn shop-btn">Add to cart</a>
                                                                </p>
                                                            @else
                                                                {{-- Out of Stock --}}
                                                                <span class="shop-btn"
                                                                    style="font-size: 12px; padding: 7px 6px; cursor: not-allowed; opacity: 0.6;">
                                                                    Out of stock
                                                                </span>
                                                            @endif
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
            </div>
        </div>
    </section>

    <section class="divider_section animate__animated animate__fadeIn" data-delay="2s">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="card mens_collection">
                        <div class="row g-0">
                            <div class="col-md-6 order-md-0 order-1">
                                <div class="card_body">
                                    <div>
                                        <h4 class="card_title" style="font-size: 40px;">Your Daily Skin Luxuries</h4>
                                        <p class="card_text" style="font-size: 20px;">Minimal steps. Maximum glow</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 order-md-1 order-0">
                                <div class="card_img">
                                    <img src="<?php echo url(''); ?>/assets/images/canntum_banner.png" alt="...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="offer_product">
        <div class="container">
            <h5 class="product_title animate__animated animate__fadeInLeft" data-delay="2s">Trending Products</h5>
            <div class="mt-5">
                <div class="product_collections animate__animated animate__fadeIn" data-delay="2s">
                    <div class="owl-carousel product-slider">
                        @php
                            $product_unique_img = App\Models\Product::where('deleted', 0)
                                ->where('trending_tshirt', 1)
                                ->where('status', 1)
                                ->latest()
                                ->get();
                        @endphp

                        @foreach ($product_unique_img as $key => $value)
                            @php
                                $product_price = App\Models\ProductDetail::where('product_id', $value->id)->first();
                            @endphp
                            <div class="item">
                                <div class="card">
                                    <div class="card_img">
                                        <a href="{{ route('product.details.show', $value->id) }}">
                                            <img src="{{ url('public/product_images/' . $value->product_img) }}"
                                                alt="">
                                        </a>
                                    </div>
                                    <div class="card_body">
                                        <h5 class="card_title text-truncate">
                                            <a
                                                href="{{ route('product.details.show', $value->id) }}">{{ $value->product_name }}</a>
                                        </h5>
                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                @php
                                                    $price = $product_price
                                                        ? $product_price->price -
                                                            $product_price->price * ($value->discount / 100)
                                                        : 0;
                                                @endphp
                                                <div class="card_text">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto pri">
                                                            <span class="price" style="padding-left: 3px;">
                                                                Rs. {{ round($price) }}
                                                                @if ($product_price && $value->discount)
                                                                    <span
                                                                        class="amount_strike">{{ $product_price->price }}</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        @if ($value->discount)
                                                            <div class="col-auto pado ">
                                                                <span class="offer_text">{{ round($value->discount) }}%
                                                                    Off</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 text-end">
                                                @if ($product_price && $product_price->quantity > 0)
                                                    <p class="text-end">
                                                        <a href="{{ route('product.details.show', $value->id) }}"
                                                            class="btn shop-btn">Add to cart</a>
                                                    </p>
                                                @else
                                                    {{-- Out of Stock --}}
                                                    <span class="shop-btn"
                                                        style="font-size: 12px; padding: 7px 6px; cursor: not-allowed; opacity: 0.6;">
                                                        Out of stock
                                                    </span>
                                                @endif
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
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const elements = document.querySelectorAll(".animate__animated");

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const animationClasses = Array.from(el.classList).filter(cls => cls
                            .startsWith("animate__") && cls !== "animate__animated");
                        const delay = el.dataset.delay || "0s";
                        const duration = el.dataset.duration || "1s";

                        el.style.setProperty("--animate-delay", delay);
                        el.style.setProperty("--animate-duration", duration);
                        el.style.opacity = "1";

                        el.classList.remove(...animationClasses);
                        void el.offsetWidth;
                        el.classList.add(...animationClasses);
                        obs.unobserve(el);
                    }
                });
            }, {
                threshold: 0.2
            });

            elements.forEach(el => {
                el.style.opacity = "0";
                observer.observe(el);
            });
        });
    </script>

@endsection

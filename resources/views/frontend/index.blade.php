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
            padding: 0;
            width: auto !important;
        }

        .pri {
            padding: 0 6px;
            width: auto !important;
        }
        /* @media (max-width: 768px) {
            .card.jst-pr-card-outer.off-cards {
                width: 85%;
            }
        } */

         @media (max-width: 325px) {
            .card.jst-pr-card-outer.off-cards {
                width: 85%;
            }
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
        <div class="container jst-pr-grid-container">
            <div class="row">

                @php
                    $products = App\Models\Product::where('deleted', 0)
                        ->where('new_arrival', 1)
                        ->where('status', 1)
                        ->latest()
                        ->take(4)
                        ->get();
                @endphp

                @foreach ($products as $value)
                    @php
                        $product_price = App\Models\ProductDetail::where('product_id', $value->id)->first();
                    @endphp

                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card jst-pr-card-outer">

                            <!-- IMAGE -->
                            <div class="jst-pr-media-frame">
                                <a href="{{ route('product.details.show', $value->id) }}">
                                    <img loading="lazy" src="{{ url('public/product_images/' . $value->product_img) }}"
                                        alt="{{ $value->product_name }}">
                                </a>

                                <div class="jst-pr-badge-layer">
                                    <img class="badge-imgg" src="{{ url('assets/images/cart-badge.svg') }}" width="16"
                                        alt="">
                                    <p class="badge-p">New Arrival</p>
                                </div>
                            </div>

                            <!-- CONTENT -->
                            <div class="jst-pr-info-block">

                                <!-- TITLE -->
                                <h5 class="jst-pr-headline text-truncate">
                                    <a href="{{ route('product.details.show', $value->id) }}"
                                        style="color:white; text-decoration:none;">
                                        {{ $value->product_name }}
                                    </a>
                                </h5>

                                <!-- FOOTER -->
                                <div class="jst-pr-action-row">

                                    <!-- PRICE -->
                                    <div class="jst-pr-val-stack">
                                        @if ($product_price)
                                            @php
                                                $discounted =
                                                    $product_price->price -
                                                    $product_price->price * ($value->discount / 100);
                                            @endphp

                                            <span class="jst-pr-active-price">
                                                Rs. {{ round($discounted) }}
                                            </span>

                                            @if ($value->discount)
                                                <span class="jst-pr-was-price">
                                                    {{ $product_price->price }}
                                                </span>

                                                <span class="jst-pr-pct-tag">
                                                    {{ round($value->discount) }}% Off
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- BUTTON -->
                                    @if ($product_price && $product_price->quantity > 0)
                                        <a href="{{ route('product.details.show', $value->id) }}"
                                            class="btn jst-pr-buy-btn">
                                            Add to cart
                                        </a>
                                    @else
                                        <span class="btn jst-pr-buy-btn1" style="cursor:not-allowed; opacity:0.6;">
                                            Out of stock
                                        </span>
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <section class="best_product">
        <div class="container">
            <h5 class="product_title animate__animated animate__fadeInLeft" data-delay="2s">
                Best Selling Products
            </h5>

            <div class="product_collections mt-5">
                <div class="owl-carousel product-slider">

                    @php
                        $products = App\Models\Product::where('deleted', 0)
                            ->where('best_sellers', 1)
                            ->where('status', 1)
                            ->latest()
                            ->get();
                    @endphp

                    @foreach ($products as $value)
                        @php
                            $product_price = App\Models\ProductDetail::where('product_id', $value->id)->first();
                        @endphp

                        <div class="item">
                            <div class="card jst-pr-card-outer">

                                <!-- IMAGE -->
                                <div class="jst-pr-media-frame">
                                    <a href="{{ route('product.details.show', $value->id) }}">
                                        <img loading="lazy" src="{{ url('public/product_images/' . $value->product_img) }}"
                                            alt="{{ $value->product_name }}">
                                    </a>

                                    <div class="jst-pr-badge-layer">
                                        <img class="badge-imgg" src="{{ url('assets/images/cart-badge.svg') }}"
                                            width="16" alt="">
                                        <p class="badge-p">Best Seller</p>
                                    </div>
                                </div>

                                <!-- CONTENT -->
                                <div class="jst-pr-info-block">

                                    <!-- TITLE -->
                                    <h5 class="jst-pr-headline text-truncate">
                                        <a href="{{ route('product.details.show', $value->id) }}"
                                            style="color:white; text-decoration:none;">
                                            {{ $value->product_name }}
                                        </a>
                                    </h5>

                                    <!-- FOOTER -->
                                    <div class="jst-pr-action-row">

                                        <!-- PRICE -->
                                        <div class="jst-pr-val-stack">
                                            @if ($product_price)
                                                @php
                                                    $discounted =
                                                        $product_price->price -
                                                        $product_price->price * ($value->discount / 100);
                                                @endphp

                                                <span class="jst-pr-active-price">
                                                    Rs. {{ round($discounted) }}
                                                </span>

                                                @if ($value->discount)
                                                    <span class="jst-pr-was-price">
                                                        {{ $product_price->price }}
                                                    </span>

                                                    <span class="jst-pr-pct-tag">
                                                        {{ round($value->discount) }}% Off
                                                    </span>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- BUTTON -->
                                        @if ($product_price && $product_price->quantity > 0)
                                            <a href="{{ route('product.details.show', $value->id) }}"
                                                class=" jst-pr-buy-btn">
                                                Add to cart
                                            </a>
                                        @else
                                            <span class="btn jst-pr-buy-btn1" style="cursor:not-allowed; opacity:0.6;">
                                                Out of stock
                                            </span>
                                        @endif

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
        <div class="container">
            <h5 class="product_title">Offer Products</h5>

            <div class="mt-5">
                <div class="row gy-4 align-items-center">

                    <!-- LEFT MENU -->
                    <div class="col-lg-4 col-md-4">
                        <div class="product_left">
                            <h5 class="product_left_title">Popular Categories</h5>

                            <div class="nav flex-column nav-pills">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-new">
                                    New Arrivals
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-best">
                                    Best Selling
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-trending">
                                    Trending This Week
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT CONTENT -->
                    <div class="col-lg-8 col-md-8">
                        <div class="tab-content">

                            <!-- ================= NEW ARRIVAL ================= -->
                            <div class="tab-pane fade show active" id="tab-new">
                                <div class="owl-carousel product-slider1">

                                    @php
                                        $products = App\Models\Product::where('deleted', 0)
                                            ->where('new_arrival', 1)
                                            ->where('status', 1)
                                            ->latest()
                                            ->get();
                                    @endphp

                                    @foreach ($products as $value)
                                        @php
                                            $product_price = App\Models\ProductDetail::where(
                                                'product_id',
                                                $value->id,
                                            )->first();
                                        @endphp

                                        <div class="item mx-1 mt-1">
                                            <div class="card jst-pr-card-outer off-cards">

                                                <!-- IMAGE -->
                                                <div class="jst-pr-media-frame">
                                                    <a href="{{ route('product.details.show', $value->id) }}">
                                                        <img loading="lazy"
                                                            src="{{ url('public/product_images/' . $value->product_img) }}"
                                                            alt="{{ $value->product_name }}">
                                                    </a>

                                                    <div class="jst-pr-badge-layer">
                                                        <img class="badge-imgg"
                                                            src="{{ url('assets/images/cart-badge.svg') }}" alt="">
                                                        <p class="badge-p">New Arrival</p>
                                                    </div>
                                                </div>

                                                <!-- CONTENT -->
                                                <div class="jst-pr-info-block">
                                                    <h5 class="jst-pr-headline text-truncate">
                                                        <a href="{{ route('product.details.show', $value->id) }}"
                                                            style="color:white; text-decoration:none;">
                                                            {{ $value->product_name }}
                                                        </a>
                                                    </h5>

                                                    <div class="jst-pr-action-row">

                                                        <!-- PRICE -->
                                                        <div class="jst-pr-val-stack">
                                                            @if ($product_price)
                                                                @php
                                                                    $price =
                                                                        $product_price->price -
                                                                        $product_price->price *
                                                                            ($value->discount / 100);
                                                                @endphp

                                                                <span class="jst-pr-active-price">
                                                                    Rs. {{ round($price) }}
                                                                </span>

                                                                @if ($value->discount)
                                                                    <span class="jst-pr-was-price">
                                                                        {{ $product_price->price }}
                                                                    </span>

                                                                    <span class="jst-pr-pct-tag">
                                                                        {{ round($value->discount) }}% Off
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>

                                                        <!-- BUTTON -->
                                                        @if ($product_price && $product_price->quantity > 0)
                                                            <a href="{{ route('product.details.show', $value->id) }}"
                                                                class="btn jst-pr-buy-btn">
                                                                Add to cart
                                                            </a>
                                                        @else
                                                            <span class="btn jst-pr-buy-btn1"
                                                                style="opacity:0.6; cursor:not-allowed;">
                                                                Out of stock
                                                            </span>
                                                        @endif

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            <!-- ================= BEST SELLING ================= -->
                            <div class="tab-pane fade" id="tab-best">
                                <div class="owl-carousel product-slider1">

                                    @php
                                        $products = App\Models\Product::where('deleted', 0)
                                            ->where('best_sellers', 1)
                                            ->where('status', 1)
                                            ->latest()
                                            ->get();
                                    @endphp

                                    @foreach ($products as $value)
                                        @php
                                            $product_price = App\Models\ProductDetail::where(
                                                'product_id',
                                                $value->id,
                                            )->first();
                                        @endphp

                                        <div class="item mx-1 mt-1">
                                            <div class="card jst-pr-card-outer off-cards">

                                                <!-- IMAGE -->
                                                <div class="jst-pr-media-frame">
                                                    <a href="{{ route('product.details.show', $value->id) }}">
                                                        <img loading="lazy"
                                                            src="{{ url('public/product_images/' . $value->product_img) }}"
                                                            alt="{{ $value->product_name }}">
                                                    </a>

                                                    <div class="jst-pr-badge-layer">
                                                        <img class="badge-imgg"
                                                            src="{{ url('assets/images/cart-badge.svg') }}"
                                                            alt="">
                                                        <p class="badge-p">Best Seller</p>
                                                    </div>
                                                </div>

                                                <!-- CONTENT -->
                                                <div class="jst-pr-info-block">
                                                    <h5 class="jst-pr-headline text-truncate">
                                                        <a href="{{ route('product.details.show', $value->id) }}"
                                                            style="color:white; text-decoration:none;">
                                                            {{ $value->product_name }}
                                                        </a>
                                                    </h5>

                                                    <div class="jst-pr-action-row">

                                                        <!-- PRICE -->
                                                        <div class="jst-pr-val-stack">
                                                            @if ($product_price)
                                                                @php
                                                                    $price =
                                                                        $product_price->price -
                                                                        $product_price->price *
                                                                            ($value->discount / 100);
                                                                @endphp

                                                                <span class="jst-pr-active-price">
                                                                    Rs. {{ round($price) }}
                                                                </span>

                                                                @if ($value->discount)
                                                                    <span class="jst-pr-was-price">
                                                                        {{ $product_price->price }}
                                                                    </span>

                                                                    <span class="jst-pr-pct-tag">
                                                                        {{ round($value->discount) }}% Off
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>

                                                        <!-- BUTTON -->
                                                        @if ($product_price && $product_price->quantity > 0)
                                                            <a href="{{ route('product.details.show', $value->id) }}"
                                                                class="btn jst-pr-buy-btn">
                                                                Add to cart
                                                            </a>
                                                        @else
                                                            <span class="btn jst-pr-buy-btn1"
                                                                style="opacity:0.6; cursor:not-allowed;">
                                                                Out of stock
                                                            </span>
                                                        @endif

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            <!-- ================= TRENDING ================= -->
                            <div class="tab-pane fade" id="tab-trending">
                                <div class="owl-carousel product-slider1">

                                    @php
                                        $products = App\Models\Product::where('deleted', 0)
                                            ->where('trending_tshirt', 1)
                                            ->where('status', 1)
                                            ->latest()
                                            ->get();
                                    @endphp

                                    @foreach ($products as $value)
                                        @php
                                            $product_price = App\Models\ProductDetail::where(
                                                'product_id',
                                                $value->id,
                                            )->first();
                                        @endphp

                                       <div class="item mx-1 mt-1">
                                            <div class="card jst-pr-card-outer off-cards">

                                                <!-- IMAGE -->
                                                <div class="jst-pr-media-frame">
                                                    <a href="{{ route('product.details.show', $value->id) }}">
                                                        <img loading="lazy"
                                                            src="{{ url('public/product_images/' . $value->product_img) }}"
                                                            alt="{{ $value->product_name }}">
                                                    </a>

                                                    <div class="jst-pr-badge-layer">
                                                        <img class="badge-imgg"
                                                            src="{{ url('assets/images/cart-badge.svg') }}" alt="">
                                                        <p class="badge-p">Trending</p>
                                                    </div>
                                                </div>

                                                <!-- CONTENT -->
                                                <div class="jst-pr-info-block">
                                                    <h5 class="jst-pr-headline text-truncate">
                                                        <a href="{{ route('product.details.show', $value->id) }}"
                                                            style="color:white; text-decoration:none;">
                                                            {{ $value->product_name }}
                                                        </a>
                                                    </h5>

                                                    <div class="jst-pr-action-row">

                                                        <!-- PRICE -->
                                                        <div class="jst-pr-val-stack">
                                                            @if ($product_price)
                                                                @php
                                                                    $price =
                                                                        $product_price->price -
                                                                        $product_price->price *
                                                                            ($value->discount / 100);
                                                                @endphp

                                                                <span class="jst-pr-active-price">
                                                                    Rs. {{ round($price) }}
                                                                </span>

                                                                @if ($value->discount)
                                                                    <span class="jst-pr-was-price">
                                                                        {{ $product_price->price }}
                                                                    </span>

                                                                    <span class="jst-pr-pct-tag">
                                                                        {{ round($value->discount) }}% Off
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>

                                                        <!-- BUTTON -->
                                                        @if ($product_price && $product_price->quantity > 0)
                                                            <a href="{{ route('product.details.show', $value->id) }}"
                                                                class="btn jst-pr-buy-btn">
                                                                Add to cart
                                                            </a>
                                                        @else
                                                            <span class="btn jst-pr-buy-btn1"
                                                                style="opacity:0.6; cursor:not-allowed;">
                                                                Out of stock
                                                            </span>
                                                        @endif

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

    <!--<section class="divider_section animate__animated animate__fadeIn" data-delay="2s">-->
    <!--    <div class="container">-->
    <!--        <div class="row gy-4">-->
    <!--            <div class="col-lg-12">-->
    <!--                <div class="card mens_collection">-->
    <!--                    <div class="row g-0">-->
    <!--                        <div class="col-md-6 order-md-0 order-1">-->
    <!--                            <div class="card_body">-->
    <!--                                <div>-->
    <!--                                    <h4 class="card_title" style="font-size: 40px;">Your Daily Skin Luxuries</h4>-->
    <!--                                    <p class="card_text" style="font-size: 20px;">Minimal steps. Maximum glow</p>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <div class="col-md-6 order-md-1 order-0">-->
    <!--                            <div class="card_img">-->
    <!--                                <img src="<?php echo url(''); ?>/assets/images/canntum_banner.png" alt="...">-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
     @php
        $offer = \App\Models\OfferImage::latest()->first();
    @endphp

    <section class="divider_section animate__animated animate__fadeIn" data-delay="2s">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="card mens_collection">
                        <div class="row g-0">
                            <div class="col-md-12 order-md-1 order-0">
                                <div class="card_img">
                                    @if ($offer && $offer->image)
                                        <img src="{{ asset('public/offer_images/' . $offer->image) }}" alt="Offer Image">
                                    @else
                                        <img src="<?php echo url(''); ?>/assets/images/canntum_banner.png" alt="Default Offer Image">
                                    @endif
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
            <h5 class="product_title animate__animated animate__fadeInLeft">Trending Products</h5>

            <div class="mt-5">
                <div class="product_collections animate__animated animate__fadeIn">
                    <div class="owl-carousel product-slider">

                        @php
                            $product_unique_img = App\Models\Product::where('deleted', 0)
                                ->where('trending_tshirt', 1)
                                ->where('status', 1)
                                ->latest()
                                ->get();
                        @endphp

                        @foreach ($product_unique_img as $value)
                            @php
                                $product_price = App\Models\ProductDetail::where('product_id', $value->id)->first();

                                $price = 0;
                                if ($product_price) {
                                    $price = $product_price->price - $product_price->price * ($value->discount / 100);
                                }
                            @endphp

                            <div class="item">
                                <div class="card jst-pr-card-outer">

                                    <!-- IMAGE -->
                                    <div class="jst-pr-media-frame">
                                        <a href="{{ route('product.details.show', $value->id) }}">
                                            <img loading="lazy"
                                                src="{{ url('public/product_images/' . $value->product_img) }}"
                                                alt="{{ $value->product_name }}">
                                        </a>

                                        <div class="jst-pr-badge-layer">
                                            <img class="badge-imgg" src="{{ url('assets/images/cart-badge.svg') }}"
                                                width="16" alt="">
                                            <p class="badge-p">Trending</p>
                                        </div>
                                    </div>

                                    <!-- CONTENT -->
                                    <div class="jst-pr-info-block">

                                        <!-- TITLE -->
                                        <h5 class="jst-pr-headline text-truncate">
                                            <a href="{{ route('product.details.show', $value->id) }}"
                                                style="color:white; text-decoration:none;">
                                                {{ $value->product_name }}
                                            </a>
                                        </h5>

                                        <!-- FOOTER -->
                                        <div class="jst-pr-action-row">

                                            <!-- PRICE -->
                                            <div class="jst-pr-val-stack">
                                                @if ($product_price)
                                                    @php
                                                        $discounted =
                                                            $product_price->price -
                                                            $product_price->price * ($value->discount / 100);
                                                    @endphp

                                                    <span class="jst-pr-active-price">
                                                        Rs. {{ round($discounted) }}
                                                    </span>

                                                    @if ($value->discount)
                                                        <span class="jst-pr-was-price">
                                                            {{ $product_price->price }}
                                                        </span>

                                                        <span class="jst-pr-pct-tag">
                                                            {{ round($value->discount) }}% Off
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>

                                            <!-- BUTTON -->
                                            @if ($product_price && $product_price->quantity > 0)
                                                <a href="{{ route('product.details.show', $value->id) }}"
                                                    class="btn jst-pr-buy-btn">
                                                    Add to cart
                                                </a>
                                            @else
                                                <span class="btn jst-pr-buy-btn1"
                                                    style="cursor:not-allowed; opacity:0.6;">
                                                    Out of stock
                                                </span>
                                            @endif

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

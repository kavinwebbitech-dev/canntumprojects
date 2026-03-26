{{-- <div class="top-header web-view">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="top-header-left">
                    <ul>
                        <li><a href="{{ route('contact')}}" class="top-header-link">Contact Us </a></li>
                        <li><a href="{{ route('join_us')}}" class="top-header-link">Join us </a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="top-header-right">
                    <ul class="desc-link">
                        <li><a class="top-header-link">Upto 30% discount on all the products!</a></li>
                    </ul>


                </div>
            </div>
            <!--<div class="col-md-2">-->
            <!--    <ul class="social-icon">-->
            <!--        <li><a href="https://www.youtube.com/@blendeofficial"><i class="fa-brands fa-youtube"></i></a></li>-->
            <!--        <li><a href="https://www.instagram.com/myblendeofficial/"><i class="fa-brands fa-instagram"></i></a></li>-->
            <!--        <li><a href="https://x.com/officialblende"><i class="fa-brands fa-x"></i></a></li>-->
            <!--        <li><a href="https://www.linkedin.com/company/myblende"><i class="fa-brands fa-linkedin"></i></a></li>-->
            <!--    </ul>-->
            <!--</div>-->
        </div>
    </div>
</div> --}}
<style>
    .search-btn i {
        transition: transform 0.3s ease;
    }

    .search-btn:hover i {
        transform: scale(1.3);
        color: black;
        /* border:none !important; */
        /* outline: none */
    }
</style>
<div class="search-header">
    <div class="container">
        <div class="row gx-2 gy-3 align-items-center">
            <div class="col-lg-2 col-3 order-0 order-lg-0">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ url('/') }}/assets/images/canntum.svg" alt=""
                            style="width:280px; height:auto;">
                    </a>
                </div>

            </div>
            <div class="col-lg-1">

            </div>

            <div class="col-lg-8 col-12 order-2 order-lg-1 text-center">
                <form id="hint-search-form" action="{{ route('search.product') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control ms-2" name="search_text" value="{{ request('search_text') }}"
                            placeholder="Search here" aria-label="What are you Looking For ?"
                            aria-describedby="button-addon2">
                        <button class="btn search-btn" type="submit" id="button-addon2" style="border: none;"><i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
               
            </div>
            {{-- <div class="col-lg-4 col-9 order-1 order-lg-2">
                <div class="main-header-contact">
                    <ul>

                        <li>
                            <div class="icon">
                                <img src="<?php echo url(''); ?>/assets/images/call.svg" alt="">
                            </div>
                            <div class="text">
                                <p class="contact-text">Contact Any Time </p>
                                <a href="" class="phone-number">+91 96005 50730</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<div class="main-header-bottom web-view p-10">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <span class="nav-text">Home</span>
                        </a>

                    </li>

                    @php $categories = App\Models\ProductCategory::get(); @endphp

                    @foreach ($categories as $category)
                        <li class="nav-item dropdown">
                            <a class="nav-link {{ request()->routeIs('category.list') && request()->route('id') == $category->id ? 'active' : '' }}"
                                href="{{ route('category.list', ['id' => $category->id]) }}">
                                <span class="nav-text">{{ $category->name }}</span>
                                {{-- <span class="dropdown-toggle" role="button" data-bs-toggle="dropdown"></span> --}}
                            </a>

                            @php $subcategories = App\Models\ProductSubCategory::where('category_id',$category->id)->get(); @endphp
                            @if ($subcategories->count() > 0)
                                <ul class="dropdown-menu">
                                    @foreach ($subcategories as $subcategory)
                                        <li><a class="dropdown-item"
                                                href="{{ route('subcategory.list', $subcategory->id) }}">{{ $subcategory->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach

                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact Us</a>
                    </li> --}}
                </ul>
                <div class="d-flex ms-auto">
                    <ul class="login-list">
                        <li>
                            @if (Auth::check())
                                <a href="{{ route('user.dashboard') }}">
                                    <img src="<?php echo url(''); ?>/assets/images/sign-in.svg" alt="">My account
                                </a>
                            @else
                                <a href="{{ route('user.login') }}">
                                    <img src="<?php echo url(''); ?>/assets/images/sign-in.svg" alt="">Sign In
                                </a>
                            @endif
                        </li>
                        <li>
                            <a href="{{ route('show.wishlist.list') }}">
                                <div class="icon">
                                    <div class="count">
                                        @if (session('wishlist'))
                                            @php $wishlist = count(session('wishlist')); @endphp
                                            {{ $wishlist }}
                                        @else
                                            {{ '0' }}
                                        @endif
                                    </div>

                                </div>
                                <img src="<?php echo url(''); ?>/assets/images/favorites.svg" alt="">Wishlist
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('show.cart.table') }}">
                                <div class="icon">
                                    <div class="count">
                                        @if (session('cart'))
                                            @php $count = count(session('cart')); @endphp
                                            {{ $count }}
                                        @else
                                            {{ '0' }}
                                        @endif
                                    </div>

                                </div>
                                <img src="<?php echo url(''); ?>/assets/images/cart.svg" alt="">Cart
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

<div class="mobile-header mobile-view">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                <a href="{{ route('home') }}" class="logo"><img src="{{ url('assets/images/canntum.svg') }}" /></a>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>

        </div>

        <div class="offcanvas-body mean-container">
            <div class="main-menu ">
                <nav class="mean-menulist">
                    <ul>
                        <li>
                            <a class="{{ request()->routeIs('home') ? 'active' : '' }}"
                                href="{{ route('home') }}">Home</a>
                        </li>

                        @php $categories = App\Models\ProductCategory::get(); @endphp

                        @foreach ($categories as $category)
                            <li class="nav-item dropdown">
                                <a class="nav-link {{ request()->routeIs('category.list') && request()->route('id') == $category->id ? 'active' : '' }}"
                                    href="{{ route('category.list', ['id' => $category->id]) }}">
                                    {{ $category->name }} <span class="dropdown-toggle" role="button"
                                        data-bs-toggle="dropdown"></span>
                                </a>
                                @php $subcategories = App\Models\ProductSubCategory::where('category_id',$category->id)->get(); @endphp
                                @if ($subcategories->count() > 0)
                                    <ul class="dropdown-menu">
                                        @foreach ($subcategories as $subcategory)
                                            <li><a class="dropdown-item"
                                                    href="{{ route('subcategory.list', $subcategory->id) }}">{{ $subcategory->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach

                        {{-- <li>
                            <a href="{{ route('contact') }}">Contact Us</a>
                        </li> --}}

                    </ul>

                </nav>

            </div>

        </div>

    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-2">
                <p class="text-start">
                    <a class="btn toggler" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                        aria-controls="offcanvasExample">
                        <i class="bi bi-filter-left"></i>
                    </a>
                </p>

            </div>
            <div class="col-10">
                <ul class="login-list">
                    <li>
                        @if (Auth::check())
                            <a href="{{ route('user.dashboard') }}">
                                <img src="<?php echo url(''); ?>/assets/images/sign-in.svg" alt="">My account
                            </a>
                        @else
                            <a href="{{ route('user.login') }}">
                                <img src="<?php echo url(''); ?>/assets/images/sign-in.svg" alt="">Sign In
                            </a>
                        @endif
                    </li>
                    <li>
                        <a href="{{ route('show.wishlist.list') }}">
                            <div class="icon">
                                <span class="count">
                                    @if (session('wishlist'))
                                        @php $wishlist = count(session('wishlist')); @endphp
                                        {{ $wishlist }}
                                    @else
                                        {{ '0' }}
                                    @endif
                                </span>

                            </div>
                            <img src="<?php echo url(''); ?>/assets/images/favorites.svg" alt="">Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('show.cart.table') }}">
                            <div class="icon">
                                <span class="count">
                                    @if (session('cart'))
                                        @php $count = count(session('cart')); @endphp
                                        {{ $count }}
                                    @else
                                        {{ '0' }}
                                    @endif
                                </span>

                            </div>
                            <img src="<?php echo url(''); ?>/assets/images/cart.svg" alt="">Cart
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.main-header-bottom .navbar .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.main-header-bottom .navbar .nav-link')
                .forEach(nav => nav.classList.remove('active'));

            this.classList.add('active');
            this.blur();
        });
    });
</script>

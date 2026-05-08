@include('frontend.include.header')

<body>
    <style>
        html,
        body {
            overflow-x: hidden;
        }

        .main-content {
            overflow: hidden !important;
        }

        .banner-carousel .owl-nav {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .banner-carousel:hover .owl-nav {
            opacity: 1;
        }

        .banner-carousel .owl-prev,
        .banner-carousel .owl-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        .banner-carousel .owl-prev {
            left: 10px;

        }

        .banner-carousel .owl-next {
            right: 10px;
        }

        @media (max-width: 767px) {



            .product-slider .owl-nav button.owl-prev {
                left: 15px;
            }

            .product-slider .owl-nav button.owl-next {
                right: 15px;
            }


        }


        /* Animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Hide state */
        #site-preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        #site-preloader {
            position: fixed;
            inset: 0;
            display: flex;
            background: #001E40;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 3.5s ease, visibility 3.5s ease;
        }

        #site-preloader.hide {
            opacity: 0;
            visibility: hidden;
        }

        .preloader-content {
            position: relative;
            text-align: center;
            /* background: #ffff !important; */
            padding: 10px
        }

        .preloader-logo {
            /* background: rgb(242, 242, 243); */
            width: 320px;
            height: auto;
            animation: bounceGlow 2s infinite ease-in-out;
        }

        .loader-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 140px;
            height: 140px;
            border: 3px solid rgb(248, 245, 245);
            border-top: 3px solid #001E40;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes bounceGlow {

            0%,
            100% {
                transform: translateY(0);
                filter: drop-shadow(0 0 0 rgba(0, 0, 0, 0));
            }

            50% {
                transform: translateY(-10px);
                filter: drop-shadow(0 0 12px rgba(0, 0, 0, 0.3));
            }
        }

         .custom-toast {
            width: 100%;
            /* white-space: nowrap; */
            max-width: 100%;
            background-color: #272727 !important;
            color: #fff !important;
            /* responsive */
        }
    </style>

    <!-- ==== PRELOADER START ==== -->
    @php
        if (!session()->has('count')) {
            session()->put('count', 1);
        }
    @endphp

    @if (session('count') == 1)
        <div id="site-preloader">

            <div class="preloader-content">
                <img src="{{ url('') }}/assets/images/banner_canntum.svg" alt="Loading..." class="preloader-logo">
            </div>

        </div>

        @php
            session()->put('count', 0);
        @endphp
    @endif

    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="bi bi-chevron-up"></i></button>

    @include('frontend.include.navbar')

    <div class="main-content">
        @yield('content')
        @if (session('success') || session('error'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index:9999">

                @if (session('success'))
                    <div id="successToast"
                        class="toast custom-toast align-items-center text-white bg-success border-0 mb-2">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div id="errorToast" class="toast custom-toast align-items-center text-white bg-danger border-0">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif

            </div>
        @endif
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                var successToast = document.getElementById('successToast');
                if (successToast) {
                    new bootstrap.Toast(successToast, {
                        delay: 3000
                    }).show();
                }

                var errorToast = document.getElementById('errorToast');
                if (errorToast) {
                    new bootstrap.Toast(errorToast, {
                        delay: 3000
                    }).show();
                }

            });
        </script>

    </div>


    @include('frontend.include.footer')

    <script type="text/javascript" src="<?php echo url(''); ?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo url(''); ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?php echo url(''); ?>/assets/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo url(''); ?>/assets/js/fancybox.min.js"></script>
    <script type="text/javascript" src="<?php echo url(''); ?>/assets/js/wow.min.js"></script>
    <script type="text/javascript" src="<?php echo url(''); ?>/assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo url(''); ?>/assets/js/jquery.meanmenu.min.js"></script>

    <script>
        $(".banner-carousel ").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: false,
            autoplayTimeout: 5000,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 1,
                },
                1000: {
                    items: 1,
                },
            },
        });

        $('.product-slider').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: false,
            smartSpeed: 1000,
            autoplayTimeout: 4000,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 4,
                },
            },
        });

        $('.product-slider1').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: false,
            autoWidth: true,
            smartSpeed: 1000,
            autoplayTimeout: 4000,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1.7
                },
                1000: {
                    items: 3.2
                }
            }
        });

        $(".testimonial-carousel").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: true,
            smartSpeed: 1000,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 1,
                },
                1000: {
                    items: 1,
                },
            },
        });

        $(".gallery-slider").owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            autoplay: true,
            center: true,
            smartSpeed: 1000,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 1.7,
                },
                600: {
                    items: 3.7,
                },
                1000: {
                    items: 5.7,
                },
            },
        });




        $(".owl-prev").html('<i class="fa-solid fa-angle-left"></i>');
        $(".owl-next").html('<i class="fa-solid fa-angle-right"></i>');

        $(".banner-carousel .owl-prev").html('<i class="fa-solid fa-angle-left"></i>');
        $(".banner-carousel .owl-next").html('<i class="fa-solid fa-angle-right"></i>');


        function counter(event) {
            var element = event.target;
            var items = event.item.count;
            var item = event.item.index + 1;

            if (item > items) {
                item = item - items
            }
            $('#counter').html("" + item + " /" + items)
        }
    </script>
    <script>
        window.addEventListener("load", function() {
            const preloader = document.getElementById("site-preloader");
            preloader.classList.add("hidden");
            setTimeout(() => preloader.style.display = "none", 400);
        });
    </script>
    <script>
        $('.mean-menulist').meanmenu({
            meanMenuContainer: '.mobile-menu',

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let wishlistBtn = document.getElementById("wishlist-Btn");

            wishlistBtn.addEventListener("click", function() {
                this.classList.toggle("active");
            });

        });
    </script>


</body>

</html>

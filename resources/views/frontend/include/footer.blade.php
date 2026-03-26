<footer class="footer" style=" padding: 100px 0px 20px 0px;">
    <div class="container">
        <div class="row"style=" padding: 10px 0px 80px 0px;">
            <div class="col-lg-4 col-md-6 order-1 order-lg-0">
                <div class="left_column">
                    <h5 class="footer_title">Contact With Us</h5>
                    <ul class="social-icon mt-5">
                        {{-- <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li> --}}
                        <li><a href="https://www.instagram.com/_canntum_?igsh=ZjNrMmVhbWdocmFy"><i class="fa-brands fa-instagram"></i></a></li>
                        <li><a href="https://share.google/EkTgvUewTWUSsKL93"><i class="fa-brands fa-x-twitter"></i></a>
                        </li>
                        {{-- <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li> --}}
                    </ul>
                </div>
            </div>
            {{-- <div class="col-lg-3 col-md-10 order-0 order-lg-1">
                <div class="center_column">
                    <h5 class="footer_title">Sign Up For Email Offers & Update</h5>
                    @if (session('message'))
                        <div class="text-success">{{ session('message') }}</div>
                    @endif
                    <form action="{{ route('newsletter.subscribe') }}" method="post" class="subscribe-form">
                        @csrf
                        <div class="input-group mt-5">
                            <input type="text" class="form-control" placeholder="Email" name="email"
                                aria-label="Recipient's username" aria-describedby="button-addon2"
                                style="color: white;" required>
                            <button class="btn  subscribe-btn" type="submit" id="button-addon2">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div> --}}
            <div class="col-lg-4 col-md-10 order-2 order-lg-2">
                <div class="center_column d-flex flex-column align-items-center justify-content-center mx-auto ">

                    <div class="footer-links-wrapper">
                        <h5 class="footer_titles text-start">Useful Links</h5>

                        <ul class="footer-bottom-list">
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                            {{-- <li><a href="{{ route('faq') }}">Frequently Asked Questions</a></li> --}}
                            <li><a href="{{ route('privacy_policy') }}">Privacy Policies</a></li>
                            <li><a href="{{ route('shipping_return') }}">Shipping & Return Policy</a></li>
                            <li><a href="{{ route('terms_condition') }}">Terms and Conditions</a></li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-6 order-3 order-lg-3">
                <div class="right_column">
                    <h5 class="footer_title">Secure Ordering & Transactions</h5>
                    <ul class="footer_pay_list mt-5">
                        <li><img src="<?php echo url(''); ?>/assets/images/american.svg"
                                    alt=""></li>
                        <li><img src="<?php echo url(''); ?>/assets/images/paypal.png"
                                    alt=""></li>
                        <li><img src="<?php echo url(''); ?>/assets/images/master.png"
                                    alt=""></li>
                        <li><img src="<?php echo url(''); ?>/assets/images/visa.png" alt=""></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom p-10">
            <div class="row gy-3 d-flex justify-content-center align-items-center">
                <div class="col-lg-6 d-flex justify-content-center align-items-center">
                    <p class="copyright-text">© Copyrights {{ date('Y') }}. All Rights Reserved by canntumemporium.
                    </p>
                </div>
                {{-- <div class="col-lg-7">
                        <ul class="footer-bottom-list">
                            <li><a href="{{ route('contact')}}">Contact Us</a></li>
                            <li><a href="{{ route('terms_condition')}}">Terms and Conditions</a></li>
                            <li><a href="{{ route('privacy_policy')}}">Privacy Policies</a></li>
                            <li><a href="{{route('faq')}}">Frequently Asked Questions</a></li>
                            <li><a href="{{ route('shipping_return')}}">Shipping & Return Policy </a></li>
                            <li><a href="{{ route('join_us')}}">Join us </a></li>
                        </ul>
                    </div> --}}
                <div class="col-lg-2 d-none">
                    <p class="copyright-text text-end">Designed By <a target="_blank"
                            href="https://webbitech.com">Webbitech.com</a></p>
                </div>
            </div>
        </div>
    </div>

</footer>
<style>
    @media (max-width: 767px) {
        .footer_titles {
            text-align: left !important;
        }
    }

    .footer-bottom-list li a {
        display: inline-block;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .footer-bottom-list li a:hover {
        transform: scale(1.08);
        /* color: #001E40; */
        color: white;
    }

    .social-icon li {
        list-style: none;
        display: inline-block;
        margin-right: 12px;
    }

    .social-icon li a {
        font-size: 22px;
        color: #fff;
        transition: transform 0.25s ease, color 0.25s ease, text-shadow 0.25s ease;
        display: inline-block;
    }

    .social-icon li a:hover {
        transform: scale(1.25);
        color: white;
        text-shadow: 0 0 10px rgba(97, 94, 154, 0.066);
    }
</style>

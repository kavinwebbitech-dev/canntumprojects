<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('meta_title', 'Canntum')</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="description" content="@yield('meta_description')" />
    <meta name="keywords" content="@yield('meta_keywords')">
    @yield('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="<?php echo url(''); ?>/assets/images/canntum_fav.png">
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/bootstrap-icons.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/owl.carousel.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/flaticon.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/animate.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/fancybox.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/meanmenu.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/owl.theme.default.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }
        (window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '2254295658373485');
        fbq('track', 'PageView');
    </script>
    <script type="text/javascript">
        (function(c, l, a, r, i, t, y) {
            c[a] = c[a] || function() {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "tf5p81vu2c");
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=2254295658373485&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
</head>

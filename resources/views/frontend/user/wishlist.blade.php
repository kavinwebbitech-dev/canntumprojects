@extends('frontend.layouts.app')
@section('content')

    <div class="page-banner">
        <div class="page-banner-content">
            <div class="container">
                <h1 class="page-banner-title">Whislist</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Whislist</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="profile-detail">
        <div class="container">
            <div class="row gy-4">
                @include('frontend.user.sidebar')
                <div class="col-lg-9 col-md-8">
                    <div class="profile-right">
                        <div class="edit-box">
                            <div class="edit-heading">
                                <div class="row gy-4 align-items-center">
                                    <div class="col-md-12">
                                        <h5 class="edit-title">Whislist</h5>
                                    </div>

                                </div>
                            </div>
                            <div class="over-auto">
                                <table class="table  ">

                                    <tbody>
                                        @if (session('wishlist'))
                                            @php
                                                $cart = session('cart', []);
                                            @endphp

                                            @foreach (session('wishlist') as $id => $details)
                                                <tr id="wishlist-item-{{ $id }}">
                                                    <th scope="row">
                                                        <div class="img-box">
                                                            <a href="{{ route('product.details.show', $id) }}">
                                                                <img src="{{ url('public/product_images/' . $details['product_img']) }}"
                                                                    alt="">
                                                            </a>
                                                        </div>
                                                    </th>
                                                    <td colspan="3">
                                                        <p>
                                                            <a href="{{ route('product.details.show', $id) }}"
                                                                class="title">{{ $details['product_name'] }}</a>
                                                        </p>
                                                    </td>
                                                    <td class="price">₹ {{ $details['offer_price'] }}</td>
                                                    <td>
                                                        <p>
                                                            <a class="btn common-btn move-to-cart"
                                                                data-id="{{ $id }}">Move to Cart</a>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="price">
                                                            <a class="close-icon remove-from-wishlist"
                                                                data-id="{{ $id }}"><i class="bi bi-x-lg"></i></a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif


                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection



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
                        $('#wishlist-item-' + productId).remove();
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        });

        $('.remove-from-wishlist').click(function(e) {
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
                        $('#wishlist-item-' + productId).remove();
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        });
    });
</script>

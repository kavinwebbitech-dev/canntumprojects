@extends('frontend.layouts.app')
@section('content')
    <style>
        .img-btn img {}

        .img-btn>input {
            display: none
        }

        .img-btn>img {
            cursor: pointer;
            border: 1px solid #b5b5b5;
            border-radius: 10px;
            width: 100%;
            height: 150px;
            margin-bottom: 20px;
            padding: 10px;
        }

        .img-btn>input:checked+img {

            border-radius: 10px;
            border: 1px solid #001E40;
            width: 100%;
            height: 180px;
            margin-bottom: 20px;
            padding: 10px;
        }

        .padd-both-30 {
            padding: 30px 0;
        }
    </style>

    <div class="padd-both-30">
        <div class="container padd-both-30">
            <div class="row justify-content-center">
                <div class="col-lg-2">
                    <label class="img-btn">
                        <input type="radio" name="payment_method" id="razorpay" value="razorpay" checked>
                        <img src="{{ url('public/payment/razorpay.png') }}">
                        <p class="text-center">Online Payment</p>
                    </label>
                </div>
                <div class="col-lg-2">
                    <label class="img-btn">
                        <input type="radio" name="payment_method" id="cod" value="cod">
                        <img src="{{ url('public/payment/cod.svg') }}">
                        <p class="text-center">Cash on delivery</p>
                    </label>
                </div>
            </div>
            <form id="paymentForm" action="{{ route('payment.process') }}" method="POST">
                @csrf
                <input type="hidden" name="shipping_address" value="{{ $address }}">
                <input type="hidden" name="total_amount" value="{{ $amount }}">
                <input type="hidden" name="shipping_charge" value="{{ $shippingCharge }}">
                <input type="hidden" name="gst" value="{{ $gst }}">
                <input type="hidden" name="coupon_discount" value="{{ $coupon_discount }}">
            </form>
        </div>
    </div>

    <p class="mt-3 mb-3 text-center">
        <button class="btn common_btn" onclick="submitForm()">Confirm</button>
    </p>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
        function submitForm() {
            // Get the selected payment method
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            // Create a hidden input for the payment method and append it to the form
            const paymentInput = document.createElement('input');
            paymentInput.type = 'hidden';
            paymentInput.name = 'payment_method';
            paymentInput.value = paymentMethod;

            const form = document.getElementById('paymentForm');
            form.appendChild(paymentInput);

            // Submit the form
            form.submit();
        }
    </script>
@endsection

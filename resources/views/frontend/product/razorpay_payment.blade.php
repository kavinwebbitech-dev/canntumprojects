<!-- resources/views/frontend/product/razorpay_payment.blade.php -->
@extends('frontend.layouts.app')

@section('content')
    <div class="container" style="text-align: center;padding: 100px;">
        <h2>Complete Payment</h2>
        <form id="razorpay-payment-form" action="{{ route('payment.success') }}" method="POST">
            @csrf
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $razorpayOrderId }}">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        </form>

        <button id="rzp-button1" class="btn btn-primary">Pay with Razorpay</button>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
 
    <script>
        var options = {
            "key": "{{ env('RAZORPAY_KEY') }}",
            "amount": "{{ $total_amount * 100 }}",
            "currency": "INR",
            "name": "CANNTUM",  
            "description": "Order Payment",
            "image": "{{ url('assets/images/canntum.png') }}",
            "order_id": "{{ $razorpayOrderId }}",
            "handler": function(response) {
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('razorpay-payment-form').submit();
            },
            "modal": {
                "ondismiss": function() {
                    // ✅ Redirect back to checkout if they cancel
                    window.location.href = "{{ route('product.proceed_to_checkout') }}";
                }
            },
            "prefill": {
                "name": "{{ auth()->user()->name }}",
                "email": "{{ auth()->user()->email }}"
            },
            "theme": {
                "color": "#F37254"
            }
        };

        // Auto-open the payment modal so the user doesn't have to click "Pay" again
        window.onload = function() {
            var rzp1 = new Razorpay(options);
            rzp1.open();
        };

        document.getElementById('rzp-button1').onclick = function(e) {
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        }
    </script>
@endsection

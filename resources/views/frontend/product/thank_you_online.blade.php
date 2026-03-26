
@extends('frontend.layouts.app')
@section('content')


<div class="page-banner">
<div class="container">
    <ul class="navigation-list">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li class="text-center">Order Confirmed</li>
    </ul>
</div>
</div>


<div class="h-75 py-5 d-flex justify-content-center align-items-center">
    <div>
        <div class="mb-4 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="75" height="75"
                fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </svg>
        </div>
        <div class="text-center">
            <h1>Order Confirmed !</h1>
            <p>Redirecting in <span id="countdown">5</span> seconds...</p>
            <a href="{{ route('home') }}" class="btn common-btn mt-4">Done</a>
        </div>
    </div>
</div> 



@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // JavaScript to handle the countdown and redirection
    document.addEventListener("DOMContentLoaded", function() {
        let countdown = 5;
        const countdownElement = document.getElementById("countdown");

        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(timer);
                // Replace the following line with your actual redirection logic
                window.location.href = `{{ route('home') }}`;
            }
        }, 1000);
    });

    // Example order and orderDetail data
    const order = { /* your order data */ };
    const orderDetail = { /* your orderDetail data */ };
</script>
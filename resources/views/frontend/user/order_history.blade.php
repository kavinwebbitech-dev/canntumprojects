@extends('frontend.layouts.app')
@section('content')
    <style>
        .review_btn {
            position: relative;
        }

        .badge-notify {
            position: absolute;
            top: 6px;
            right: 10px;
            width: 10px;
            height: 10px;
            background: #28a745;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(40, 167, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        .notify-dot {
            width: 10px;
            height: 10px;
            background: red;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            right: 3px;
            animation: boomPulse 1s infinite;
        }

        @keyframes boomPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 0, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
            }
        }

        .btnbg {
            background: #001e40 !important;
            color: white !important;
        }

        .btnbg:hover {
            background: white !important;
            color: #001e40 !important;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-responsive table {
            min-width: 1200px;
        }

        .table td,
        .table th {
            white-space: nowrap;
            vertical-align: middle;
            text-align: center;
        }

        .wrapper {
            margin: 0 auto;
            max-width: 960px;
            width: 100%;
        }

        .master {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding-top: 40px;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        h2 {
            line-height: 160%;
            margin-bottom: 20px;
            text-align: center;
        }

        .rating-component {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-bottom: 10px;
        }

        .rating-component .status-msg {
            margin-bottom: 10px;
            text-align: center;
        }

        .rating-component .status-msg strong {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .rating-component .stars-box {
            align-self: center;
            margin-bottom: 15px;
        }

        .rating-component .stars-box .star {
            color: #ccc;
            cursor: pointer;
        }

        .rating-component .stars-box .star.hover {
            color: #ff5a49;
        }

        .rating-component .stars-box .star.selected {
            color: #ff5a49;
        }

        .feedback-tags {
            min-height: 119px;
        }

        .feedback-tags .tags-container {
            display: none;
        }

        .feedback-tags .tags-container .question-tag {
            text-align: center;
            margin-bottom: 40px;
        }

        .feedback-tags .tags-box {
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .feedback-tags .tags-container .make-compliment {
            padding-bottom: 20px;
        }

        .feedback-tags .tags-container .make-compliment .compliment-container {
            align-items: center;
            color: #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .feedback-tags .tags-container .make-compliment .compliment-container .fa-smile-wink {
            color: #ff5a49;
            cursor: pointer;
            font-size: 40px;
            margin-top: 15px;
            animation-name: compliment;
            animation-duration: 2s;
            animation-iteration-count: 1;
        }

        .feedback-tags .tags-container .make-compliment .compliment-container .list-of-compliment {
            display: none;
            margin-top: 15px;
        }

        .feedback-tags .tag {
            border: 1px solid #ff5a49;
            border-radius: 5px;
            color: #ff5a49;
            cursor: pointer;
            margin-bottom: 10px;
            margin-left: 10px;
            padding: 10px;
        }

        .feedback-tags .tag.choosed {
            background-color: #ff5a49;
            color: #fff;
        }

        .list-of-compliment ul {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }

        .list-of-compliment ul li {
            align-items: center;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-bottom: 10px;
            margin-left: 20px;
            min-width: 90px;
        }

        .list-of-compliment ul li:first-child {
            margin-left: 0;
        }

        .list-of-compliment ul li .icon-compliment {
            align-items: center;
            border: 2px solid #ff5a49;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 70px;
            margin-bottom: 15px;
            overflow: hidden;
            padding: 0 10px;
            transition: 0.5s;
            width: 70px;
        }

        .list-of-compliment ul li .icon-compliment i {
            color: #ff5a49;
            font-size: 30px;
            transition: 0.5s;
        }

        .list-of-compliment ul li.actived .icon-compliment {
            background-color: #ff5a49;
            transition: 0.5s;
        }

        .list-of-compliment ul li.actived .icon-compliment i {
            color: #fff;
            transition: 0.5s;
        }

        .button-box .done {
            background-color: #ff5a49;
            border: 1px solid #ff5a49;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
            display: none;
            min-width: 100px;
            padding: 10px;
        }

        .button-box .done:disabled,
        .button-box .done[disabled] {
            border: 1px solid #ff9b95;
            background-color: #ff9b95;
            color: #fff;
            cursor: initial;
        }

        .submited-box {
            display: none;
            padding: 20px;
        }

        .submited-box .loader,
        .submited-box .success-message {
            display: none;
        }

        .submited-box .loader {
            border: 5px solid transparent;
            border-top: 5px solid #4dc7b7;
            border-bottom: 5px solid #ff5a49;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes compliment {
            1% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-30deg);
            }

            50% {
                transform: rotate(30deg);
            }

            75% {
                transform: rotate(-30deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .action-btns {
            min-width: 220px;
        }

        .action-btns .badge {
            font-size: 12px;
        }

        .order-btns .badge {
            font-size: 12px;
        }

        .auto-resize {
            min-height: 200px !important;
            min-width: 500px !important;
            resize: none;
            /* optional: disable manual resize */
            overflow-y: scroll;
        }
    </style>

    <section class="my_profile">
        <div class="container">
            <div class="row gx-2">
                @include('frontend.user.sidebar')
                <div class="col-lg-10 col-md-12">

                    @if (session('success'))
                        <div id="successMessage" class="alert alert-success">{{ session('success') }}</div>
                        <script>
                            setTimeout(function() {
                                document.getElementById('successMessage').style.display = 'none';
                            }, 5000);
                        </script>
                    @endif

                    @if (session('error'))
                        <div id="errorMessage" class="alert alert-danger">{{ session('error') }}</div>
                        <script>
                            setTimeout(function() {
                                document.getElementById('errorMessage').style.display = 'none';
                            }, 5000);
                        </script>
                    @endif

                    <div class="profile_right">
                        <div class="row align-items-center">
                            <div class="col-lg-4 col-md-6">
                                <h2 class="profile_title text-start" style="margin-bottom: -20px">Order History</h2>
                            </div>
                        </div>

                        <div class="order_history">
                            <div class="product_collections">
                                <div class="table-responsive" style="overflow-x:auto;">
                                    <table class="table table-bordered align-middle text-nowrap">
                                        <thead style="background-color: #001E40; color: #ffffff;">
                                            <tr>
                                                <th>S.No</th>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Product</th>
                                                <th>Payment Method</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Price (₹)</th>
                                                <th>Original Price (₹)</th>
                                                <th>No. of Products</th>
                                                <th>Total (₹)</th>
                                                <th>Shipping Status</th>
                                                <th>Actions</th>
                                                <th>Order Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if ($orders->isNotEmpty())
                                                @foreach ($orders as $index => $order)
                                                    @php
                                                        $orderDetails = App\Models\OrderDetail::where(
                                                            'order_id',
                                                            $order->id,
                                                        )->get();
                                                        $firstDetail = $orderDetails->first();
                                                        $product = $firstDetail
                                                            ? App\Models\Product::find($firstDetail->product_id)
                                                            : null;

                                                        $price = $firstDetail ? $firstDetail->offer_price : 0;
                                                        $originalPrice = $product ? $product->orginal_rate : 0;
                                               
                                                        $totalQty = $orderDetails->sum('quantity');

                                                       
                                                        $grandTotal = 0;
                                                        foreach ($orderDetails as $detail) {
                                                            $itemSubtotal = $detail->offer_price * $detail->quantity;
                                                            // $itemGst = $itemSubtotal * ($detail->product_gst / 100);
                                                            $grandTotal += $itemSubtotal;
                                                        }

                                                        $shippingCharge = $order->shipping_charge ?? 0;
                                                        $couponDiscount = $order->coupon_discount ?? 0;
                                                        $grandTotal = $grandTotal  + $shippingCharge - $couponDiscount;

                                                        switch ($order->shipping_status) {
                                                            case 1:
                                                                $statusText = 'Order Received';
                                                                break;
                                                            case 2:
                                                                $statusText = 'Shipped';
                                                                break;
                                                            case 3:
                                                                $statusText = 'Out for Delivery';
                                                                break;
                                                            case 4:
                                                                $statusText = 'Delivered';
                                                                break;
                                                            default:
                                                                $statusText = 'Pending';
                                                                break;
                                                        }

                                                        $review = $product
                                                            ? App\Models\Review::where('order_id', $order->id)
                                                                ->where('product_id', $product->id)
                                                                ->where('user_id', auth()->id())
                                                                ->first()
                                                            : null;
                                                    @endphp

                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $order->payment_order_id }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                                        </td>

                                                        <td>
                                                            @php
                                                                if (
                                                                    $firstDetail &&
                                                                    !empty($firstDetail->selected_image)
                                                                ) {
                                                                    // ✅ Best: directly stored image filename (new orders)
                                                                    $displayImage = $firstDetail->selected_image;
                                                                } elseif (
                                                                    $firstDetail &&
                                                                    $firstDetail->image_index !== null
                                                                ) {
                                                                    // ⚡ Fallback: try image_index from current variant (old orders)
                                                                    $productDetail = App\Models\ProductDetail::find(
                                                                        $firstDetail->product_detail_id,
                                                                    );
                                                                    $variantImages =
                                                                        $productDetail && isset($productDetail->images)
                                                                            ? json_decode($productDetail->images, true)
                                                                            : [];
                                                                    $imageIndex = $firstDetail->image_index ?? 0;
                                                                    $displayImage =
                                                                        !empty($variantImages) &&
                                                                        isset($variantImages[$imageIndex])
                                                                            ? $variantImages[$imageIndex]
                                                                            : $product->product_img ?? null;
                                                                } else {
                                                                    // 🔄 Last resort: product main image
                                                                    $displayImage = $product->product_img ?? null;
                                                                }
                                                            @endphp
                                                            @if ($displayImage)
                                                                @php
                                                                    $imgUrl = file_exists(
                                                                        public_path('variant_images/' . $displayImage),
                                                                    )
                                                                        ? url('public/variant_images/' . $displayImage)
                                                                        : url('public/product_images/' . $displayImage);
                                                                @endphp
                                                                <img src="{{ $imgUrl }}" width="60" height="60"
                                                                    style="object-fit:cover;">
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ strtoupper($order->payment_method) }}</td>
                                                        <td>
                                                            @if ($firstDetail && $firstDetail->color_id)
                                                                @php $color = App\Models\Color::find($firstDetail->color_id); @endphp
                                                                {{ $color->color ?? '-' }}
                                                            @else
                                                                {{ $firstDetail->color ?? '-' }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($firstDetail && $firstDetail->size_id)
                                                                @php $size = App\Models\Size::find($firstDetail->size_id); @endphp
                                                                {{ $size->name ?? '-' }}
                                                            @else
                                                                {{ $firstDetail->size ?? '-' }}
                                                            @endif
                                                        </td>

                                                        <td class="fw-bold text-danger">
                                                            ₹ {{ number_format($price, 0) }}
                                                        </td>

                                                        <td>
                                                            @if ($originalPrice)
                                                                <span class="fw-bold" style="color:#999;">
                                                                    ₹ {{ number_format($originalPrice, 0) }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        <td>{{ $totalQty }}</td>

                                                        
                                                        <td class="fw-bold">
                                                            ₹ {{ number_format($grandTotal, 0) }}
                                                        </td>
                                                        <td>{{ $statusText }}</td>

                                                        <td>
                                                            <div
                                                                class="d-flex align-items-center justify-content-center gap-2 flex-wrap action-btns">
                                                                <a href="{{ route('user.order.details', $order->id) }}"
                                                                    class="btn btnbg btn-sm">Order Details</a>

                                                                {{-- <a href="javascript:void(0)"
                                                                    onclick="openEnquiryModal({{ $order->id }})"
                                                                    class="btn btnbg btn-sm position-relative enquiry-btn">
                                                                    Enquiry
                                                                    @if ($order->complaint && $order->complaint->reply && !$order->complaint->is_read)
                                                                        <span class="notify-dot"></span>
                                                                        <input type="hidden" class="has-reply"
                                                                            value="1">
                                                                    @endif
                                                                </a> --}}

                                                                @if (!$review && $order->shipping_status == 4)
                                                                    <a data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                                        data-order-id="{{ $order->id }}"
                                                                        class="btn review-btn write-review-btn btnbg btn-sm">
                                                                        <i class="bi bi-pencil me-1"></i>Review
                                                                    </a>
                                                                @elseif($review)
                                                                    <span class="badge bg-success px-2 py-2">Reviewed</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td class="order-btns">
                                                            @if ($order->order_status == 2)
                                                                <span class="badge bg-danger px-2 py-2">Cancelled</span>
                                                            @elseif($order->order_status == 3)
                                                                <span class="badge bg-warning px-2 py-2">Return
                                                                    Requested</span>
                                                            @elseif($order->order_status == 4)
                                                                <span class="badge bg-warning px-2 py-2">Returned</span>
                                                            @elseif($order->shipping_status < 2 && $order->order_status < 2)
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="openCancel({{ $order->id }})">Cancel</button>
                                                            @elseif($order->shipping_status == 4 && $order->order_status == 1)
                                                                <button class="btn btnbg btn-sm"
                                                                    onclick="openReturn({{ $order->id }}, 'return')">Return</button>
                                                            @else
                                                                <span class="badge bg-secondary px-3 py-2">Processing</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="13" class="text-center">No orders found</td>
                                                </tr>
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
    </section>

    {{-- Cancel Modal --}}
    <div class="modal fade" id="statusModalresornot" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="cancelForm" method="POST" action="{{ route('user.order.status') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Cancel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>Are you sure you want to cancel this order?</p>
                        <input type="hidden" name="order_id" id="status_order_id">
                        <input type="hidden" name="type" id="status_type">
                        <input type="hidden" name="remark" value="Order cancelled by user">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="confirmCancel()">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Return Modal --}}
    {{-- Return Modal --}}
    <div class="modal fade" id="statusModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('user.order.status') }}">
                @csrf
                {{-- ✅ Unique IDs to avoid conflict with Cancel modal --}}
                <input type="hidden" name="order_id" id="return_order_id">
                <input type="hidden" name="type" id="return_type">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Return Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Return Reason</label>
                        <textarea name="remark" class="form-control" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Review Modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Write a Review</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="review-form" action="{{ route('add.review') }}" method="post">
                        @csrf
                        <input type="hidden" name="order_id" id="order-id" value="">
                        <div class="wrapper">
                            <div class="master">
                                <h1>Review And rating</h1>
                                <h2>How was your experience about our product?</h2>
                                <div class="rating-component">
                                    <div class="status-msg">
                                        <label><input class="rating_msg" type="hidden" name="rating_msg"
                                                value="" /></label>
                                    </div>
                                    <div class="stars-box">
                                        <i class="star fa fa-star" title="1 star" data-message="Poor"
                                            data-value="1"></i>
                                        <i class="star fa fa-star" title="2 stars" data-message="Too bad"
                                            data-value="2"></i>
                                        <i class="star fa fa-star" title="3 stars" data-message="Average quality"
                                            data-value="3"></i>
                                        <i class="star fa fa-star" title="4 stars" data-message="Nice"
                                            data-value="4"></i>
                                        <i class="star fa fa-star" title="5 stars" data-message="Very good quality"
                                            data-value="5"></i>
                                    </div>
                                    <div class="starrate">
                                        <label><input class="ratevalue" type="hidden" name="rate_value"
                                                value="" /></label>
                                    </div>
                                </div>
                                <div class="feedback-tags">
                                    <div class="tags-container" data-tag-set="1">
                                        <div class="question-tag">Why was your experience so bad?</div>
                                    </div>
                                    <div class="tags-container" data-tag-set="2">
                                        <div class="question-tag">Why was your experience so bad?</div>
                                    </div>
                                    <div class="tags-container" data-tag-set="3">
                                        <div class="question-tag">Why was your average rating experience?</div>
                                    </div>
                                    <div class="tags-container" data-tag-set="4">
                                        <div class="question-tag">Why was your experience good?</div>
                                    </div>
                                    <div class="tags-container" data-tag-set="5">
                                        <div class="make-compliment">
                                            <div class="compliment-container">
                                                Give a compliment
                                                <i class="far fa-smile-wink"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tags-box">
                                        <textarea class="tag form-control auto-resize" name="comment" placeholder="Please enter your review"></textarea>
                                    </div>
                                </div>
                                <div class="button-box">
                                    <input type="submit" class="done btn btn-warning" disabled="disabled"
                                        value="Add review" />
                                </div>
                                <div class="submited-box">
                                    <div class="loader"></div>
                                    <div class="success-message">Thank you!</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Enquiry Modal --}}
    <div class="modal fade" id="enquiryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Enquiry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="chatMessages"
                        style="max-height:400px; overflow-y:auto; padding:15px; background:#f5f5f5; border-radius:10px;">
                        <p class="text-center text-muted">Loading...</p>
                    </div>
                    <hr>
                    <form id="sendEnquiryForm">
                        @csrf
                        <input type="hidden" name="order_id" id="enquiry_order_id">
                        <label class="fw-bold">Send Message</label>
                        <textarea class="form-control" name="remark" id="userRemark" required></textarea>
                        <button class="btn btn-primary mt-3 w-100">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>
        function openCancel(id) {
            document.getElementById('status_order_id').value = id;
            document.getElementById('status_type').value = 'cancel';
            new bootstrap.Modal(document.getElementById('statusModalresornot')).show();
        }

        function confirmCancel() {
            document.getElementById('cancelForm').submit();
        }

        function openReturn(id) {
            // ✅ Use unique IDs for return modal inputs
            document.getElementById('return_order_id').value = id;
            document.getElementById('return_type').value = 'return';
            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }

        function openEnquiryModal(orderId) {
            document.getElementById('enquiry_order_id').value = orderId;
            loadMessages(orderId);
            const badge = document.querySelector(`a[onclick="openEnquiryModal(${orderId})"] .badge-notify`);
            if (badge) badge.remove();
            new bootstrap.Modal(document.getElementById('enquiryModal')).show();
        }

        const BASE_URL = "{{ url('') }}";

        function loadMessages(orderId) {
            fetch(`${BASE_URL}/user/enquiry/messages/${orderId}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('chatMessages').innerHTML = html;
                });
        }

        document.getElementById('sendEnquiryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = new FormData(this);
            fetch("{{ route('user.order.enquiry.store') }}", {
                    method: "POST",
                    body: form
                })
                .then(() => {
                    loadMessages(document.getElementById('enquiry_order_id').value);
                    document.getElementById('userRemark').value = "";
                });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var reviewButtons = document.querySelectorAll('.write-review-btn');
            var orderIdInput = document.getElementById('order-id');

            reviewButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    orderIdInput.value = this.getAttribute('data-order-id');
                });
            });

            $(".rating-component .star").on("mouseover", function() {
                var onStar = parseInt($(this).data("value"), 10);
                $(this).parent().children("i.star").each(function(e) {
                    e < onStar ? $(this).addClass("hover") : $(this).removeClass("hover");
                });
            }).on("mouseout", function() {
                $(this).parent().children("i.star").each(function() {
                    $(this).removeClass("hover");
                });
            });

            $(".rating-component .stars-box .star").on("click", function() {
                var onStar = parseInt($(this).data("value"), 10);
                var stars = $(this).parent().children("i.star");
                var ratingMessage = $(this).data("message");

                $('.rating-component .starrate .ratevalue').val(onStar);
                $(".fa-smile-wink").show();
                $(".button-box .done").show().removeAttr("disabled");

                stars.removeClass("selected");
                for (var i = 0; i < onStar; i++) {
                    $(stars[i]).addClass("selected");
                }

                $(".status-msg .rating_msg").val(ratingMessage);
                $(".status-msg").html(ratingMessage);
                $("[data-tag-set]").hide();
                $("[data-tag-set=" + onStar + "]").show();
            });

            $(".feedback-tags .tag").on("click", function() {
                $(this).toggleClass("choosed");
            });

            $(".compliment-container .fa-smile-wink").on("click", function() {
                $(this).fadeOut("slow", function() {
                    $(".list-of-compliment").fadeIn();
                });
            });

            $(".done").on("click", function() {
                $(".rating-component").hide();
                $(".feedback-tags").show();
                $(".button-box").hide();
                $(".submited-box").show();
                $(".submited-box .loader").show();
                setTimeout(function() {
                    $(".submited-box .loader").hide();
                    $(".submited-box .success-message").show();
                }, 1500);
            });
        });
    </script>

    <script>
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection

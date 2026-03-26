@extends('admin.index')
@section('admin')
    <div class="col-12 col-md-6 col-lg-4">
        @if (session('success'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
                <div id="successToast" class="toast custom-toast align-items-center text-white bg-success border-0"
                    role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var toastEl = document.getElementById('successToast');
                    var toast = new bootstrap.Toast(toastEl, {
                        delay: 3000
                    }); // auto-hide after 3s
                    toast.show();
                });
            </script>
        @endif
        <p>Welcome Admin!</p>
    </div>
    <style>
        .custom-toast {
            width: 100%;
            /* set your width */
            max-width: 100%;
            /* responsive */
        }

        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            flex: 1 1 200px;
            /* responsive width */
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 25px;
            border-radius: 20px;
            /* slightly rounded */
            background-color: #001E40;
            /* dark navy blue */
            color: #fff;
            font-weight: 600;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card i {
            font-size: 30px;
            opacity: 0.8;
            margin-right: 10px;
        }

        .badge {
            background-color: #ff4d4f;
            border-radius: 12px;
            padding: 3px 10px;
            font-size: 14px;
            font-weight: 500;
        }

        .dashboard-card span {
            display: flex;
            align-items: center;
        }
    </style>

    <div class="dashboard-cards">
        <a href="{{ route('admin.users') }}" class="dashboard-card">
            <span><i class="fa fa-users"></i> Users</span>
            @if ($newUsersCount > 0)
                <span class="badge">{{ $newUsersCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.product') }}" class="dashboard-card">
            <span><i class="fa fa-box"></i> Product</span>
            @if ($productCount > 0)
                <span class="badge">{{ $productCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.order.list') }}" class="dashboard-card">
            <span><i class="fa fa-file-alt"></i> Orders</span>
            @if ($pendingOrdersCount > 0)
                <span class="badge">{{ $pendingOrdersCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.complaints.index') }}" class="dashboard-card">
            <span><i class="fa fa-exclamation-circle"></i> Complaints</span>
            @if ($newComplaintsCount > 0)
                <span class="badge">{{ $newComplaintsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.coupon_code') }}" class="dashboard-card">
            <span><i class="fa fa-ticket-alt"></i> Coupon</span>
            @if ($couponCount > 0)
                <span class="badge">{{ $couponCount }}</span>
            @endif
        </a>
        <a href="{{ route('review') }}" class="dashboard-card">
            <span><i class="fa fa-star"></i> Reviews</span>
            @if ($newReviewsCount > 0)
                <span class="badge">{{ $newReviewsCount }}</span>
            @endif
        </a>

    </div>
@endsection

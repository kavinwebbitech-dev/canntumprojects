@extends('admin.index')
@section('admin')
    <link href="vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        thead th {
            font-family: 'Open Sans', sans-serif !important;
        }

        .thead-success th {
            background-color: #001E40 !important;
        }

        table {
            margin-bottom: 20px !important;
            text-align: center;
        }
    </style>


    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Reviews List</h4>

        <!-- ✅ ADD REVIEW BUTTON -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReviewModal">
            Add Review
        </button>
    </div>




    @if (session('danger'))
        <div id="dangerAlert" class="alert alert-danger">
            {{ session('danger') }}
        </div>

        <script>
            setTimeout(function() {
                $('#dangerAlert').fadeOut('fast');
            }, 3000);
        </script>
    @endif


    @if (session('success'))
        <div id="successAlert" class="alert alert-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                $('#successAlert').fadeOut('fast');
            }, 3000);
        </script>
    @endif

    <div class="card-body">
        <div class="table-responsive" style="overflow-x:scroll;">
            <table id=""
                class="export-table display table table-bordered verticle-middle table-striped table-responsive-sm"
                style="min-width: 845px">
                <thead class="thead-success">
                    <tr>
                        <th>S.No</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Order</th>
                        <th>Review</th>
                        <th>Stars</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $key => $item)
                        @php $user = App\Models\User::find($item->user_id); @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name ?? 'Unknown User' }}</td>
                            <td>{{ $item->product_id ?? '-' }}</td>
                            <td>{{ $item->order_id ?? '-' }}</td>

                            <!-- Review preview -->
                            <td>
                                {{ Str::limit($item->command, 30, '...') }}
                            </td>

                            <td>{{ $item->star_count ?? '-' }}</td>

                            <td>
                                <div class="d-flex gap-2">
                                    <!-- View Button (opens modal) -->
                                    <button type="button" class="btn btn-info btn-sm review-view-btn"
                                        data-id="{{ $item->id }}" data-user="{{ $user->name ?? 'Unknown User' }}"
                                        data-review="{{ $item->command }}" data-stars="{{ $item->star_count }}"
                                        data-bs-toggle="modal" data-bs-target="#reviewModal">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- ✅ Add Review Modal -->
    <div class="modal fade" id="addReviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('admin.reviews.store') }}">
                    @csrf

                    <div class="modal-body">

                        <!-- ✅ USER DROPDOWN -->
                        <div class="mb-3">
                            <label>User</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ✅ PRODUCT DROPDOWN -->
                        <div class="mb-3">
                            <label>Product</label>
                            <select name="product_id" class="form-control">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ✅ ORDER DROPDOWN -->
                        <div class="mb-3">
                            <label>Order</label>
                            <select name="order_id" class="form-control">
                                <option value="">Select Order</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}">
                                        Order #{{ $order->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- REVIEW -->
                        <div class="mb-3">
                            <label>Review</label>
                            <textarea name="command" class="form-control" required></textarea>
                        </div>

                        <!-- STARS -->
                        <div class="mb-3">
                            <label>Stars</label>
                            <input type="number" name="star_count" class="form-control" min="1" max="5"
                                required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="editReviewForm" method="POST" action="{{ route('admin.reviews.update') }}">
                        @csrf
                        <input type="hidden" name="id" id="review_id">

                        <div class="mb-3">
                            <label class="form-label">Review</label>
                            <textarea class="form-control" name="command" id="review_text" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stars</label>
                            <input type="number" name="star_count" id="review_stars" class="form-control" min="1"
                                max="5">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <form id="deleteReviewForm" method="POST" action="{{ route('admin.reviews.delete') }}">
                        @csrf
                        <input type="hidden" name="id" id="delete_review_id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <button type="button" class="btn btn-primary" id="saveReviewBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="js/plugins-init/sweetalert.init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('a.btn-danger').click(function(event) {
                event.preventDefault(); // Prevent the default action of the link

                var deleteUrl = $(this).attr('href'); // Get the URL to delete

                Swal.fire({
                    title: 'Are you sure?',
                    text: "It will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user confirms, proceed with the deletion
                        window.location.href = deleteUrl; // Redirect to the delete URL
                    } else {
                        // If the user cancels, show a message
                        /*Swal.fire(
                        'Cancelled',
                        'Your file is safe :)',
                        'error'
                        );*/
                    }
                });
            });
        });
        $(document).ready(function() {
            $('.review-view-btn').on('click', function() {
                const id = $(this).data('id');
                const review = $(this).data('review');
                const stars = $(this).data('stars');

                $('#review_id').val(id);
                $('#delete_review_id').val(id);
                $('#review_text').val(review);
                $('#review_stars').val(stars);
            });

            // Save button inside modal
            $('#saveReviewBtn').on('click', function() {
                $('#editReviewForm').submit();
            });
        });
    </script>
@endsection

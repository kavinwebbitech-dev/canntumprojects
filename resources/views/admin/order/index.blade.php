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



    <div class="card-header">
        <h4 class="card-title">Order List</h4>
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
    @php
        $showRemarkColumn = $orders->whereNotNull('remark')->where('remark', '!=', '')->count() > 0;
    @endphp
    <div class="card-body">
        <div class="table-responsive" style="overflow-x:scroll;">
            <table id=""
                class="export-table display table table-bordered verticle-middle table-striped table-responsive-sm"
                style="min-width: 845px">
                <thead class="thead-success">
                    <tr>
                        <th>S.No</th>
                        <th style="width:30%">Order Id</th>
                        <th style="width:30%">Name</th>
                        <th style="width:30%">email</th>
                        <th style="width:30%">Phone</th>
                        <th style="width:30%">color</th>
                        <th style="width:30%">size</th>
                        <th style="width:30%">Payment Method</th>
                        <th style="width:15%">Order Status</th>
                        <th style="width:15%">Shipping Status</th>
                        @if ($showRemarkColumn)
                            <th style="width:15%">Remark</th>
                        @endif
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $item)
                        @php 
                            $user   = App\Models\User::where('id',$item->user_id)->first();
                            $orderDetails = App\Models\OrderDetail::where('order_id', $item->id)->first();
                        @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>
                                {{ $item->payment_order_id }}
                            </td>

                            <td>
                                {{ $user->name ?? '-' }}
                            </td>

                            <td>
                                {{ $user->email ?? '-' }}
                            </td>

                            <td>
                                {{ $user->phone ?? '-' }}
                            </td>
                            <td>
                                @if ($orderDetails && $orderDetails->color_id)
                                    @php
                                        $color = App\Models\Color::find($orderDetails->color_id);
                                    @endphp
                                    {{ $color->color ?? '-' }}
                                @else
                                    {{ $orderDetails->color ?? '-' }}
                                @endif
                            </td>

                            <td>
                                @if ($orderDetails && $orderDetails->size_id)
                                    @php
                                        $size = App\Models\Size::find($orderDetails->size_id);
                                    @endphp
                                    {{ $size->name ?? '-' }}
                                @else
                                    {{ $orderDetails->size ?? '-' }}
                                @endif
                            </td>
                            <td>

                                {{ strtoupper($item->payment_method ?? '-') }}
                            </td>
                            <td>
                                @if ($item->order_status == 0)
                                    <span class="badge badge-rounded badge-light">Pending</span>
                                @elseif($item->order_status == 1)
                                    <span class="badge badge-rounded badge-success">Approved</span>
                                @elseif($item->order_status == 2)
                                    <span class="badge badge-rounded badge-danger">Cancelled</span>
                                @elseif($item->order_status == 3)
                                    <span class="badge badge-rounded badge-warning">Return Requested</span>
                                @elseif($item->order_status == 4)
                                    <span class="badge badge-rounded badge-danger">Returned</span>
                                @else
                                    <span class="badge badge-rounded badge-warning">Waiting</span>
                                @endif

                            </td>

                            <td>

                                @if ($item->shipping_status == 1)
                                    <span class="badge badge-rounded badge-warning">Order Received</span>
                                @elseif($item->shipping_status == 2)
                                    <span class="badge badge-rounded badge-warning">Shipped</span>
                                @elseif($item->shipping_status == 3)
                                    <span class="badge badge-rounded badge-warning">Out Of Delivery</span>
                                @elseif($item->shipping_status == 4)
                                    <span class="badge badge-rounded badge-success">Delivered</span>
                                @else
                                    <span class="badge badge-rounded badge-light">Pending</span>
                                @endif

                            </td>
                            @if ($showRemarkColumn)
                                <td>
                                    @if ($item->remark)
                                        {{ $item->remark }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.order.detail', $item->id) }}"
                                        class="btn btn-success shadow btn-xs sharp me-2"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>




    <script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="js/plugins-init/sweetalert.init.js"></script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('a.btn-danger').click(function(event) {
                event.preventDefault();

                var deleteUrl = $(this).attr('href');

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
                        window.location.href = deleteUrl;
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
    </script>
@endsection

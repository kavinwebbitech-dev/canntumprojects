@extends('admin.index')
@section('admin')
    <link href="vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        thead th {
            font-family: 'Open Sans', sans-serif !important;
        }

         .thead-success th {
            background-color: #001E40 !important;
            color: #fff;
        }

        table {
            margin-bottom: 20px !important;
            text-align: center;
        }
    </style>

    <div class="card-header">
        <h4 class="card-title">Complaint List</h4>
    </div>

    @if (session('danger'))
        <div id="dangerAlert" class="alert alert-danger">
            {{ session('danger') }}
        </div>
        <script>
            setTimeout(() => $('#dangerAlert').fadeOut('fast'), 3000);
        </script>
    @endif

    @if (session('success'))
        <div id="successAlert" class="alert alert-success">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => $('#successAlert').fadeOut('fast'), 3000);
        </script>
    @endif

    <div class="card-body">
        <div class="table-responsive" style="overflow-x:scroll;">
            <table class="export-table display table table-bordered verticle-middle table-striped table-responsive-sm"
                style="min-width: 845px">
                <thead class="thead-success">
                    <tr>
                        <th>S.No</th>
                        <th>User Name</th>
                        <th>Order ID</th>
                        <th>Remark</th>
                        <th>Status</th>
                        <th style="width:15%">Options</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($complaints as $key => $c)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $c->user->name ?? '-' }}</td>

                            <td>{{ $c->order_id }}</td>

                            <td>{{ Str::limit($c->remark, 50) }}</td>

                            <td>
                                @if ($c->status == 'pending')
                                    <span class="badge badge-rounded badge-warning">Pending</span>
                                @elseif ($c->status == 'replied')
                                    <span class="badge badge-rounded badge-info">Replied</span>
                                @elseif ($c->status == 'solved')
                                    <span class="badge badge-rounded badge-success">Solved</span>
                                @elseif ($c->status == 'rejected')
                                    <span class="badge badge-rounded badge-danger">Rejected</span>
                                @endif
                            </td>



                            <td>
                                <div class="d-flex">
                                    <form action="{{ route('complaints.show', $c->id) }}" method="GET" class="me-2">
                                        <button type="submit" class="btn btn-success shadow btn-xs sharp">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('complaints.destroy', $c->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

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
    <script>
        $(document).ready(function() {

            $('.delete-btn').click(function(event) {
                event.preventDefault();

                var deleteUrl = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Complaint will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });

        });
    </script>
@endsection

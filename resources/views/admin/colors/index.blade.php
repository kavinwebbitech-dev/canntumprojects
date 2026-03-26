@extends('admin.layouts.master')
@section('title', 'Color List')
@section('content')
    <main class="main" id="main">
        
        <div class="row g-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Colors List</h2>
                <div>
                    <a href="javascript:void(0);" data-title="Add Color" data-size="modal-lg"
                        data-route="{{ route('admin.colors.create') }}" class="btn btn-primary common_model">+ Add Color</a>
                    {{-- <a href="{{ route('admin.categories.trashed') }}" class="btn btn-warning">Trashed</a> --}}
                </div>
            </div>
            
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped align-middle" id="color-table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(function() {
            // Initialize DataTable
            var table = $('#color-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.colors.index') }}',
                columns: [
                    {
                        data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                        searchable: false, className: 'text-center'
                    },
                    { data: 'name', name: 'name' },
                    { data: 'code', name: 'code' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
@extends('admin.layouts.master')
@section('title', 'Sizes List')
@section('content')
    <main class="main" id="main">
        
        <div class="row g-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Sizes List</h2>
                <div>
                    <a href="javascript:void(0);" data-title="Add Size" data-size="modal-lg"
                        data-route="{{ route('admin.sizes.create') }}" class="btn btn-primary common_model">+ Add Size</a>
                    {{-- <a href="{{ route('admin.categories.trashed') }}" class="btn btn-warning">Trashed</a> --}}
                </div>
            </div>
            
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped align-middle" id="size-table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Value</th>
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
            var table = $('#size-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.sizes.index') }}',
                columns: [
                    {
                        data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                        searchable: false, className: 'text-center'
                    },
                    { data: 'name', name: 'name' },
                    { data: 'value', name: 'value' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
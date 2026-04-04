@extends('admin.index')
@section('admin')
    <div class="card-header">
        <h4 class="card-title">Shipping Charges</h4>
        <button class="btn" style="background:#001E40;color:#fff"
            onclick="window.location.href='{{ route('admin.shipping_charge.create') }}'">
            Add New
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead style="background:#001E40;color:#fff">
                <tr>
                    <th>S.No</th>
                    <th>Min Amount</th>
                    <th>Max Amount</th>
                    <th>Charge</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($charges as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->min_amount ?? '0' }}</td>
                        <td>{{ $item->max_amount ?? '∞' }}</td>
                        <td>₹{{ $item->charge }}</td>

                        <td>
                            @if ($item->status)
                                <span class="badge badge-dark">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.shipping_charge.edit', $item->id) }}"
                                class="btn btn-success shadow btn-xs sharp me-2"><i class="fas fa-pencil-alt"></i></a>
                            <a href="{{ route('admin.shipping_charge.delete', $item->id) }}"
                                class="btn btn-danger shadow btn-xs sharp "><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

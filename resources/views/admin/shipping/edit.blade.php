@extends('admin.index')
@section('admin')

<h4>Edit Shipping Charge</h4>

<form action="{{ route('admin.shipping_charge.update') }}" method="POST">
    @csrf

    <input type="hidden" name="id" value="{{ $data->id }}">

    <div class="mb-3">
        <label>Min Amount</label>
        <input type="number" name="min_amount" value="{{ $data->min_amount }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Max Amount</label>
        <input type="number" name="max_amount" value="{{ $data->max_amount }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Shipping Charge</label>
        <input type="number" name="charge" value="{{ $data->charge }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1" {{ $data->status ? 'selected':'' }}>Active</option>
            <option value="0" {{ !$data->status ? 'selected':'' }}>Inactive</option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
</form>

@endsection
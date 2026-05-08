@extends('admin.index')
@section('admin')

<h4>Add Shipping Charge</h4>

<form action="{{ route('admin.shipping_charge.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Min Amount</label>
        <input type="number" name="min_amount" class="form-control">
    </div>

    <div class="mb-3">
        <label>Max Amount</label>
        <input type="number" name="max_amount" class="form-control">
    </div>

    <div class="mb-3">
        <label>Shipping Charge</label>
        <input type="number" name="charge" class="form-control" required>
    </div>

    <button class="btn btn-primary">Save</button>
</form>

@endsection
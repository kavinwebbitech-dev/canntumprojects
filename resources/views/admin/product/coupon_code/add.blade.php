@extends('admin.index')
@section('admin')
    @if (session('success'))
        <div id="successAlert" class="alert alert-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                $('#successAlert').fadeOut('fast');
            }, 3000); // 3000 milliseconds = 3 seconds
        </script>
    @endif




    <div class="profile-tab">
        <div class="custom-tab-1">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link active show">PRODUCT
                        COUPON CODE</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="profile-settings" class="tab-pane fade active show">
                    <div class="pt-3">
                        <div class="settings-form">
                            <form method="POST" action="{{ route('admin.coupon_code.store') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Code</label>
                                        <input type="text" name="code" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Percentage</label>
                                        <input type="text" name="percentage" class="form-control">
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-6">
                                        <label class="form-label">User Limit</label>
                                        <input type="number" name="user_limit" class="form-control"
                                            value="{{ old('user_limit') ?? 1 }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="date" name="expiry_date" class="form-control"
                                            value="{{ old('expiry_date') }}">
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Status</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="active"
                                                value="1">
                                            <label class="form-check-label" for="active">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inactive"
                                                value="0">
                                            <label class="form-check-label" for="inactive">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <button class="btn btn-primary" type="submit">Add coupon code</button>
                            </form>

                        </div>
                    </div>
                </div> <br><br><br>
            </div>
        </div>

    </div>
@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

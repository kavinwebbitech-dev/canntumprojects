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
                <li class="nav-item">
                    <a href="#profile-settings" data-bs-toggle="tab" class="nav-link active show">SIZE</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="profile-settings" class="tab-pane fade active show">
                    <div class="pt-3">
                        <div class="settings-form">
                            <form method="POST" action="{{ route('admin.product.size.store') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Enter size name" required>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Value</label>
                                        <input type="text" name="value" class="form-control"
                                            placeholder="Optional value, e.g., 32, 34, 36">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1" selected>Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <button class="btn btn-primary mt-3" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <br><br><br>
            </div>
        </div>
    </div>
@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

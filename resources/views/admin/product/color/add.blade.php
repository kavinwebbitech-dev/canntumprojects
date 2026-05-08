@extends('admin.index')
@section('admin')

@if(session('success'))
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
            <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link active show">COLOR</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="profile-settings" class="tab-pane fade active show">
                <div class="pt-3">
                    <div class="settings-form">
                            <form method="POST" action="{{ route('admin.product.color.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Color</label>
                                        <input type="text" name="color" class="form-control" placeholder='color'>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                       <label class="form-label">Code - Hex Code</label>
                                        <input type="text" name="code" class="form-control" placeholder="eg:#FF5733">
                                    </div>
                                </div>
                                <br>
                            
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>

                    </div>
                </div>
            </div> <br><br><br>
        </div>
    </div>

</div>

@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



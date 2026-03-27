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
            <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link active show">Gallery</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="profile-settings" class="tab-pane fade active show">
                <div class="pt-3">
                    <div class="settings-form">
                            <form method="POST" action="{{ route('admin.product.gallery.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Gallery Images</label>
                                        <input type="file" name="file2[]" class="form-control image_input" id="file2" multiple />
                                        <div id="imagePreviews"></div>
                                    </div>
                                </div>
                                <br>
                            
                                <button class="btn btn-primary" type="submit">Add gallery</button>
                            </form>

                    </div>
                </div>
            </div> <br><br><br>
        </div>
    </div>

</div>

@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



<script>
    $(document).ready(function() {
        $(".image_input").change(function() {
            var input = this;
            if (input.id === 'file1') {
                readURL(input, $('#imagePreview'));
            } else if (input.id === 'file2') {
                readURL(input, $('#imagePreviews'));
            }
        });

        function readURL(input, previewContainer) {
            if (input.files && input.files.length > 0) {
                var totalFiles = input.files.length;

                previewContainer.empty();

                    for (var i = 0; i < totalFiles; i++) {
                        if (input.files[i]) {
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                previewContainer.append('<img src="' + e.target.result + '" class="img-thumbnail" width="80"/>');
                            }

                            reader.readAsDataURL(input.files[i]);
                        }
                    }
                
            }
        }
    });
</script>

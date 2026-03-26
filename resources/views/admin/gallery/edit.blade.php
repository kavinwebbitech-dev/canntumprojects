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


<style>
    .img-postion{
        position:relative;
        display:inline-block;
    }
    .delete-icon{
        position:absolute;
        top:-7px;
        right:-7px;
        font-size:20px;
        
    }
     .delete-icon a{
         color:red;
     }
</style>




<div class="profile-tab">
    <div class="custom-tab-1">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link active show">PRODUCT GALLERY</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="profile-settings" class="tab-pane fade active show">
                <div class="pt-3">
                    <div class="settings-form">
                            <form method="POST" action="{{ route('admin.product.gallery.update') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="product_id" class="form-control" value="{{ $gallery->id }}">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $gallery->name }}" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                    <label class="form-label">Gallery Images</label>
                                    <input type="file" name="file2[]" class="form-control image_input" id="file2" multiple />
                                    <div id="imagePreviews">
                                    </div>
                                    <div id="imagePreviewsss">
                                           @php $galleryImages = App\Models\GalleryImage::where('gallery_id', $gallery->id)->get(); @endphp
                                            @foreach($galleryImages as $image)
                                                <div class="image-container mb-3">
                                                    <img src="{{ url('public/gallery_images/'.$image->image) }}" class="img-thumbnail" width="80" />
                                                    <a href="#" class="btn btn-danger delete-image" data-id="{{ $image->id }}">Delete</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <br>
                            
                                <button class="btn btn-primary" type="submit">Update Gallery</button>
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
    $('.delete-image').on('click', function() {
        var imageId = $(this).data('id');
        var imageContainer = $(this).closest('.image-container');

        $.ajax({
            type: "POST", // Change the request type to POST
            url: "{{ route('admin.gallery.image.delete') }}",
            data: {
                id: imageId,
                _token: '{{ csrf_token() }}' // Include the CSRF token in the request data
            },
            success: function(response) {
                // If deletion is successful, remove the image container from the DOM
                imageContainer.remove();
                alert('Image deleted successfully.');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                alert('Error deleting image.');
            }
        });
    });
});
</script>


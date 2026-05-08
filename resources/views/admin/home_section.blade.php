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
            <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link active show">Setting</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="profile-settings" class="tab-pane fade active show">
                <div class="pt-3">
                    <div class="settings-form">
                            <form method="POST" action="{{ route('admin.home.section.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <h2 style="color:brown;font-size:25px;font-weight:600;">Section 1</h2>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Category</label>
                                        <select class="default-select form-control wide" id="category" name="category_id">
                                            <option data-display="Select">Please select</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $section1->category ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">SubCategory</label>
                                        <select class="form-control wide" id="subcategory" name="subcategory">
                                            <option value="" data-display="Select">Please select</option>
                                            @foreach($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}" {{ $subcategory->id == $section1->sub_category ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Section Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    @if ($section1->image_name)
                                        <img src="{{ asset('/public/images/'.$section1->image_name) }}" alt="Section Image" class="mt-2" style="max-width: 200px;">
                                    @else
                                        <p>No image uploaded</p>
                                    @endif
                                </div>
                                <hr>
                                <h2 style="color:brown;font-size:25px;font-weight:600;">Section 2</h2>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Category</label>
                                        <select class="default-select form-control wide" id="category" name="category_id2">
                                            <option data-display="Select">Please select</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $section2->category ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">SubCategory</label>
                                        <select class="form-control wide" id="subcategory" name="subcategory2">
                                            <option value="" data-display="Select">Please select</option>
                                            @foreach($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}" {{ $subcategory->id == $section2->sub_category ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Section Image</label>
                                    <input type="file" class="form-control" id="image" name="image2">
                                    @if ($section2->image_name)
                                        <img src="{{ asset('/public/images/'.$section2->image_name) }}" alt="Section Image" class="mt-2" style="max-width: 200px;">
                                    @else
                                        <p>No image uploaded</p>
                                    @endif
                                </div>
                                
                            
                                <button class="btn btn-primary" type="submit">Update</button>
                            </form>

                    </div>
                </div>
            </div> <br><br><br>
        </div>
    </div>
   

</div>

@endsection




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<script>
    $(document).ready(function() {
        CKEDITOR.replace('description');
    });
</script>-->

<script type="text/javascript">
$(document).ready(function(){
    $('#category').change(function(){
        var category_id = $(this).val();
        if(category_id){
            $.ajax({
                type:"GET",
                url:"{{ route('get-subcategories', ['id' => ':category_id']) }}".replace(':category_id', category_id),
                success: function(data) {
                    console.log(data);
                    $('select[name="subcategory"]').empty();
                    $('select[name="subcategory"]').append('<option value="">Please select</option>');
                    $.each(data, function(key, value) {
                        $('select[name="subcategory"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                }
            });
        } else {
            $("#subcategory").empty();
        }
    });
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#category').change(function(){
        var category_id = $(this).val();
        if(category_id){
            $.ajax({
                type:"GET",
                url:"{{ route('get-subcategories', ['id' => ':category_id']) }}".replace(':category_id', category_id),
                success: function(data) {
                    console.log(data);
                    $('select[name="subcategory2"]').empty();
                    $('select[name="subcategory2"]').append('<option value="">Please select</option>');
                    $.each(data, function(key, value) {
                        $('select[name="subcategory2"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                }
            });
        } else {
            $("#subcategory").empty();
        }
    });
});

</script>



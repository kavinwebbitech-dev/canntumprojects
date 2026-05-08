@extends('admin.index')
@section('admin')
    <link href="vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        thead th {
            font-family: 'Open Sans', sans-serif !important;
        }

        thead th {
            font-family: 'Open Sans', sans-serif !important;
        }

        .thead-success th {
            background-color: #001E40 !important;
        }

        table {
            margin-bottom: 20px !important;
            text-align: center;
        }
    </style>

    <div class="card-header">
        <h4 class="card-title">All Offer Images</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bannerModal">
            Add Offer Image
        </button>
    </div>

    {{-- ── ADD MODAL ── --}}
    <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bannerModalLabel">Add Offer Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bannerForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                Offer Image
                                <small class="text-muted">(Required size: 1920 × 550 px)</small>
                            </label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div id="imageError" class="text-danger mt-1" style="display:none;"></div>
                            <div id="imagePreview" class="mt-2" style="display:none;">
                                <img id="previewImg" src="" alt="Preview"
                                    style="max-width:100%; border:1px solid #ddd; border-radius:4px;">
                                <small id="imageDimensions" class="text-muted d-block mt-1"></small>
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveOffer" disabled>Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── EDIT / UPDATE MODAL ── --}}
    <div class="modal fade" id="editBannerModal" tabindex="-1" aria-labelledby="editBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBannerModalLabel">Update Banner Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBannerForm" enctype="multipart/form-data">
                        <input type="hidden" id="editBannerId" name="id">

                        {{-- Current image preview --}}
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img id="currentBannerImg" src="" alt="Current Banner"
                                    style="max-width:100%; border:1px solid #ddd; border-radius:4px;">
                            </div>
                        </div>

                        {{-- New image upload --}}
                        <div class="mb-3">
                            <label for="editImage" class="form-label">
                                New Offer Image
                                <small class="text-muted">(Required size: 1920 × 550 px)</small>
                            </label>
                            <input type="file" class="form-control" id="editImage" name="image" accept="image/*">
                            <div id="editImageError" class="text-danger mt-1" style="display:none;"></div>
                            <div id="editImagePreview" class="mt-2" style="display:none;">
                                <img id="editPreviewImg" src="" alt="New Preview"
                                    style="max-width:100%; border:1px solid #ddd; border-radius:4px;">
                                <small id="editImageDimensions" class="text-muted d-block mt-1"></small>
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="updateBanner">Update</button>
                </div>
            </div>
        </div>
    </div>

    @if (session('danger'))
        <div id="successAlert" class="alert alert-danger">{{ session('danger') }}</div>
        <script>
            setTimeout(function() {
                $('#successAlert').fadeOut('fast');
            }, 3000);
        </script>
    @endif

    @if (session('success'))
        <div id="successAlert" class="alert alert-success">{{ session('success') }}</div>
        <script>
            setTimeout(function() {
                $('#successAlert').fadeOut('fast');
            }, 3000);
        </script>
    @endif

    <div class="card-body">
        <div class="table-responsive" style="overflow-x:scroll;">
            <table id="example4" class="export-table display table table-bordered verticle-middle table-striped table-responsive-sm" style="min-width: 845px">
                <thead class="thead-success">
                    <tr>
                        <th>S.No</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offer_images as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($item->image)
                                    <img src="{{ asset('public/offer_images/' . $item->image) }}"
                                        style="max-width: 200px;">
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-warning edit-offer-btn shadow btn-xs sharp"
                                    data-id="{{ $item->id }}"
                                    data-image="{{ asset('public/offer_images/' . $item->image) }}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>

                                <a href="{{ route('admin.offer_image.delete', $item->id) }}"
                                    class="btn btn-danger shadow btn-xs sharp"
                                    onclick="return confirm('Are you sure you want to delete this image?');">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            const REQUIRED_WIDTH = 1920;
            const REQUIRED_HEIGHT = 550;

            function validateImage(fileInput, errorEl, previewEl, previewImg, dimensionsEl, saveBtn) {
                const file = fileInput.files[0];
                $(errorEl).hide().text('');
                $(previewEl).hide();
                $(saveBtn).prop('disabled', true);

                if (!file) return;

                const objectUrl = URL.createObjectURL(file);
                const img = new Image();

                img.onload = function() {
                    const w = img.naturalWidth;
                    const h = img.naturalHeight;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewImg).attr('src', e.target.result);
                        $(previewEl).show();
                    };
                    reader.readAsDataURL(file);

                    URL.revokeObjectURL(objectUrl);

                    $(dimensionsEl).text(`Detected size: ${w} × ${h} px`);

                    if (w !== REQUIRED_WIDTH || h !== REQUIRED_HEIGHT) {
                        $(errorEl).text(`Invalid size (${w} × ${h})`).show();
                    } else {
                        $(saveBtn).prop('disabled', false);
                    }
                };

                img.src = objectUrl;
            }

            // ADD
            $('#image').on('change', function() {
                validateImage(this, '#imageError', '#imagePreview',
                    '#previewImg', '#imageDimensions', '#saveOffer');
            });

            $('#saveOffer').on('click', function() {
                $.ajax({
                    url: '{{ route('admin.offer_image.store') }}',
                    type: 'POST',
                    data: new FormData($('#bannerForm')[0]),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#bannerModal').modal('hide');
                        Swal.fire('Success', res.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                });
            });
            $('#editImage').on('change', function() {
                validateImage(this, '#editImageError', '#editImagePreview',
                    '#editPreviewImg', '#editImageDimensions', '#updateBanner');
            });
            // EDIT OPEN
            $(document).on('click', '.edit-offer-btn', function() {
                const id = $(this).data('id');
                const image = $(this).data('image');

                $('#editBannerId').val(id);
                $('#currentBannerImg').attr('src', image);

                // ✅ IMPORTANT FIX
                $('#updateBanner').prop('disabled', false);

                $('#editBannerModal').modal('show');
            });
            // UPDATE
            $('#updateBanner').on('click', function() {
                const formData = new FormData($('#editBannerForm')[0]);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: '{{ route('admin.offer_image.update') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#editBannerModal').modal('hide');
                        Swal.fire('Success', res.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                });
            });

        });
    </script>

    <script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endsection

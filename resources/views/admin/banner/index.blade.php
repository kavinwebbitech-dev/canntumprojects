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
        <h4 class="card-title">All Banner Image</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bannerModal">
            Add Banner Image
        </button>
    </div>

    {{-- ── ADD MODAL ── --}}
    <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bannerModalLabel">Add Banner Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bannerForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                Banner Image
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
                    <button type="button" class="btn btn-primary" id="savebanner" disabled>Save</button>
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
                                New Banner Image
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
                    <button type="button" class="btn btn-warning" id="updateBanner" disabled>Update</button>
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
            <table id="example4"
                class="export-table display table table-bordered verticle-middle table-striped table-responsive-sm"
                style="min-width: 845px">
                <thead class="thead-success">
                    <tr>
                        <th>S.No</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banner_images as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($item->image)
                                    <img src="{{ asset('public/banner_images/' . $item->image) }}" alt="Banner Image"
                                        class="mt-2" style="max-width: 200px;">
                                @else
                                    <p>No image uploaded</p>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    {{-- Edit button --}}
                                    <button type="button" class="btn btn-warning shadow btn-xs sharp edit-banner-btn"
                                        data-id="{{ $item->id }}"
                                        data-image="{{ asset('public/banner_images/' . $item->image) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    {{-- Delete button --}}
                                    <a href="{{ route('banner_images.delete', $item->id) }}"
                                        class="btn btn-danger shadow btn-xs sharp">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
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

            // ── Shared: validate image dimensions & show preview ─────────────
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

                    // Use FileReader for the visible preview (base64 — no blob URL shown)
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewImg).attr('src', e.target.result);
                        $(previewEl).show();
                    };
                    reader.readAsDataURL(file);

                    URL.revokeObjectURL(objectUrl);

                    $(dimensionsEl).text(`Detected size: ${w} × ${h} px`);

                    if (w !== REQUIRED_WIDTH || h !== REQUIRED_HEIGHT) {
                        $(errorEl)
                            .text(
                                `Invalid size (${w} × ${h} px). Must be exactly ${REQUIRED_WIDTH} × ${REQUIRED_HEIGHT} px.`
                            )
                            .show();
                    } else {
                        $(saveBtn).prop('disabled', false);
                    }
                };

                img.onerror = function() {
                    URL.revokeObjectURL(objectUrl);
                    $(errorEl).text('Could not read the image file.').show();
                };

                img.src = objectUrl;
            }

            // ── ADD: image picker ────────────────────────────────────────────
            $('#image').on('change', function() {
                validateImage(this, '#imageError', '#imagePreview',
                    '#previewImg', '#imageDimensions', '#savebanner');
            });

            // ── ADD: reset on close ──────────────────────────────────────────
            $('#bannerModal').on('hidden.bs.modal', function() {
                $('#bannerForm')[0].reset();
                $('#imageError').hide().text('');
                $('#imagePreview').hide();
                $('#savebanner').prop('disabled', true);
            });

            // ── ADD: save ────────────────────────────────────────────────────
            $('#savebanner').on('click', function() {
                $.ajax({
                    url: '{{ route('banner.add') }}',
                    type: 'POST',
                    data: new FormData($('#bannerForm')[0]),
                    processData: false,
                    contentType: false,
                    success: function() {
                        $('#bannerModal').modal('hide');
                        window.location.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // ── EDIT: open modal & populate ──────────────────────────────────
            $(document).on('click', '.edit-banner-btn', function() {
                const id = $(this).data('id');
                const image = $(this).data('image');

                $('#editBannerId').val(id);
                $('#currentBannerImg').attr('src', image);

                // reset new-image fields
                $('#editBannerForm')[0].reset();
                $('#editBannerId').val(id); // reset() clears hidden too — restore
                $('#editImageError').hide().text('');
                $('#editImagePreview').hide();
                $('#updateBanner').prop('disabled', false);

                $('#editBannerModal').modal('show');
            });

            // ── EDIT: image picker ───────────────────────────────────────────
            $('#editImage').on('change', function() {
                validateImage(this, '#editImageError', '#editImagePreview',
                    '#editPreviewImg', '#editImageDimensions', '#updateBanner');
            });

            // ── EDIT: reset on close ─────────────────────────────────────────
            $('#editBannerModal').on('hidden.bs.modal', function() {
                $('#editBannerForm')[0].reset();
                $('#editImageError').hide().text('');
                $('#editImagePreview').hide();
                $('#updateBanner').prop('disabled', false);
            });

            // ── EDIT: submit update ──────────────────────────────────────────
            $('#updateBanner').on('click', function() {
                const id = $('#editBannerId').val();
                const formData = new FormData($('#editBannerForm')[0]);
                formData.append('id', id);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: '{{ route('banner.update') }}', // adjust route name as needed
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(res) {
                        $('#editBannerModal').modal('hide');
                        Swal.fire('Success', res.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // ── DELETE: confirmation ─────────────────────────────────────────
            $(document).on('click', 'a.btn-danger', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'It will be permanently deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = deleteUrl;
                });
            });
        });
    </script>

    <script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endsection

@extends('admin.index')
@section('admin')
    @if (session('success'))
        <div id="successAlert" class="alert alert-success">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                $('#successAlert').fadeOut('fast');
            }, 3000);
        </script>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- ✅ GLOBAL VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .nice-select .list {
            overflow: auto;
            height: 200px;
        }

        .content-body {
            padding-bottom: 100px;
        }

        .hidden {
            display: none;
        }

        .variant-table {
            margin-top: 20px;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview-item {
            position: relative;
            width: 80px;
        }

        .image-preview-item img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .gallery-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .gallery-preview-item {
            position: relative;
            width: 80px;
        }

        .gallery-preview-item img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Variant required warning */
        #variantRequiredMsg {
            display: none;
            color: #dc3545;
            font-size: 13px;
            margin-top: 6px;
        }
    </style>

    <div class="profile-tab">
        <div class="custom-tab-1">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#profile-settings" data-bs-toggle="tab" class="nav-link active show">ADD PRODUCT</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="profile-settings" class="tab-pane fade active show">
                    <div class="pt-3">
                        <div class="settings-form">
                            <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data"
                                id="addProductForm">
                                @csrf

                                <!-- Basic Product Info -->
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Category</label>
                                        <select class="default-select form-control wide" id="category" name="category_id">
                                            <option data-display="Select">Please select</option>
                                            @php $categories = App\Models\ProductCategory::get(); @endphp
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">SubCategory</label>
                                        <select class="form-control wide" id="subcategory" name="subcategory">
                                            <option value="" data-display="Select">Please select</option>
                                        </select>
                                        @error('subcategory')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" name="product_name" class="form-control"
                                            placeholder="Ex: Tripr Buy Maroon Half Sleeve T-Shirt Online"
                                            value="{{ old('product_name') }}">
                                        @error('product_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Thumbnail Image (Square image – 1:1 ratio)</label>
                                        <input type="file" name="file1" class="form-control image_input" id="file1"
                                            accept="image/*" />
                                        <div id="imagePreview"></div>
                                        @error('file1')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label text-primary">Products Details Image-(Max 1 MB (Square
                                            image –
                                            1:1 ratio))</label>
                                        <input type="file" name="gallery_images" id="gallery_images" class="form-control"
                                            accept="image/*">
                                        <div id="galleryPreview" class="mt-2"></div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label>HSN Code</label>
                                        <input type="text" name="hsn_code" class="form-control"
                                            placeholder="Enter HSN Code" value="{{ old('hsn_code') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Actual Price</label>
                                        <input type="number" name="actual_price" class="form-control"
                                            placeholder="Ex: 26500" value="{{ old('actual_price') }}" step="0.01">
                                        @error('actual_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Discount (%)</label>
                                        <input type="number" name="discount" class="form-control" placeholder="Ex: 50"
                                            value="{{ old('discount') }}" step="0.01">
                                        @error('discount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Offer Price</label>
                                        <input type="number" name="offer_price" class="form-control" step="0.01"
                                            value="{{ old('offer_price') }}" readonly>
                                        @error('offer_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST (%)</label>
                                        <input type="number" name="gst" class="form-control" step="0.01" placeholder="Ex: 18%"
                                            value="{{ old('gst') }}">
                                        @error('gst')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" id="description" rows="5">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <input type="checkbox" id="best_sellers" name="best_sellers" value="1"
                                            {{ old('best_sellers') ? 'checked' : '' }}>
                                        <label for="best_sellers" class="ms-2">Best Sellers</label>
                                        <input type="checkbox" id="new_arrival" name="new_arrival" value="1"
                                            class="ms-3" {{ old('new_arrival') ? 'checked' : '' }}>
                                        <label for="new_arrival" class="ms-2">New Arrival</label>
                                        <input type="checkbox" id="trending_tshirt" name="trending_tshirt"
                                            value="1" class="ms-3" {{ old('trending_tshirt') ? 'checked' : '' }}>
                                        <label for="trending_tshirt" class="ms-2">Trending Products</label>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-3 mb-3">
                                        <label>Unit Value</label>

                                        <input type="number" name="unit_value" class="form-control" min="1"
                                            value="{{ old('unit_value', 1) }}" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Unit Type</label>

                                        <select name="unit_type" class="form-control" required>

                                            <option value="">Select Unit</option>

                                            <option value="Piece">Piece</option>
                                            <option value="Pieces">Pieces</option>
                                            <option value="Pair">Pair</option>
                                            <option value="Set">Set</option>
                                            <option value="Pack">Pack</option>
                                            <option value="Box">Box</option>
                                            <option value="Bottle">Bottle</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Gram">Gram</option>
                                            <option value="Litre">Litre</option>
                                            <option value="Meter">Meter</option>
                                            <option value="Roll">Roll</option>

                                        </select>
                                    </div>

                                </div>

                                <hr>

                                <!-- Variant Selection -->
                                <h4 class="text-success mb-3">Product Variants <span class="text-danger"
                                        style="font-size:14px;">(* At least one variant is required)</span></h4>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Select Color</label>
                                        <select class="form-control" id="selectColor">
                                            <option value="">-- Select Color --</option>
                                            @php $colors = App\Models\Color::get(); @endphp
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}">{{ $color->color }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Select Size</label>
                                        <select class="form-control" id="selectSize">
                                            <option value="">-- Select Size --</option>
                                            @php $sizes = App\Models\Size::whereNull('deleted_at')->get(); @endphp
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-success w-100 mt-1" id="addVariantBtn">
                                            <i class="mdi mdi-plus"></i> Add Variant
                                        </button>
                                    </div>
                                </div>
                                <div id="variantRequiredMsg">⚠ Please add at least one product variant before
                                    submitting.
                                </div>

                                {{-- ✅ Laravel variant errors --}}
                                @error('variants')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @foreach ($errors->get('variants.*') as $messages)
                                    @foreach ($messages as $message)
                                        <div class="text-danger">{{ $message }}</div>
                                    @endforeach
                                @endforeach
                                <!-- Variants Table -->
                                <div id="variantsContainer" class="variant-table hidden">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="variantsTable">
                                            <thead>
                                                <tr class="table-header-bg">
                                                    <th style="width: 12%;">Color</th>
                                                    <th style="width: 12%;">Size</th>
                                                    <th style="width: 10%;">Quantity</th>
                                                    <th style="width: 36%;">Variantes images-(Max 4 -(Square image –
                                                        1:1
                                                        ratio)-(Max 1 MB))
                                                    </th>
                                                    {{-- <th style="width: 30%;">Images-(e.g., 500 × 500px)</th> --}}
                                                    <th style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="variantsBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <button class="btn btn-primary mt-4" type="submit" id="submitProductBtn">Add
                                    Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>

    <script>
        let variantCounter = 0;
        const colorMap = {!! json_encode(App\Models\Color::pluck('color', 'id')) !!};
        const sizeMap = {!! json_encode(App\Models\Size::whereNull('deleted_at')->pluck('name', 'id')) !!};

        $(document).ready(function() {
            CKEDITOR.replace('description');

            // Sync CKEditor content before form submit
            $('form').on('submit', function(e) {

                // Sync CKEditor
                for (var instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }

                // ✅ Check variants exist
                if ($('#variantsBody tr').length === 0) {
                    e.preventDefault();
                    $('#variantRequiredMsg').show();
                    alert('Please add at least one variant');
                    $('html, body').animate({
                        scrollTop: $('#variantRequiredMsg').offset().top - 100
                    }, 400);
                    return false;
                }
            });

            if ($('#variantsBody tr').length === 0) {
                $('#variantsContainer').addClass('hidden');
            }

            // Add Variant Button
            $('#addVariantBtn').click(function() {
                const colorId = $('#selectColor').val();
                const sizeId = $('#selectSize').val();

                // if (!colorId || !sizeId) {
                //     alert('Please select both Color and Size');
                //     return;
                // }

                // Check for duplicate
                const isDuplicate = $(`tr[data-color="${colorId}"][data-size="${sizeId}"]`).length > 0;
                if (isDuplicate) {
                    alert('This Color-Size combination already exists');
                    return;
                }

                addVariantRow(colorId, sizeId);
                $('#selectColor').val('').focus();
                $('#selectSize').val('');
                $('#variantRequiredMsg').hide();
            });

            // Remove Variant
            $(document).on('click', '.remove-variant', function() {
                $(this).closest('tr').remove();
                if ($('#variantsBody tr').length === 0) {
                    $('#variantsContainer').addClass('hidden');
                }
            });

            // Image upload preview for variant specific images
            $(document).on('change', '.variant-images', function() {
                const files = this.files;
                const container = $(this).closest('td').find('.image-preview-container');

                if (files.length > 4) {
                    alert('Maximum 4 images allowed per variant');
                    this.value = '';
                    return;
                }

                container.empty();
                Array.from(files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        container.append(`
                        <div class="image-preview-item">
                            <img src="${e.target.result}" alt="preview">
                        </div>
                    `);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // ✅ Global Gallery Image Preview (Standalone Input)
            $('#gallery_images').change(function() {
                const previewContainer = $('#galleryPreviewStandalone');
                if (this.files && this.files[0]) {
                    previewContainer.empty();
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewContainer.append(
                            `<img src="${e.target.result}" class="img-thumbnail" width="100"/>`
                        );
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Category Change
            $('#category').change(function() {
                const categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get-subcategories', ['id' => ':category_id']) }}".replace(
                            ':category_id', categoryId),
                        success: function(data) {
                            $('#subcategory').empty().append(
                                '<option value="">Please select</option>');
                            $.each(data, function(key, value) {
                                $('#subcategory').append(
                                    `<option value="${key}">${value}</option>`);
                            });
                        }
                    });
                } else {
                    $('#subcategory').empty();
                }
            });

            // Thumbnail Image Preview
            $('#file1').change(function() {
                readURL(this, $('#imagePreview'));
            });

            function readURL(input, previewContainer) {
                if (input.files && input.files.length > 0) {
                    previewContainer.empty();
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewContainer.append(
                            `<img src="${e.target.result}" class="img-thumbnail" width="100"/>`);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Discount Calculation
            function calculateFromDiscount() {
                const actualPrice = parseFloat($("[name='actual_price']").val()) || 0;
                const discount = parseFloat($("[name='discount']").val()) || 0;

                if (actualPrice > 0 && discount === 0) {
                    $("[name='offer_price']").val(actualPrice.toFixed(2));
                } else if (actualPrice > 0 && discount > 0 && discount < 100) {
                    const offerPrice = actualPrice - (actualPrice * discount / 100);
                    $("[name='offer_price']").val(offerPrice.toFixed(2));
                } else {
                    $("[name='offer_price']").val('');
                }
            }

            $("[name='actual_price'], [name='discount']").on("input", calculateFromDiscount);
        });

        // Updated Add Variant Row Function — removed the repeating Gallery column
        function addVariantRow(colorId, sizeId) {
            const colorName = colorMap[colorId];
            const sizeName = sizeMap[sizeId];
            const rowId = `variant-${variantCounter}`;

            const html = `
            <tr data-color="${colorId}" data-size="${sizeId}" id="${rowId}">
                <td>${colorName}</td>
                <td>${sizeName}</td>
                <td>
                    <input type="number" name="variants[${variantCounter}][quantity]" class="form-control" placeholder="Qty" min="0" value="1" style="width:80px;">
                </td>
                <td>
                    <input type="file" name="variants[${variantCounter}][images][]" class="form-control variant-images" multiple accept="image/*">
                    <small class="text-muted">Max 4 images</small>
                    <div class="image-preview-container mt-2"></div>
                </td>
                <td class="text-center">
                    <input type="hidden" name="variants[${variantCounter}][color_id]" value="${colorId}">
                    <input type="hidden" name="variants[${variantCounter}][size_id]" value="${sizeId}">
                    <button type="button" class="btn btn-sm btn-danger remove-variant">
                        <i class="mdi mdi-close"></i>
                    </button>
                </td>
            </tr>
        `;

            $('#variantsBody').append(html);
            $('#variantsContainer').removeClass('hidden');
            variantCounter++;
        }
    </script>
@endsection

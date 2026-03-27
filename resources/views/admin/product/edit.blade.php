@extends('admin.index')
@section('admin')

    {{-- SUCCESS --}}
    @if (session('success'))
        <div id="successAlert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- VALIDATION --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .hidden {
            display: none;
        }

        .variant-table {
            margin-top: 20px;
        }

        .image-preview-container,
        .gallery-preview {
            display: flex;
            /* flex-wrap: wrap; */
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview-item,
        .gallery-preview-item {
            width: 80px;
            height: 80px;
            position: relative;
        }

        .image-preview-item img,
        .gallery-preview-item img {
            width: 80%;
            height: 80%;
            object-fit: inherit;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-xs-delete {
            position: absolute;
            top: -6px;
            right: 10px !important;
            background: red;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            border: none;
            cursor: pointer;
        }

        .table {
            overflow: visible !important;
        }

        .settings-form {
            padding-bottom: 80px;
            /* prevents overlap */
        }
    </style>

    <div class="settings-form">

        <form method="POST" action="{{ route('admin.product.update') }}" enctype="multipart/form-data" id="editForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            {{-- CATEGORY --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Category</label>
                    <select class="form-control" id="category" name="category_id">
                        <option value="">Select</option>
                        @foreach (App\Models\ProductCategory::get() as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Subcategory</label>
                    <select class="form-control" id="subcategory" name="subcategory">
                        @foreach (App\Models\ProductSubCategory::where('category_id', $product->category_id)->get() as $sub)
                            <option value="{{ $sub->id }}" {{ $product->subcategory == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- PRODUCT --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Product Name</label>
                    <input type="text" name="product_name" class="form-control"
                        value="{{ old('product_name', $product->product_name) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Thumbnail</label>
                    <input type="file" name="file1" id="file1" class="form-control">
                    <div id="imagePreview">
                        @if ($product->product_img)
                            <img src="{{ url('public/product_images/' . $product->product_img) }}"
                                style="
                                    width: 80px;
                                    height: 80px;
                                    padding: 5px;
                                ">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-primary">Products Details Image-(Max 1 MB -e.g.,500×500 px)</label>
                <input type="file" name="gallery_images" id="gallery_images" class="form-control">

                {{-- Hidden input to track if we should delete the existing image --}}
                <input type="hidden" name="remove_global_gallery" id="remove_global_gallery" value="0">

                <div id="galleryPreviewStandalone" class="mt-2" style="position: relative; width: 110px;">
                    @php $firstVariant = $productVariants->first(); @endphp

                    @if ($firstVariant && $firstVariant->gallery_images)
                        <div class="gallery-preview-item">
                            <img src="{{ asset('public/gallery_images/' . $firstVariant->gallery_images) }}" width="100"
                                class="img-thumbnail">

                            {{-- Changed onclick to a simpler global function --}}
                            <button type="button" class="btn-xs-delete" onclick="removeGlobalGalleryImage(this)">×</button>
                        </div>
                    @endif
                </div>
            </div>
            {{-- PRICE --}}
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Actual Price</label>
                    <input type="number" name="actual_price" class="form-control" value="{{ $product->orginal_rate }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Discount</label>
                    <input type="number" name="discount" class="form-control" value="{{ $product->discount }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Offer Price</label>
                    <input type="number" name="offer_price" class="form-control" value="{{ $product->offer_price }}"
                        readonly>
                </div>

                <div class="col-md-3 mb-3">
                    <label>GST</label>
                    <input type="number" name="gst" class="form-control" value="{{ $product->gst }}">
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
            </div>

            {{-- CHECKBOX --}}
            <div class="mb-3">
                <input type="checkbox" name="best_sellers" value="1" {{ $product->best_sellers ? 'checked' : '' }}>
                Best
                Sellers
                <input type="checkbox" name="new_arrival" value="1" {{ $product->new_arrival ? 'checked' : '' }}> New
                Arrival
                <input type="checkbox" name="trending_tshirt" value="1"
                    {{ $product->trending_tshirt ? 'checked' : '' }}> Trending
            </div>

            <hr>

            {{-- VARIANTS --}}
            <h4>Product Variants</h4>

            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="selectColor" class="form-control">
                        <option value="">Color</option>
                        @foreach (App\Models\Color::get() as $c)
                            <option value="{{ $c->id }}">{{ $c->color }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select id="selectSize" class="form-control">
                        <option value="">Size</option>
                        @foreach (App\Models\Size::whereNull('deleted_at')->get() as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="button" id="addVariantBtn" class="btn btn-success w-100">Add Variant</button>
                </div>
            </div>

            <div id="variantRequiredMsg" class="text-danger" style="display:none;">
                ⚠ Please add at least one variant
            </div>

            <div id="variantsContainer" class="variant-table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Qty</th>
                            <th>Variantes images-(Max 4 -e.g.,500×500 px-(Max 1 MB))</th>
                            {{-- <th>Images-(e.g., 500 × 500px)</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="variantsBody">

                        @foreach ($productVariants as $i => $v)
                            <tr data-color="{{ $v->color_id }}" data-size="{{ $v->size_id }}">

                                {{-- <td>{{ $v->colordetails->color }}</td> --}}
                                <td>{{ $v->colordetails?->color ?? 'N/A' }}</td>
                                <td>{{ $v->sizedetails?->name ?? 'N/A' }}</td>

                                <td>
                                    <input type="number" name="variants[{{ $i }}][quantity]"
                                        value="{{ $v->quantity }}" class="form-control" style="width:80px !important">
                                </td>

                                <td>
                                    <div class="image-preview-container">
                                        @foreach (json_decode($v->images) as $imgKey => $img)
                                            <div class="image-preview-item"
                                                id="variant-img-{{ $i }}-{{ $imgKey }}">
                                                <img src="{{ asset('public/variant_images/' . $img) }}" width="50">
                                                <button type="button" class="btn-xs-delete"
                                                    onclick="softDeleteVariantImage('{{ $i }}', '{{ $img }}', '#variant-img-{{ $i }}-{{ $imgKey }}')">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div id="removed-{{ $i }}"></div>

                                    <input type="file" name="variants[{{ $i }}][images][]"
                                        class="form-control" multiple>
                                    <input type="hidden" name="variants[{{ $i }}][color_id]"
                                        value="{{ $v->color_id }}">
                                    <input type="hidden" name="variants[{{ $i }}][size_id]"
                                        value="{{ $v->size_id }}">

                                    <div id="removed-{{ $i }}"></div>
                                </td>


                                <td>
                                    <button type="button" class="btn btn-danger remove-variant"><i
                                            class="mdi mdi-close"></i></button>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="text-start mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    Update Product
                </button>
            </div>

        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>

    <script>
        let variantCounter = {{ count($productVariants) }};
        const colorMap = {!! json_encode(App\Models\Color::pluck('color', 'id')) !!};
        const sizeMap = {!! json_encode(App\Models\Size::pluck('name', 'id')) !!};

        CKEDITOR.replace('description');

        $('form').on('submit', function(e) {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            if ($('#variantsBody tr').length === 0) {
                e.preventDefault();
                $('#variantRequiredMsg').show();
                alert('Add at least one variant');
            }
        });

        function softDeleteVariantImage(index, filename, elementId) {
            if (confirm('Remove this image from the product?')) {
                // Add to hidden input so the controller knows to exclude it from the JSON
                $('#removed-' + index).append(
                    `<input type="hidden" name="variants[${index}][removed_images][]" value="${filename}">`
                );

                // Remove from UI
                $(elementId).fadeOut(300, function() {
                    $(this).remove();
                });
            }
        }

        // UPDATED: Removed the gallery input from individual rows
        $('#addVariantBtn').click(function() {
            let c = $('#selectColor').val();
            let s = $('#selectSize').val();

            // if (!c || !s) {
            //     alert('Select color & size');
            //     return;
            // }
            const isDuplicate = $(`tr[data-color="${c}"][data-size="${s}"]`).length > 0;
            if (isDuplicate) {
                alert('This Color-Size combination already exists');
                return;
            }

            let html = `
            <tr data-color="${c}" data-size="${s}">
                <td>${colorMap[c]}</td>
                <td>${sizeMap[s]}</td>
                <td>
                    <input type="number" name="variants[${variantCounter}][quantity]" class="form-control" value="1" style="width:80px !important">
                </td>
                <td>
                    <input type="file" name="variants[${variantCounter}][images][]" class="form-control variant-images" multiple>
                    <div class="image-preview-container"></div>
                    <input type="hidden" name="variants[${variantCounter}][color_id]" value="${c}">
                    <input type="hidden" name="variants[${variantCounter}][size_id]" value="${s}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-variant"><i class="mdi mdi-close"></i></button>
                </td>
            </tr>`;

            $('#variantsBody').append(html);
            variantCounter++;
        });

        $(document).on('click', '.remove-variant', function() {
            $(this).closest('tr').remove();
        });

        // price calc
        $("[name='actual_price'], [name='discount']").on("input", function() {
            let actual = parseFloat($("[name='actual_price']").val()) || 0;
            let discount = parseFloat($("[name='discount']").val()) || 0;

            if (actual > 0 && discount >= 0 && discount < 100) {
                let offer = actual - (actual * discount / 100);
                $("[name='offer_price']").val(offer.toFixed(2));
            }
        });

        // UPDATED: Preview for the single main gallery input
        $(document).on('change', 'input[name="gallery_images"]', function() {
            let previewBox = $('#galleryPreviewStandalone');
            previewBox.html('');

            let files = this.files;
            if (files.length > 0) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    previewBox.append(`
                    <div class="gallery-preview-item">
                        <img src="${e.target.result}" style="width:80px; height:80px; border:1px solid #ddd; padding:2px;">
                    </div>
                `);
                };
                reader.readAsDataURL(files[0]);
            }
        });

        function removeGalleryImage(index, btn) {
            if (confirm('Remove image?')) {
                $('#remove-gallery-' + index).val(1);
                $(btn).closest('.gallery-preview-item').remove();
            }
        }

        $(document).on('change', '.variant-images', function() {

            const files = this.files;
            const container = $(this).closest('td').find('.image-preview-container');

            // count existing images (already shown)
            let existingCount = container.find('.image-preview-item').length;

            // total = existing + new
            let total = existingCount + files.length;

            if (total > 4) {
                alert('Maximum 4 images allowed per variant');
                this.value = '';
                return;
            }

            // remove only NEW previews (keep old DB images)
            container.find('.new-preview').remove();

            Array.from(files).forEach((file) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    container.append(`
                <div class="image-preview-item new-preview">
                    <img src="${e.target.result}" alt="preview">
                </div>
            `);
                };

                reader.readAsDataURL(file);
            });
        });
        // Update your existing preview listener to handle the UI better
        $(document).on('change', '#gallery_images', function() {
            let previewBox = $('#galleryPreviewStandalone');
            let files = this.files;

            if (files.length > 0) {
                // Reset the delete tracker since a new file is being uploaded
                $('#remove_global_gallery').val(0);

                let reader = new FileReader();
                reader.onload = function(e) {
                    previewBox.html(`
                <div class="gallery-preview-item">
                    <img src="${e.target.result}" width="100" class="img-thumbnail">
                    <button type="button" class="btn-xs-delete" onclick="removeGlobalGalleryImage(this)">×</button>
                </div>
            `);
                };
                reader.readAsDataURL(files[0]);
            }
        });

        function removeGlobalGalleryImage(btn) {
            if (confirm('Remove this shared gallery image?')) {
                $('#remove_global_gallery').val(1); // Tell Laravel to delete it
                $(btn).closest('.gallery-preview-item').remove(); // Clear UI
                $('#gallery_images').val(''); // Clear file input
            }
        }
    </script>

@endsection


<form id="ColorForm" action="{{ route('admin.sizes.store') }}" method="POST">
    @csrf

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">

                <!-- Size Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        Size Name <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Enter Size name (e.g. M, XL)">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Size Code (Hex) -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        Size Value
                    </label>
                    <input type="text" name="value" id="colorCode" class="form-control @error('value') is-invalid @enderror"value="{{ old('value') }}" placeholder="30, 42">
                    @error('value')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status"
                            class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status',1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <!-- Buttons -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="fa fa-save me-2"></i>Save Size
                </button>
                <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary rounded-pill px-4">
                    Cancel
                </a>
            </div>

        </div>
    </div>

</form>

<script>
    // jQuery Validation
    $(document).ready(function () {
        $('#ColorForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 1,
                    maxlength: 100
                },
                value: {
                    required: true,
                    maxlength: 20
                }
            },
            messages: {
                name: {
                    required: "Size name is required",
                    minlength: "Minimum 2 characters required"
                }
            },
            errorElement: 'span',
            errorClass: 'text-danger small',
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
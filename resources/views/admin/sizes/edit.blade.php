<form id="sizeForm" action="{{ route('admin.sizes.update', $size->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Size Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $size->name) }}"
                               placeholder="Enter Size name (e.g. M, XL)">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Size Code (Hex)
                        </label>
                        <div class="input-group">
                            <input type="text"
                                   name="value"
                                   id="colorCode"
                                   class="form-control @error('value') is-invalid @enderror"
                                   value="{{ old('value', $size->value) }}"
                                   placeholder="30, 42">
                        </div>
                        @error('value')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status', $size->status) == 1 ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ old('status', $size->status) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="fa fa-save me-2"></i>Update Size
                    </button>
                    <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary rounded-pill px-4">
                        Cancel
                    </a>
                </div>

            </div>
        </div>

    </form>
<script>
    $(document).ready(function () {
        $('#sizeForm').validate({
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
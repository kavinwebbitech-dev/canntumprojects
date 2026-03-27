<form id="ColorForm" action="{{ route('admin.colors.update', $color->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Color Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $color->name) }}"
                               placeholder="Enter color name (e.g. Red, Blue)">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Color Code (Hex)
                        </label>
                        <div class="input-group">
                            <input type="color"
                                   id="colorPicker"
                                   class="form-control form-control-color"
                                   value="{{ old('code', $color->code ?? '#000000') }}">
                            <input type="text"
                                   name="code"
                                   id="colorCode"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $color->code) }}"
                                   placeholder="#FF0000">
                        </div>
                        @error('code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status', $color->status) == 1 ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ old('status', $color->status) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Preview</label>
                        <div class="d-flex align-items-center gap-3">
                            <div id="colorPreview"
                                 style="width:50px;height:50px;border-radius:8px;
                                 background:{{ old('code', $color->code ?? '#000000') }};
                                 border:1px solid #ddd;">
                            </div>
                            <small class="text-muted">Color preview</small>
                        </div>
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="fa fa-save me-2"></i>Update Color
                    </button>
                    <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary rounded-pill px-4">
                        Cancel
                    </a>
                </div>

            </div>
        </div>

    </form>
<script>
    const picker = document.getElementById('colorPicker');
    const codeInput = document.getElementById('colorCode');
    const preview = document.getElementById('colorPreview');

    picker.addEventListener('input', function () {
        codeInput.value = this.value;
        preview.style.background = this.value;
    });

    codeInput.addEventListener('keyup', function () {
        if(this.value){
            preview.style.background = this.value;
            picker.value = this.value;
        }
    });

    $(document).ready(function () {
        $('#ColorForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 100
                },
                code: {
                    required: true,
                    maxlength: 20
                }
            },
            messages: {
                name: {
                    required: "Color name is required",
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
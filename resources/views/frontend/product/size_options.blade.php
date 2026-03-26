@foreach($sizes as $size)
    <input type="radio" class="btn-check check_size" name="size" id="size-{{ $size->id }}" value="{{ $size->id }}" 
        @if(request('size') == $size->id || (old('size') == $size->id) || ($loop->first && !request('size') && !old('size'))) 
            checked 
        @endif>
    <label class="btn size_btn" for="size-{{ $size->id }}">{{ $size->name }}</label>
@endforeach

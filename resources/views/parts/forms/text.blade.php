<div class="form-group row">
    <label for="{{ $name }}" class="col-md-4 col-form-label text-md-right">{{ $labelName }}</label>

    <div class="col-md-6">
        <input id="{{ $name }}" type="text"
        class="form-control"
        @error($name) is-invalid @enderror
        name="{{ $name }}"
        value="{{ old($name) }}"
        @if(isset($required)) required @endif
        @if(isset($pattern)) pattern="{{$pattern}}" @endif
        autocomplete="{{ isset($autocomplete) ? $autocomplete : $name }}" autofocus>

        @error($name)
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

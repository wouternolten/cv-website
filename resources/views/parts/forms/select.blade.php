<div class="form-group row">
    <label for="{{ $name }}" class="col-md-4 col-form-label text-md-right">{{ $labelName }}</label>

    <div class="col-md-6">
        <select name="{{ $name }}">
            <option value="">-- Please select one -- </option>
            @foreach(config('forms.selects.'.$options) as $value => $label)
                <option value="{{$value}}">{{ $label }}</option>
            @endforeach
        </select>

        @error($name)
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

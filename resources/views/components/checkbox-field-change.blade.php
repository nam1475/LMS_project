@if($isChange)
    <div class="mb-3">
        <input type="checkbox" value="{{ $value }}" @checked($value == 1) disabled class="form-control is-invalid">
    </div>
@endif
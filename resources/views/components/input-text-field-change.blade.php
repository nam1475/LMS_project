@if($isChange)
    <div class="mb-3">
        <input type="text" value="{{ $value }}" class="form-control is-invalid" disabled @isset($id) id="{{ $id }}" @endisset>
        {{-- <x-input-error :messages="$errors->get($name)" class="mt-2" /> --}}
        @isset($hint)
        {{ $hint }} 
        @endisset
    </div>
@endif
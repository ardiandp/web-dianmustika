@props(['cancel' => ''])

<div class="form-group mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save mr-1"></i> Simpan
    </button>
    <a href="{{ $cancel }}" class="btn btn-default ml-2">
        <i class="fas fa-times mr-1"></i> Batal
    </a>
</div>

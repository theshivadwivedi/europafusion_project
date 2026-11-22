{{-- Status + notes form shared by enquiries and partner requests --}}
<form method="POST" action="{{ $action }}" class="panel">
    @csrf @method('PATCH')
    <h2 class="h6 fw-bold mb-3">Follow-up</h2>
    <label class="form-label small fw-semibold" for="status">Status</label>
    <select class="form-select mb-3" id="status" name="status">
        @foreach ($statuses as $key => $label)<option value="{{ $key }}" @selected($record->status === $key)>{{ $label }}</option>@endforeach
    </select>
    <label class="form-label small fw-semibold" for="admin_notes">Internal notes</label>
    <textarea class="form-control mb-3 @error('admin_notes') is-invalid @enderror" id="admin_notes" name="admin_notes" rows="6" placeholder="Call outcome, next steps…">{{ old('admin_notes', $record->admin_notes) }}</textarea>
    @error('admin_notes')<div class="invalid-feedback mb-2">{{ $message }}</div>@enderror
    <button class="btn btn-primary w-100">Save changes</button>
</form>
<form method="POST" action="{{ $deleteAction }}" class="mt-3" data-confirm="Delete this record permanently?">
    @csrf @method('DELETE')
    <button class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash"></i> Delete</button>
</form>

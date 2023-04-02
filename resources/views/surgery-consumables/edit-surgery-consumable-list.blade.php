@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Edit Surgery Consumable Item</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('surgery.consumable.surgey.update', $sc->id) }}" method="post">
            @csrf
            @method("PUT")
            <div class="row g-4">
                <div class="col-sm-4">
                    <label class="form-label">Surgery Name<sup class="text-danger">*</sup></label>
                    <select class="form-control" name="surgery_id">
                        <option value="">Select</option>
                        @forelse($surgeries as $key => $surgery)
                            <option value="{{ $surgery->id }}" {{ ($surgery->id == $sc->surgery_id) ? 'selected' : '' }}>{{ $surgery->surgery_name }}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('name')
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                    @enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Consumable Name<sup class="text-danger">*</sup></label>
                    <select class="form-control" name="consumable_id">
                        <option value="">Select</option>
                        @forelse($consumables as $key => $consumable)
                            <option value="{{ $consumable->id }}" {{ ($consumable->id == $sc->consumable_id) ? 'selected' : '' }}>{{ $consumable->name }}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('name')
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                    @enderror
                </div>
                <div class="col-sm-2">
                    <label class="form-label">Default Qty.<sup class="text-danger">*</sup></label>
                    <input type="number" value="{{ $sc->default_qty }}" name="default_qty" class="form-control form-control-md" placeholder="0.0">
                    @error('default_qty')
                    <small class="text-danger">{{ $errors->first('default_qty') }}</small>
                    @enderror
                </div>
                <div class="col-sm-12 text-right">
                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary btn-submit">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
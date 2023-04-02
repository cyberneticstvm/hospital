@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Surgery Consumable Item Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('surgery.consumable.surgey.save') }}" method="post">
            @csrf
            <div class="row g-4">
                <div class="col-sm-4">
                    <label class="form-label">Surgery Name<sup class="text-danger">*</sup></label>
                    <select class="form-control" name="surgery_id">
                        <option value="">Select</option>
                        @forelse($surgeries as $key => $surgery)
                            <option value="{{ $surgery->id }}">{{ $surgery->surgery_name }}</option>
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
                            <option value="{{ $consumable->id }}">{{ $consumable->name }}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('name')
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                    @enderror
                </div>
                <div class="col-sm-2">
                    <label class="form-label">Default Qty.<sup class="text-danger">*</sup></label>
                    <input type="number" value="{{ old('default_qty') }}" name="default_qty" class="form-control form-control-md" placeholder="0.0">
                    @error('default_qty')
                    <small class="text-danger">{{ $errors->first('default_qty') }}</small>
                    @enderror
                </div>
                <div class="col-sm-12 text-right">
                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary btn-submit">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Consumable Name</th><th>Surgery Name</th><th>Default Qty.</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($scis as $sc)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $sc->consumable->name }}</td>
                    <td>{{ $sc->surgery->surgery_name }}</td>
                    <td>{{ $sc->default_qty }}</td>
                    <td><a class='btn btn-link' href="{{ route('surgery.consumable.surgey.edit', $sc->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('surgery.consumable.surgey.delete', $sc->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection
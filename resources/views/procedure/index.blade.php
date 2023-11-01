@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">{{ ($proc) ? 'Update' : 'Save' }} Procedure</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ ($proc) ? route('procedure.update', $proc->id) : route('procedure.create') }}" method="post">
                            @csrf
                            @if($proc) @method("PUT") @endif
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label">Procedure Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ ($proc) ? $proc->name : old('name') }}" name="name" class="form-control form-control-md" placeholder="Procedure Name">
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-control" name="type">
                                        <option value="P" {{ ($proc && $proc->type == 'P') ? 'selected' : '' }}>Select</option>
                                        <option value="S" {{ ($proc && $proc->type == 'S') ? 'selected' : '' }}>Surgery</option>
                                        <option value="A" {{ ($proc && $proc->type == 'A') ? 'selected' : '' }}>A-Scan</option>
                                        <option value="K" {{ ($proc && $proc->type == 'K') ? 'selected' : '' }}>Keratometry</option>
                                        <option value="T" {{ ($proc && $proc->type == 'T') ? 'selected' : '' }}>Tonometry</option>
                                        <option value="C" {{ ($proc && $proc->type == 'C') ? 'selected' : '' }}>Pachymetry</option>
                                        <option value="H" {{ ($proc && $proc->type == 'H') ? 'selected' : '' }}>HFA</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Fee<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ ($proc) ? $proc->fee : old('fee') }}" name="fee" class="form-control form-control-md" step="any" placeholder="0.00">
                                    @error('fee')
                                    <small class="text-danger">{{ $errors->first('fee') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">{{ ($proc) ? 'Update' : 'Save' }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <h5 class="mb-3 mt-3">Procedure List</h5>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Procedure Name</th>
                                    <th>Fee</th>
                                    <th>Edit</th><!--<th>Remove</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @forelse($procedures as $key => $procedure)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $procedure->name }}</td>
                                    <td class="text-right">{{ $procedure->fee }}</td>
                                    <td><a class='btn btn-link' href="{{ route('procedure.edit', $procedure->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                    <!--<td>
                                    <form method="post" action="{{ route('procedure.delete', $procedure->id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Procedure?');"><i class="fa fa-trash text-danger"></i></button>
                                    </form>
                                </td>-->
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
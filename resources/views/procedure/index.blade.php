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
                                <div class="col-sm-4">
                                    <label class="form-label">Procedure Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ ($proc) ? $proc->name : old('name') }}" name="name" class="form-control form-control-md" placeholder="Procedure Name">
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="type">
                                        @foreach($ptypes as $key => $ptype)
                                        <option value="{{ $ptype->type }}" {{ ($proc && $proc->type == $ptype->type) ? 'selected'  : '' }}>{{ $ptype->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Avail. Consult.</label>
                                    <select class="form-control" name="is_available_for_consultation">
                                        <option value="">Select</option>
                                        <option value="yes" {{ ($proc && $proc->is_available_for_consultation == 'yes') ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ ($proc && $proc->is_available_for_consultation == 'no') ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Fee<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ ($proc) ? $proc->fee : old('fee') }}" name="fee" class="form-control form-control-md" step="any" placeholder="0.00">
                                    @error('fee')
                                    <small class="text-danger">{{ $errors->first('fee') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Fee Stkta<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ ($proc) ? $proc->fee_stkta : old('fee_stkta') }}" name="fee_stkta" class="form-control form-control-md" step="any" placeholder="0.00">
                                    @error('fee_stkta')
                                    <small class="text-danger">{{ $errors->first('fee_stkta') }}</small>
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
                                    <th>Available for Consult.</th>
                                    <th>Fee</th>
                                    <th>Fee-Stkta</th>
                                    <th>Edit</th><!--<th>Remove</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @forelse($procedures as $key => $procedure)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $procedure->name }}</td>
                                    <td>{{ $procedure->is_available_for_consultation }}</td>
                                    <td class="text-right">{{ $procedure->fee }}</td>
                                    <td class="text-right">{{ $procedure->fee_stkta }}</td>
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
@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Procedure Cancelled Records</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.procedure.cancelled.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date <sup class="text-danger">*</sup></label>
                                    <input type="date" class="form-control" name="from_date" value="{{ $inputs[0] }}">
                                    @error('from_date')
                                    <small class="text-danger">{{ $errors->first('from_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date <sup class="text-danger">*</sup></label>
                                    <input type="date" class="form-control" name="to_date" value="{{ $inputs[1] }}">
                                    @error('to_date')
                                    <small class="text-danger">{{ $errors->first('to_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Procedure </label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="procedure">
                                        @foreach($procs as $key => $proc)
                                        <option value="{{ $proc->id }}" {{ ($inputs[2] == $proc->id) ? 'selected'  : '' }}>{{ $proc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Branch </label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="branch">
                                        @foreach($branches as $key => $branch)
                                        <option value="{{ $branch->id }}" {{ ($inputs[3] == $branch->id) ? 'selected'  : '' }}>{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">Fetch</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="dTblApp" class="table table-sm dataTable table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>MR Id</th>
                                    <th>Patient Name</th>
                                    <th>Patient ID</th>
                                    <th>Test Name</th>
                                    <th>Created On</th>
                                    <th>Deleted On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->medical_record_id }}</td>
                                    <td>{{ $row->patient->patient_name }}</td>
                                    <td>{{ $row->patient->patient_id }}</td>
                                    <td>{{ $row->procedures->name }}</td>
                                    <td>{{ $row->created_at->format('d.M.Y') }}</td>
                                    <td>{{ $row->deleted_at->format('d.M.Y') }}</td>
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
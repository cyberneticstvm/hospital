@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Glasses Prescribed</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.glasses.prescribed.fetch') }}" method="post">
                            @csrf
                            @php $today = date('d/M/Y') @endphp
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date</label>
                                    <input type="date" value="{{ $inputs[0] }}" name="fromdate" class="form-control form-control-md">
                                    @error('fromdate')
                                    <small class="text-danger">{{ $errors->first('fromdate') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date</label>
                                    <input type="date" value="{{ $inputs[1] }}" name="todate" class="form-control form-control-md">
                                    @error('todate')
                                    <small class="text-danger">{{ $errors->first('todate') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Branch<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="branch">
                                        <option value="">Select</option>
                                        @foreach($branches as $key => $branch)
                                        <option value="{{ $branch->id }}" {{ ($inputs && $inputs[2] == $branch->id) ? 'selected'  : '' }}>{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch')
                                    <small class="text-danger">{{ $errors->first('branch') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Status<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="status">
                                        <option value="">Select</option>
                                        <option value="yes" {{ ($inputs[3] == 'yes') ? 'selected'  : '' }}>Yes</option>
                                        <option value="no" {{ ($inputs[3] == 'no') ? 'selected'  : '' }}>No</option>
                                        <option value="all" {{ ($inputs[3] == 'all') ? 'selected'  : '' }}>All</option>
                                    </select>
                                    @error('type')
                                    <small class="text-danger">{{ $errors->first('type') }}</small>
                                    @enderror
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
                        <table class="table table-sm dataTable table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Date</th>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Medical Record ID</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $c = 1; $tot = 0; @endphp
                                @forelse($records as $key => $row)
                                <tr>
                                    <td>{{ $c++ }}</td>
                                    <td>{{ $row->created_at->format('d.M.Y') }}</td>
                                    <td>{{ $row->patient->patient_id }}</td>
                                    <td>{{ $row->patient->patient_name }}</td>
                                    <td>{{ $row->medical_record_id }}</td>
                                    <td>{{ $row->remarks }}</td>
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
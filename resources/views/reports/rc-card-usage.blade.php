@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Royaty Card Usage</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.discount.fetch') }}" method="post">
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
                                    <label class="form-label">Royalty Card<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="rc">
                                        <option value="">Select</option>
                                        @foreach($rcs as $key => $rc)
                                        <option value="{{ $rc->id }}" {{ ($inputs && $inputs[3] == $rc->id) ? 'selected'  : '' }}>{{ $rc->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('rc')
                                    <small class="text-danger">{{ $errors->first('rc') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Category<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="category">
                                        <option value="">Select</option>
                                        <option value="1" {{ ($inputs[4] == '1') ? 'selected'  : '' }}>Consultation</option>
                                        <option value="2" {{ ($inputs[4] == '2') ? 'selected'  : '' }}>Procedure</option>
                                    </select>
                                    @error('category')
                                    <small class="text-danger">{{ $errors->first('category') }}</small>
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
                                    <th>RC Card Number</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Discount Availed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $c = 1; $tot = 0; @endphp
                                @forelse($records as $key => $row)
                                <tr>
                                    <td>{{ $c++ }}</td>
                                    <td>{{ $row->created_at?->format('d.M.Y') }}</td>
                                    <td>{{ $row->patient->patient_id }}</td>
                                    <td>{{ $row->patient->patient_name }}</td>
                                    <td>{{ $row->mrid }}</td>
                                    <td>{{ $row->rc_number }}</td>
                                    <td class="text-right">{{ number_format($row->fee, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->discount, 2) }}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right fw-bold">Total</td>
                                    <td class="text-right fw-bold">{{ number_format($records->sum('fee'), 2) }}</td>
                                    <td class="text-right fw-bold">{{ number_format($records->sum('fee') - $records->sum('discount'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
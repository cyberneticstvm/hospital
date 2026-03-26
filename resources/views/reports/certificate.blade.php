@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Certificate</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.certificate.fetch') }}" method="post">
                            @csrf
                            @php $today = date('d/M/Y') @endphp
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date <sup class="text-danger">*</sup></label>
                                    <input type="date" class="form-control" name="from_date" value="{{ ($inputs && $inputs[0]) ? $inputs[0] : date('Y-m-d') }}">
                                    @error('from_date')
                                    <small class="text-danger">{{ $errors->first('from_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date <sup class="text-danger">*</sup></label>
                                    <input type="date" class="form-control" name="to_date" value="{{ ($inputs && $inputs[1]) ? $inputs[1] : date('Y-m-d') }}">
                                    @error('to_date')
                                    <small class="text-danger">{{ $errors->first('to_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Branch </label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="branch">
                                        <option value="0">Select</option>
                                        @foreach($branches as $key => $branch)
                                        <option value="{{ $branch->id }}" {{ ($inputs && $inputs[2] == $branch->id) ? 'selected'  : '' }}>{{ $branch->branch_name }}</option>
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
                                    <th>Patient Name</th>
                                    <th>Patient ID</th>
                                    <th>Doctor</th>
                                    <th>Branch</th>
                                    <th>Date</th>
                                    <th>Vision</th>
                                    <th>Medical</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $key => $record)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $record->patient_name }}</td>
                                    <td>{{ $record->patient_id }}</td>
                                    <td>{{ $record->doctor_name }}</td>
                                    <td>{{ $record->branch_name }}</td>
                                    <td>{{ $record->cdate }}</td>
                                    <td class="text-center"><a href="/license/vision/{{ $record->medical_record_id }}/" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
                                    <td class="text-center"><a href="/license/medical/{{ $record->medical_record_id }}/" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
                                    <td class="text-center"><a href="/certificate/receipt/{{ $record->id }}/" target="_blank"><i class="fa fa-file-pdf-o text-primary"></i></a></td>
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
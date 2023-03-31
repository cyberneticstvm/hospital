@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Appointments</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.appointment.fetch') }}" method="post">
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
                                    <input type="date" class="form-control" name="to_date" value="{{ ($inputs && $inputs[0]) ? $inputs[0] : date('Y-m-d') }}">
                                    @error('to_date')
                                    <small class="text-danger">{{ $errors->first('to_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="status">
                                    <option value="0" {{ ($inputs && $inputs[2] == '0') ? 'selected'  : '' }}>Select</option>
                                    <option value=">" {{ ($inputs && $inputs[2] == '>') ? 'selected'  : '' }}>Consulted</option>
                                    <option value="=" {{ ($inputs && $inputs[2] == '=') ? 'selected'  : '' }}>Not Consulted</option>
                                    </select>
                                </div>                                
                                <div class="col-sm-3">
                                    <label class="form-label">Branch </label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="branch">
                                    <option value="0">Select</option>
                                    @foreach($branches as $key => $branch)
                                        <option value="{{ $branch->id }}" {{ ($inputs && $inputs[3] == $branch->id) ? 'selected'  : '' }}>{{ $branch->branch_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Doctor</label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="doctor">
                                    <option value="0">Select</option>
                                    @foreach($doctors as $key => $doctor)
                                        <option value="{{ $doctor->id }}" {{ ($inputs && $inputs[4] == $doctor->id) ? 'selected'  : '' }}>{{ $doctor->doctor_name }}</option>
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
                            <thead><tr><th>SL No.</th><th>Patient Name</th><th>Mobile</th><th>Address</th><th>Branch</th><th>Doctor</th><th>MR.ID</th><th>Date</th></tr></thead><tbody>
                            @php $c = 1; @endphp
                            @forelse($records as $key => $row)
                            <tr>
                                <td>{{ $c++ }}</td>
                                <td>{{ $row->patient_name }}</td>
                                <td>{{ $row->mobile_number }}</td>
                                <td>{{ $row->address }}</td>
                                <td>{{ $row->branches->branch_name }}</td>
                                <td>{{ $row->doctors->doctor_name }}</td>
                                <td>{{ $row->medical_record_id }}</td>
                                <td>{{ $row->appointment_date->format('d/M/Y') }}</td>
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
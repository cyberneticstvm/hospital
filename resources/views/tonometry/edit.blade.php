@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Tonometry</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-3">MR.ID: <h5 class="text-primary">{{ $mrecord->id }}</h5></div>
                            <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ ($patient) ? $patient->patient_name : '' }}</h5></div>
                            <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ ($patient) ? $patient->patient_id : '' }}</h5></div>
                            <div class="col-sm-3">Age: <h5 class="text-primary">{{ ($age) ? $age : '' }}</h5></div>
                        </div>
                        <form action="{{ route('tonometry.update', $tonometry->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient_id" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <div class="row g-4">                                
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered" style="width:30%; margin:0 auto;">
                                        <thead><tr><th width="20%"><th>NCT</th><th>AT</th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td>OD</td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="nct_od">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}" {{ ($power->value == $tonometry->nct_od) ? 'selected' : '' }}>{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>                                              
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="at_od">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}" {{ ($power->value == $tonometry->at_od) ? 'selected' : '' }}>{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="nct_os">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}" {{ ($power->value == $tonometry->nct_os) ? 'selected' : '' }}>{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>                                              
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="at_os">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}" {{ ($power->value == $tonometry->at_os) ? 'selected' : '' }}>{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Time</td>
                                                <td><input type="text" class="form-control" name="nct_time" value="{{ ($tonometry->nct_time) ? $tonometry->nct_time : date('h:i a') }}"></td>
                                                <td><input type="text" class="form-control" name="at_time" value="{{ ($tonometry->at_time) ? $tonometry->at_time : date('h:i a') }}"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 text-end">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
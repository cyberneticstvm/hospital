@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Tests Advised</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('tests.advised.update', $test->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $test->patient->patient_name }}</h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $test->patient->patient_id }}</h5></div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $test->doctor->doctor_name }}</h5></div>
                                <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $test->medical_record_id }}</h5></div>
                                <div class="col-sm-6">
                                    <label class="form-label">Test Advised<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $test->test()->find($test->test)->name }}" name="test_advised" class="form-control form-control-md" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Attachment (If any)</label>
                                    <input type="file" name="att" class="form-control form-control-md">
                                    <small>File: <a href="/storage/tests-advised/{{ $test->medical_record_id }}/{{ $test->attachment }}" target="_blank">View</a></small>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">Select</option>
                                        <option value="Pending" {{ ($test->status == 'Pending') ? 'selected' : '' }}>Pending</option>
                                        <option value="Completed" {{ ($test->status == 'Completed') ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ ($test->status == 'Cancelled') ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control form-control-md">{{ $test->notes }}</textarea>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Proposed Date</label>
                                    <input type="date" name="proposed_date" class="form-control" value="{{ ($test->proposed_date) ? $test->proposed_date : '' }}" />
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
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Patient Payment</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient-payment.update', $payment->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="medical_record_id" value="{{ $payment->id }}" />
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                            <input type="hidden" name="pharmacy_id" value="0" />
                            <div class="row g-4">
                                <div class="col-sm-3">Medical Record ID: <h5 class="text-primary">{{ $payment->medical_record_id }}</h5></div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }} </h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label class="form-label">Payment Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($payment->created_at) ? date('d/M/Y', strtotime($payment->created_at)) : '' }}" name="created_at" class="form-control form-control-md dtpicker">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('created_at')
                                    <small class="text-danger">{{ $errors->first('created_at') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Amount<sup class="text-danger">*</sup></label>
                                    <input type="number" name="amount" step="any" class="form-control form-control-md" value="{{ $payment->amount }}">
                                    @error('amount')
                                    <small class="text-danger">{{ $errors->first('amount') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Payment Mode<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="payment_mode">
                                        <option value="">Select</option>
                                        @forelse($pmodes as $key => $pmode)
                                            <option value="{{ $pmode->id }}" {{ ($pmode->id == $payment->payment_mode) ? 'selected': '' }}>{{ $pmode->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('payment_mode')
                                    <small class="text-danger">{{ $errors->first('payment_mode') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="notes" class="form-control form-control-md" value="{{ $payment->notes }}">
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
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
@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Expenses Detailed</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $medical_record_id }}</h5></div>
                            <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ ($patient) ? $patient->patient_name : '' }}</h5></div>
                            <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ ($patient) ? $patient->patient_id : '' }}</h5></div>
                            <div class="col-sm-3">Date: <h5 class="text-primary">{{ ($patient) ? $patient->cdate : '' }}</h5></div>
                        </div>
                        <form action="{{ route('patient-payment.save') }}" method="post">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $medical_record_id }}" />
                            <input type="hidden" name="pharmacy_id" value="0" />
                            <input type="hidden" name="patient_id" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ ($patient) ? $patient->branch : 0 }}" />
                            <table class="table table-sm table-striped table-hover align-middle">
                                <thead><tr><th>Income Head</th><th>Amount</th></tr></thead>
                                @php $due = app('\App\Http\Controllers\HelperController')->getPreviousDues($patient->id) @endphp
                                <tbody>
                                    <tr><td class="text-danger fw-bold">Total Due</td><td class="text-right text-danger fw-bold">{{ number_format($due, 2) }}</td></tr>
                                    @forelse($heads as $key => $head)
                                        <tr><td>{{ $head->name }}</td><td class="text-right">{{ number_format($fee[$key], 2) }}</td></tr>
                                    @empty
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr><th class="text-end fw-bold">Total for the provided MRN</th><th class="text-end fw-bold">{{ number_format($tot, 2) }}</th></tr>
                                </tfoot>
                            </table>
                            <h5 class="mb-3">Payments received against MR.ID <span class="fw-bold">{{ $medical_record_id }}</span></h5>
                            <table class="table table-sm table-striped table-hover align-middle">
                                <thead><tr><th>Payment Mode</th><th>Notes</th><th>Amount</th></tr></thead>
                                <tbody>
                                    @php $ptot = 0.00; @endphp
                                    @forelse($payments as $key => $payment)
                                        <tr><td>{{ $payment->name }}</td><td>{{ $payment->notes }}</td><td class="text-right">{{ $payment->amount }}</td></tr>
                                    @php $ptot += $payment->amount; @endphp
                                    @empty
                                    @endforelse
                                </tbody>
                                <!--<tfoot>
                                    <tr><th colspan="2" class="text-end fw-bold">Received for the MRN</th><th class="text-end fw-bold">{{ number_format($ptot, 2) }}</th></tr>
                                    <tr><th colspan="2" class="text-end fw-bold">Balance for the MRN</th><th class="text-end fw-bold">{{ number_format(($tot)-$ptot, 2) }}</th></tr>
                                </tfoot>-->
                            </table>
                            <div class="row g-4">
                                <div class="col-sm-2">
                                    <label class="form-label">Amount<sup class="text-danger">*</sup></label>
                                    <input type="number" name="amount" step="any" class="form-control form-control-md" placeholder="0.00">
                                    @error('amount')
                                    <small class="text-danger">{{ $errors->first('amount') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Payment Mode<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="payment_mode">
                                        <option value="">Select</option>
                                        @forelse($pmodes as $key => $pmode)
                                            <option value="{{ $pmode->id }}">{{ $pmode->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('payment_mode')
                                    <small class="text-danger">{{ $errors->first('payment_mode') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Payment Type</label>
                                    <select class="form-control select2" name="type">
                                        <option value="0">Select</option>
                                        @forelse($types as $key => $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('type')
                                    <small class="text-danger">{{ $errors->first('type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="notes" class="form-control form-control-md" placeholder="Notes">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-submit btn-primary w-100">Update</button>
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
@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Fetch All Expenses</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('income.show') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Medical Record ID (MR.ID)<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ $medical_record_id }}" name="medical_record_id" class="form-control form-control-md" placeholder="Mediical Record ID">
                                    @error('medical_record_id')
                                    <small class="text-danger">{{ $errors->first('medical_record_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Expenses Detailed</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <form id="frm-patient-payment" action="{{ route('income.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $medical_record_id }}" />
                            <table class="table table-sm table-striped table-hover align-middle">
                                <thead><tr><th>Income Head</th><th>Amount</th></tr></thead>
                                <tbody>
                                    @forelse($heads as $key => $head)
                                        <tr><td>{{ $head->name }}</td><td class="text-right">{{ number_format($fee[$key], 2) }}</td></tr>
                                    @empty
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr><th class="text-end fw-bold">Total</th><th class="text-end fw-bold">{{ number_format($tot, 2) }}</th></tr>
                                </tfoot>
                            </table>
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Amount<sup class="text-danger">*</sup></label>
                                    <input type="number" name="amount" class="form-control form-control-md" placeholder="0.00">
                                    @error('amount')
                                    <small class="text-danger">{{ $errors->first('amount') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
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
                                <div class="col-sm-3">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="medical_record_number" class="form-control form-control-md" placeholder="Notes">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-patient-payment-update btn-primary w-100">Update</button>
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
@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Payment for {{ $customer->name }}</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if (session('error'))
                        <div class="alert alert-danger" style="margin-top: 0.2rem;">
                            {{ session('error') }}
                        </div>
                        @endif
                        <form action="{{ route('customer.account.edit', encrypt($payment->id)) }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Payment Date<sup class="text-danger">*</sup></label>
                                    {{ Form::date('pdate', $payment->pdate, ['class' => 'form-control']) }}
                                    @error('pdate')
                                    <small class="text-danger">{{ $errors->first('pdate') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Amount<sup class="text-danger">*</sup></label>
                                    <input type="number" name="amount" value="{{ $payment->amount }}" class="form-control form-control-md" placeholder="0.00">
                                    @error('amount')
                                    <small class="text-danger">{{ $errors->first('amount') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Notes<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $payment->notes }}" name="notes" class="form-control form-control-md" placeholder="Notes">
                                    @error('notes')
                                    <small class="text-danger">{{ $errors->first('notes') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
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
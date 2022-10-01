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
                                    <input type="number" value="{{ $medical_record_number }}" name="medical_record_number" class="form-control form-control-md" placeholder="Mediical Record Number">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                </div>
                            </div>
                            @if (count($errors) > 0)
                            <div role="alert" class="text-danger mt-3">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                            @endif
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
                        <form action="" method="post">
                            @csrf
                            <table class="table table-sm table-striped table-hover align-middle">
                                <thead><tr><th>Income Head</th><th>Amount</th></tr></thead>
                                <tbody>
                                    @forelse($heads as $key => $head)
                                        <tr><td>{{ $head->name }}</td><td class="text-right">{{ $reg_fee }}</td></tr>
                                    @empty
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr><th class="text-end fw-bold">Total</th><th class="text-end fw-bold">{{ $tot }}</th></tr>
                                </tfoot>
                            </table>
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Amount<sup class="text-danger">*</sup></label>
                                    <input type="number" name="amount" class="form-control form-control-md" placeholder="0.00">
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
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="medical_record_number" class="form-control form-control-md" placeholder="Notes">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Update</button>
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
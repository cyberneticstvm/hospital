@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Daybook Consolidated</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.fetchdaybook') }}" method="post">
                            @csrf
                            @php $today = date('d/M/Y') @endphp
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs) ? $inputs[0] : $today }}" name="fromdate" class="form-control form-control-md dtpicker">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('fromdate')
                                    <small class="text-danger">{{ $errors->first('fromdate') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs) ? $inputs[1] : $today }}" name="todate" class="form-control form-control-md dtpicker">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('todate')
                                    <small class="text-danger">{{ $errors->first('todate') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
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
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
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
                            <thead><tr><th>SL No.</th><th>Particulars</th><th class="text-end">Total</th></tr></thead><tbody>
                            <tr>
                                <td>1</td><td>Income from Registration</td><td class="text-end">{{ number_format($reg_fee_total, 2) }}</td>                                
                            </tr>
                            <tr>
                                <td>2</td><td>Income from Consultation</td><td class="text-end">{{ number_format($consultation_fee_total, 2) }}</td>       
                            </tr>
                            <tr>
                                <td>3</td><td>Income from Procedures</td><td class="text-end">{{ number_format($procedure_fee_total, 2) }}</td>       
                            </tr>
                            <tr>
                                <td>4</td><td>Income from Certificates</td><td class="text-end">{{ number_format($certificate_fee_total, 2) }}</td>       
                            </tr>
                            <tr>
                                <td>5</td><td>Income from Pharmacy (Direct)</td><td class="text-end">{{ number_format($pharmacy, 2) }}</td>       
                            </tr>
                            <tr>
                                <td>6</td><td>Income from Pharmacy</td><td class="text-end">{{ number_format($medicine, 2) }}</td>       
                            </tr>
                            <tr>
                                <td>7</td><td>Income from Other Sources</td><td class="text-end">{{ number_format($income, 2) }}</td>       
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Grand Total</td><td class="text-end fw-bold">{{ number_format($income_total, 2) }}</td>       
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Expenses</td><td class="text-end fw-bold">{{ number_format($expense, 2) }}</td>       
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Net Total</td><td class="text-end fw-bold">{{ number_format($income_total - $expense, 2) }}</td>       
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Payment Received in Cash</td><td class="text-end fw-bold">{{ number_format($income_received_cash, 2) }}</td>       
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Payment Received through Other Sources (UPI/Bank Transfer/Cheque)</td><td class="text-end fw-bold">{{ number_format($income_received_other, 2) }}</td>       
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Balance</td><td class="text-end fw-bold">{{ number_format($income_total-($income_received_cash + $income_received_other + $expense), 2) }}</td>       
                            </tr>             
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
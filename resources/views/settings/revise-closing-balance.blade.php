@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Fetch Closing Balance</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('settings.fetchClosingBalanceforUpdate') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs) ? $inputs[0] : date('d/M/Y') }}" name="date" class="form-control form-control-md dtpicker">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('date')
                                    <small class="text-danger">{{ $errors->first('date') }}</small>
                                    @enderror
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
                        @if($record)
                        <div class="row mt-5">
                            <h5 class="text-primary">Update Closing Balance</h5>
                            <form action="{{ route('settings.closingbalance.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="ddate" value="{{ $inputs[0] }}">
                                <div class="row mt-3">
                                    <div class="col-sm-2">
                                        <label class="form-label">Closing Balance</label>
                                        <input type="number" value="{{ $record->closing_balance }}" name="closing_balance" class="form-control form-control-md" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label">Operation</label>
                                        <select class="form-control form-control-md select2" name="operand">
                                            <option value="add">+</option>
                                            <option value="sub">-</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label">Amount</label>
                                        <input type="number" name="amount" placeholder="0.00" class="form-control form-control-md">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-submit w-100" onClick="javascript: return confirm('Are you sure want to perform this action?');">Update</button>
                                    </div>
                                </div>                                
                            </form>
                        </div>
                        @elseif(!session()->has('message'))
                            <div class="text-danger mt-5">No records found.</div>
                        @endif
                        <div class="mt-5">
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session()->get('message') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
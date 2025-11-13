@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Transfer</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.transfer.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date<sup class="text-danger">*</sup></label>
                                    {{ Form::date('from_date', (old('from_date')) ?? $inputs[0], ['class' => 'form-control']) }}
                                    @error('from_date')
                                    <small class="text-danger">{{ $errors->first('from_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date<sup class="text-danger">*</sup></label>
                                    {{ Form::date('to_date', (old('to_date')) ?? $inputs[1], ['class' => 'form-control']) }}
                                    @error('to_date')
                                    <small class="text-danger">{{ $errors->first('to_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Branch<sup class="text-danger">*</sup></label>
                                    {{ Form::select('branch', $branches, $inputs[2], ['class' => 'form-control form-control-md show-tick ms select2', 'placeholder' => 'Select']) }}
                                    @error('branch')
                                    <small class="text-danger">{{ $errors->first('branch') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Product</label>
                                    {{ Form::select('product', $products, $inputs[3], ['class' => 'form-control form-control-md show-tick ms select2', 'placeholder' => 'Select']) }}
                                    @error('product')
                                    <small class="text-danger">{{ $errors->first('product') }}</small>
                                    @enderror
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
                        <table id="dataTbl" class="table table-sm dataTable table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Date</th>
                                    <th>From Branch</th>
                                    <th>To Branch</th>
                                    <th>Bill</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $key => $transfer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transfer->transfer_date->format('d.M.Y') }}</td>
                                    <td>{{ $transfer->fromBr->branch_name }}</td>
                                    <td>{{ $transfer->toBr->branch_name }}</td>
                                    <td class="text-center"><a href="/product-transfer/bill/{{ $transfer->id}}" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
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
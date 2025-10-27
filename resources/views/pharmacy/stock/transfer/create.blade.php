@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-5">
                                <h5 class="">Create Product Transfer</h5>
                            </div>
                            <form action="{{ route('product-transfer.create') }}" method="post">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-3">
                                        <label class="form-label">Transfer Date<sup class="text-danger">*</sup></label>
                                        {{ Form::date('transfer_date', (old('transfer_date')) ?? date('Y-m-d'), ['class' => 'form-control', 'required' => 'required']) }}
                                        @error('transfer_date')
                                        <small class="text-danger">{{ $errors->first('transfer_date') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">From Branch<sup class="text-danger">*</sup></label>
                                        {{ Form::select('from_branch', $branches->where('id', Session::get('branch'))->pluck('branch_name', 'id'), old('from_branch') ?? Session::get('branch'), ['class' => 'form-control form-control-md show-tick ms select2 selFromBranch', 'placeholder' => 'Select', 'required' => 'required']) }}
                                        @error('from_branch')
                                        <small class="text-danger">{{ $errors->first('from_branch') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">To Branch<sup class="text-danger">*</sup></label>
                                        {{ Form::select('to_branch', $branches->where('id', '!=', Session::get('branch'))->pluck('branch_name', 'id'), old('to_branch'), ['class' => 'form-control form-control-md show-tick ms select2', 'placeholder' => 'Select', 'required' => 'required']) }}
                                        @error('to_branch')
                                        <small class="text-danger">{{ $errors->first('to_branch') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Notes / Remarks</label>
                                        {{ Form::text('transfer_note', old('transfer_note'), ['class' => 'form-control', 'placeholder' => 'Notes / Remarks']) }}
                                        @error('transfer_note')
                                        <small class="text-danger">{{ $errors->first('transfer_note') }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-6">
                                        <h6>Product Details</h6>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p class="text-right"><a href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success addProductTransferRow"></i></a></p>
                                    </div>
                                    <div class="col-md-12 table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Batch</th>
                                                    <th>Qty</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody class="transfer ProductTransferRow">
                                                <tr>
                                                    <td width="60%">
                                                        {{ Form::select('product[]', $products, old('product'), ['id' => 'pdct_0', 'class' => 'form-control form-control-md show-tick ms select2 selProductForTransfer', 'placeholder' => 'Select', 'required' => 'required']) }}
                                                        @error('product')
                                                        <small class="text-danger">{{ $errors->first('product') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td width="20%">
                                                        {{ Form::select('batch_number[]', array('' => 'Select'), null, ['id' => 'batch_0', 'class' => 'form-control form-control-md show-tick ms select2 bno', 'required' => 'required']) }}
                                                        @error('batch_number')
                                                        <small class="text-danger">{{ $errors->first('batch_number') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::number('qty[]', old('qty'), ['min' => 1,  'max' => '', 'step' => 1, 'class' => 'form-control form-control-md qty', 'placeholder' => '0', 'required' => 'required']) }}
                                                        @error('qty')
                                                        <small class="text-danger">{{ $errors->first('qty') }}</small>
                                                        @enderror
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button type="submit" class="btn btn-primary btn-submit">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
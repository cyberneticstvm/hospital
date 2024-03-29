@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Product Transfer</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if (count($errors) > 0)
                        <div role="alert" class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                        @endif
                        <form action="{{ route('product-transfer.update', $transfer->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Branch<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="from_branch" required='required'>
                                    <option value="0">Master Stock</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $transfer->from_branch == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Branch<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="to_branch" required='required'>
                                    <option value="">Select</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $transfer->to_branch == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Transfer Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ date('d/M/Y', strtotime($transfer->transfer_date)) }}" name="transfer_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy" required='required'>
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Transfer Note</label>
                                    <input type="text" value="{{ $transfer->transfer_note }}" name="transfer_note" class="form-control form-control-md" placeholder="Transfer Note">
                                </div>
                                <div class="col-sm-6 text-right">
                                    <p class= "text-right my-3"><a href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success addStockTransferRow"></i></a></p>
                                </div>
                            </div>
                            @if($transfer_details->isEmpty())
                            <div class="row mt-3">
                                <div class="col-sm-5">
                                    <label class="form-label">Product<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2 selProductForTransfer" data-placeholder="Select" name="product[]" required='required'>
                                    <option value="">Select</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Batch Number<sup class="text-danger">*</sup></label>
                                    <select name="batch_number[]" class="form-control form-control-md bno" required='required'>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label">Qty<sup class="text-danger">*</sup></label>
                                    <input type="number" name="qty[]" class="form-control form-control-md" placeholder="0" required='required'>
                                </div>
                            </div>
                            @else
                                @php $c = 0; @endphp
                                @foreach($transfer_details as $transfer)
                                @php $c++; @endphp
                                <div class="row mt-3">
                                    <div class="col-sm-5">
                                        @if($c == 1)<label class="form-label">Product<sup class="text-danger">*</sup></label>@endif
                                        <select class="form-control form-control-md show-tick ms select2 selProductForTransfer" data-placeholder="Select" name="product[]" required='required'>
                                        <option value="">Select</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ $transfer->product == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        @if($c == 1)<label class="form-label">Batch Number<sup class="text-danger">*</sup></label>@endif
                                        <select name="batch_number[]" class="form-control form-control-md bno" required='required'>
                                            <option value="{{ $transfer->batch_number }}">{{ $transfer->batch_number }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        @if($c == 1)<label class="form-label">Qty<sup class="text-danger">*</sup></label>@endif
                                        <input type="number" name="qty[]" class="form-control form-control-md" placeholder="0" value="{{ $transfer->qty }}" required='required'>
                                    </div>
                                    <div class="col-sm-1">@if($c > 1)<i class="fa fa-trash text-danger" style="cursor:pointer" onClick="$(this).parent().parent().remove();">@endif</i></div>
                                </div>
                                @endforeach
                            @endif
                            <div class="stockTransferRow"></div>
                            <div class="row mt-3">
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
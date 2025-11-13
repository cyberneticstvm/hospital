@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Pharmacy Report</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.patient.pharmacy.fetch') }}" method="post">
                            @csrf
                            @php $today = date('d/M/Y') @endphp
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs) ? $inputs[0] : $today }}" name="fromdate" class="form-control form-control-md dtpicker">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
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
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('todate')
                                    <small class="text-danger">{{ $errors->first('todate') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Product</label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="product">
                                        <option value="">Select</option>
                                        @foreach($products as $key => $product)
                                        <option value="{{ $product->id }}" {{ ($inputs && $inputs[2] == $product->id) ? 'selected'  : '' }}>{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product')
                                    <small class="text-danger">{{ $errors->first('product') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Branch<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="branch">
                                        @foreach($branches as $key => $branch)
                                        <option value="{{ $branch->id }}" {{ ($inputs && $inputs[3] == $branch->id) ? 'selected'  : '' }}>{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch')
                                    <small class="text-danger">{{ $errors->first('branch') }}</small>
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
                    <div class="card-body">
                        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>MR.ID</th>
                                    <th>Patient Name</th>
                                    <th>Patient ID</th>
                                    <th>Doctor</th>
                                    <th>Prescription</th>
                                    <th>Bill No</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach($records as $med)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $med->medical_record_id }}</td>
                                    <td>{{ $med->patient_name }}</td>
                                    <td>{{ $med->patient_id }}</td>
                                    <td>{{ $med->doctor_name }}</td>
                                    <td class="text-center"><a href="/generate-pharmacy-out/{{ $med->medical_record_id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                                    <td>{{ $med->id }}</td>
                                    <td class="text-center">
                                        @if($med->status == 1)
                                        <a href='/generate-pharmacy-bill/{{ $med->medical_record_id }}/' target='_blank'><i class="fa fa-file text-info"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="fw-bold text-end" colspan="7">Total</td>
                                    <td class="text-end fw-bold">{{ number_format($records->sum('total'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
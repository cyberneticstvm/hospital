@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Patient Outstanding Due</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            @if (Session::has('error'))
                            <div class="text-danger text-center mt-2">
                                <h5>{{ Session::get('error') }}</h5>
                            </div>
                            @endif
                            @if (Session::has('success'))
                            <div class="text-success text-center mt-2">
                                <h5>{{ Session::get('success') }}</h5>
                            </div>
                            @endif
                        </div>
                        <form action="{{ route('patient.outstanding.due.fetch') }}" method="post">
                            @csrf
                            @php $today = date('d/M/Y') @endphp
                            <div class="row g-4">
                                <div class="col-12 text-danger">Date range should <strong>NOT</strong> be more than 30 days!</div>
                                <div class="col-sm-3">
                                    <label class="form-label">From Date</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs && $inputs[0]) ? $inputs[0] : $today }}" name="fromdate" class="form-control form-control-md dtpicker">
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
                                        <input type="text" value="{{ ($inputs && $inputs[1]) ? $inputs[1] : $today }}" name="todate" class="form-control form-control-md dtpicker">
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
                                <div class="col-sm-4">
                                    <label class="form-label">Branch<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="branch">
                                        @foreach($branches as $key => $branch)
                                        <option value="{{ $branch->id }}" {{ $branch->id == $brn ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch')
                                    <small class="text-danger">{{ $errors->first('branch') }}</small>
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
                @if(!empty($outstandings))
                <h5 class="mb-3 mt-3">Patient Outstanding Due Register</h5>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Patient Name</th>
                                    <th>Patient ID</th>
                                    <th class="text-end">Total Due</th>
                                    <th class="text-end">Recieved</th>
                                    <th class="text-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $tot = 0; $duetot = 0; $paidtot = 0 @endphp
                                @forelse($outstandings as $key => $outstanding)
                                @php
                                $tot += $outstanding['balance'];
                                $duetot += $outstanding['due'];
                                $paidtot += $outstanding['received'];
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><a href="{{ route('patient.transaction.history.fetch1', $outstanding['patient_id']) }}">{{ $outstanding['patient_name'] }}</a></td>
                                    <td>{{ $outstanding['patient_id'] }}</td>
                                    <td class="text-end">{{ number_format($outstanding['due'], 2) }}</td>
                                    <td class="text-end">{{ number_format($outstanding['received'], 2) }}</td>
                                    <td class="text-end">{{ number_format($outstanding['balance'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <p class="text-danger text-center">No records found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="fw-bold">Total</td>
                                    <td class="fw-bold text-end">{{ number_format($duetot, 2) }}</td>
                                    <td class="fw-bold text-end">{{ number_format($paidtot, 2) }}</td>
                                    <td class="fw-bold text-danger text-end">{{ number_format($tot, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
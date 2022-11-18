@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Search Keratometry/Tonometry/A-Scan</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('fetch.kta') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs) ? $inputs[0] : date('d/M/Y') }}" name="from_date" class="form-control form-control-md dtpicker">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('from_date')
                                    <small class="text-danger">{{ $errors->first('from_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs) ? $inputs[1] : date('d/M/Y') }}" name="to_date" class="form-control form-control-md dtpicker">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('to_date')
                                    <small class="text-danger">{{ $errors->first('to_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Type</label>
                                    <select class="form-control form-control-md select2" name="type">
                                        <option value="k" {{ ($inputs && $inputs[2] == 'k') ? 'selected' : '' }}>Keratometry</option>
                                        <option value="t" {{ ($inputs && $inputs[2] == 't') ? 'selected' : '' }}>Tonometry</option>
                                        <option value="a" {{ ($inputs && $inputs[2] == 'a') ? 'selected' : '' }}>A-Scan</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Branch</label>
                                    <select class="form-control form-control-md select2" name="branch">
                                        <option value="0">Select</option>
                                        @forelse($branches as $key => $branch)
                                            <option value="{{ $branch->id }}" {{ ($inputs && $inputs[3] == $branch->id) ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Search</button>
                                </div>
                            </div>
                        </form>
                        <div class="row mt-5">
                            <div class="col-md-12 table-responsive">
                                <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                                <thead><tr><th>SL No.</th><th>Patient Name</th><th>Patient ID</th><th>MR.ID</th><th>Branch</th><th>Date</th><th>Report</th><th>Receipt</th><th>Edit</th></tr></thead><tbody>
                                @php $i = 0; @endphp
                                @forelse($kta as $key => $row)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $row->patient_name }}</td>
                                        <td>{{ $row->patient_id }}</td>
                                        <td>{{ $row->medical_record_id }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        <td>{{ $row->rdate }}</td>
                                        @if($inputs[2] == 'k')
                                        <td><a href="/keratometry/report/{{ $row->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                                        <td><a href="/keratometry/receipt/{{ $row->id }}/" target="_blank"><i class="fa fa-file-o text-danger"></i></a></td>
                                        <td><a class='btn btn-link' href="{{ route('keratometry.edit', $row->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                        @elseif($inputs[2] == 't')
                                        <td><a href="/tonometry/report/{{ $row->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                                        <td><a href="/tonometry/receipt/{{ $row->id }}/" target="_blank"><i class="fa fa-file-o text-danger"></i></a></td>
                                        <td><a class='btn btn-link' href="{{ route('tonometry.edit', $row->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                        @else
                                        <td><a href="/ascan/report/{{ $row->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                                        <td><a href="/ascan/receipt/{{ $row->id }}/" target="_blank"><i class="fa fa-file-o text-danger"></i></a></td>
                                        <td><a class='btn btn-link' href="{{ route('ascan.edit', $row->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                        @endif
                                    </tr>
                                @empty

                                @endforelse
                                </tbody></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
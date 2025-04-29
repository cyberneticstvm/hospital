@extends("templates.base")
@section("content")
<div class="body-header d-flex py-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="fs-4 mt-1 mb-0">Welcome {{ Auth::user()->name }}!</h1>
                <small class="text-muted">You are viewing Devi Eye Hospital's Interactive Dashboard.</small>
            </div>
            <div class="col d-flex justify-content-lg-end mt-2 mt-md-0">
                <div class="p-2 me-md-3">
                    <div><span class="h6 mb-0 fw-bold">{{ number_format($day_tot_income, 2) }}</span> <small class="text-success"><i class="fa fa-angle-up"></i></small></div>
                    <small class="text-muted text-uppercase">Income Today</small>
                </div>
                <div class="p-2 me-md-3">
                    <div><span class="h6 mb-0 fw-bold">{{ number_format($day_tot_exp, 2) }}</span> <small class="text-danger"><i class="fa fa-angle-down"></i></small></div>
                    <small class="text-muted text-uppercase">Expense Today</small>
                </div>
                <div class="p-2 pe-lg-0">
                    <div><span class="h6 mb-0 fw-bold">{{ number_format($day_tot_income-$day_tot_exp, 2) }}</span> <small class="text-success"><i class="fa fa-angle-up"></i></small></div>
                    <small class="text-muted text-uppercase">Revenue Today</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="body d-flex py-lg-4 py-3">
    <div class="container">
        <div class="row g-3 clearfix">
            <div class="col-lg-8 col-md-12">
                <div class="row g-3 clearfix row-deck">
                    <div class="col-xl-3 col-lg-6 col-md-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <span class="text-uppercase text-primary">New Registrations</span>
                                <h4 class="mb-0 mt-2">{{ $new_patients_count }}</h4>
                                <small class="text-muted">Analytics today</small>
                            </div>
                            <div id="apexspark1"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <span class="text-uppercase text-primary">Reviews</span>
                                <h4 class="mb-0 mt-2">{{ $review_count }}</h4>
                                <small class="text-muted">Analytics today</small>
                            </div>
                            <div id="apexspark2"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <span class="text-uppercase text-primary">Total Consultation</span>
                                <h4 class="mb-0 mt-2">{{ $consultation }}</h4>
                                <small class="text-muted">All Branches Tot: {{ $consultation_all_br }}</small>
                            </div>
                            <div id="apexspark3"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-12 col-md-3 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <span class="text-uppercase text-danger">Cancelled</span>
                                <h4 class="mb-0 mt-2">{{ $cancelled }}</h4>
                                <small class="text-muted">Analytics today</small>
                            </div>
                            <div id="apexspark4"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header py-3 d-flex flex-wrap  justify-content-between align-items-center bg-transparent border-bottom-0">
                                <div>
                                    <h6 class="card-title m-0">Patient Overview Last 12 months - <span class="text-primary">{{ DB::table('branches')->where('id', Session::get('branch'))->value('branch_name') }}</span></h6>
                                    <small class="text-muted">Or you can <a href="#">sync data to Dashboard</a> to ensure your data is always up-to-date.</small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted d-none d-sm-inline-block" type="button"><i class="fa fa-download"></i></button>
                                    <button class="btn btn-sm btn-link text-muted d-none d-sm-inline-block" type="button"><i class="fa fa-external-link"></i></button>
                                    <button class="btn btn-sm btn-link text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item" href="#">Action<i class="fa fa-arrow-right"></i></a></li>
                                        <li><a class="dropdown-item" href="#">Another action<i class="fa fa-arrow-right"></i></a></li>
                                        <li><a class="dropdown-item" href="#">Something else here<i class="fa fa-arrow-right"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-header border">
                                    <div class="d-flex flex-row align-items-center">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ number_format($tot_patients, 0) }}</h6>
                                            <small class="text-muted font-11">Registered Patients</small>
                                        </div>
                                        <div class="ms-lg-5 ms-md-4 ms-3">
                                            <h6 class="mb-0 fw-bold">0</h6>
                                            <small class="text-muted font-11">Appointments</small>
                                        </div>
                                    </div>
                                </div>
                                <div id="patientOverview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="row g-3 row-deck">
                    <div class="col-lg-12 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="oneExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active" data-bs-interval="3000">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-fill me-3 text-truncate">
                                                    <div class="text-primary mb-2 text-uppercase">Consultation</div>
                                                    <h4 class="mb-0">{{ $consultation }}
                                                        <div class="fs-6 d-inline"> <small class="text-muted"><i class="fa fa-level-up text-primary"></i> Nos.</small></div>
                                                    </h4>
                                                    <small></small>
                                                </div>
                                                <div class="avatar lg rounded-circle no-thumbnail bg-primary text-light"><i class="fa fa-stethoscope fa-lg"></i></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item" data-bs-interval="3000">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-fill me-3 text-truncate">
                                                    <div class="text-success mb-2 text-uppercase">Certificate</div>
                                                    <h4 class="mb-0">{{ $certificate }}
                                                        <div class="fs-6 d-inline"> <small class="text-muted"><i class="fa fa-level-up text-success"></i> Nos.</small></div>
                                                    </h4>
                                                </div>
                                                <div class="avatar lg rounded-circle no-thumbnail bg-success text-light"><i class="fa fa-stethoscope fa-lg"></i></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item" data-bs-interval="3000">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-fill me-3 text-truncate">
                                                    <div class="text-info mb-2 text-uppercase">Camp</div>
                                                    <h4 class="mb-0">{{ $camp }}
                                                        <div class="fs-6 d-inline"> <small class="text-muted"><i class="fa fa-level-up text-info"></i> Nos.</small></div>
                                                    </h4>
                                                </div>
                                                <div class="avatar lg rounded-circle no-thumbnail bg-info text-light"><i class="fa fa-stethoscope fa-lg"></i></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item" data-bs-interval="3000">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-fill me-3 text-truncate">
                                                    <div class="text-warning mb-2 text-uppercase">Vision</div>
                                                    <h4 class="mb-0">{{ $vision }}
                                                        <div class="fs-6 d-inline"> <small class="text-muted"><i class="fa fa-level-up text-info"></i> Nos.</small></div>
                                                    </h4>
                                                </div>
                                                <div class="avatar lg rounded-circle no-thumbnail bg-warning text-light"><i class="fa fa-stethoscope fa-lg"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">Analytics today</small>
                            </div>
                            <div id="apexspark5"></div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-4 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                                <div>
                                    <h6 class="card-title m-0">Patient Overview Current month (All Branches)</h6>
                                    <small class="text-muted">Or you can <a href="#">sync data to Dashboard</a> to ensure your data is always up-to-date.</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="patientmonth"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- .row end -->
            </div>
        </div>
        <div class="row g-3 clearfix">
            <div class="col-lg-8 col-md-12">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header py-3 d-flex flex-wrap  justify-content-between align-items-center bg-transparent border-bottom-0">
                            <div>
                                <h6 class="card-title m-0">Income/Expense Overview Current Month - <span class="text-primary">{{ DB::table('branches')->where('id', Session::get('branch'))->value('branch_name') }}</span></h6>
                                <small class="text-muted">Or you can <a href="#">sync data to Dashboard</a> to ensure your data is always up-to-date.</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted d-none d-sm-inline-block" type="button"><i class="fa fa-download"></i></button>
                                <button class="btn btn-sm btn-link text-muted d-none d-sm-inline-block" type="button"><i class="fa fa-external-link"></i></button>
                                <button class="btn btn-sm btn-link text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="#">Action<i class="fa fa-arrow-right"></i></a></li>
                                    <li><a class="dropdown-item" href="#">Another action<i class="fa fa-arrow-right"></i></a></li>
                                    <li><a class="dropdown-item" href="#">Something else here<i class="fa fa-arrow-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-header border">
                                <div class="d-flex flex-row align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">₹ {{ number_format($income_monthly, 2) }}</h6>
                                        <small class="text-muted font-11">Income</small>
                                    </div>
                                    <div class="ms-lg-5 ms-md-4 ms-3">
                                        <h6 class="mb-0 fw-bold">₹ {{ number_format($expense_monthly, 2) }}</h6>
                                        <small class="text-muted font-11">Expenses</small>
                                    </div>
                                </div>
                            </div>
                            <div id="incomeexpense"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="row g-3 row-deck">
                    <div class="col-lg-12 col-md-4 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                                <div>
                                    <h6 class="card-title m-0">Review Overview Current month (All Branches)</h6>
                                    <small class="text-muted">Or you can <a href="#">sync data to Dashboard</a> to ensure your data is always up-to-date.</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="reviewmonth"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- .row end -->
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<input type="hidden" id="branch_selector" value="{{ Session::get('branch') }}">
<div class="modal fade branchSelector" id="staticBackdropLive" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('store_branch_session') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLiveLabel">Branch Selector</h5>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <label class="form-label">Select Branch<sup class="text-danger">*</sup></label>
                        <select class="form-control form-control-md show-tick ms" data-placeholder="Select" name="branch_id">
                            <option value="">Select</option>
                            @isset($branches)
                            @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>{{ $br->branch_name }}</option>
                            @endforeach
                            @endisset;
                        </select>
                        @error('branch_id')
                        <small class="text-danger">{{ $errors->first('branch_id') }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/logout/" class="btn btn-danger">Close</a>
                    <button type="submit" class="btn btn-submit btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
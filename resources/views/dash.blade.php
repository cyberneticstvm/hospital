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
                    <div><span class="h6 mb-0 fw-bold">8.18K</span> <small class="text-success"><i class="fa fa-angle-up"></i> 1.3%</small></div>
                    <small class="text-muted text-uppercase">Income</small>
                </div>
                <div class="p-2 me-md-3">
                    <div><span class="h6 mb-0 fw-bold">1.11K</span> <small class="text-success"><i class="fa fa-angle-up"></i> 4.1%</small></div>
                    <small class="text-muted text-uppercase">Expense</small>
                </div>
                <div class="p-2 pe-lg-0">
                    <div><span class="h6 mb-0 fw-bold">3.66K</span> <small class="text-danger"><i class="fa fa-angle-down"></i> 7.5%</small></div>
                    <small class="text-muted text-uppercase">Revenue</small>
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
                    <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <span class="text-uppercase">New Sessions</span>
                                <h4 class="mb-0 mt-2">22,500</h4>
                                <small class="text-muted">Analytics for last week</small>
                            </div>
                            <div id="apexspark1"></div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <span class="text-uppercase">TIME ON SITE</span>
                                <h4 class="mb-0 mt-2">1,070</h4>
                                <small class="text-muted">Analytics for last week</small>
                            </div>
                            <div id="apexspark2"></div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12 col-md-4 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <span class="text-uppercase">BOUNCE RATE</span>
                                <h4 class="mb-0 mt-2">10K</h4>
                                <small class="text-muted">Analytics for last week</small>
                            </div>
                            <div id="apexspark3"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header py-3 d-flex flex-wrap  justify-content-between align-items-center bg-transparent border-bottom-0">
                                <div>
                                    <h6 class="card-title m-0">Audience Overview</h6>
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
                                            <h6 class="mb-0 fw-bold">$3,056</h6>
                                            <small class="text-muted font-11">Rate</small>
                                        </div>
                                        <div class="ms-lg-5 ms-md-4 ms-3">
                                            <h6 class="mb-0 fw-bold">$1,998</h6>
                                            <small class="text-muted font-11">Value</small>
                                        </div>
                                        <div class="d-none d-sm-block ms-auto">
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1">
                                                <label class="btn btn-outline-secondary" for="btnradio1">Week</label>

                                                <input type="radio" class="btn-check" name="btnradio" id="btnradio2">
                                                <label class="btn btn-outline-secondary" for="btnradio2">Month</label>

                                                <input type="radio" class="btn-check" name="btnradio" id="btnradio3" checked="">
                                                <label class="btn btn-outline-secondary" for="btnradio3">Year</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="apex-AudienceOverview"></div>
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
                                <span class="text-uppercase">GOAL COMPLETIONS</span>
                                <h4 class="mb-0 mt-2">$1,22,500</h4>
                                <small class="text-muted">Analytics for last week</small>
                            </div>
                            <div id="apexspark4"></div>
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
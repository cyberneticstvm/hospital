@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Inhouse Camp</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                        @endif
                        <form action="{{ route('inhousecamp.update', $camp->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ date('d/M/Y', strtotime($camp->from_date)) }}" name="from_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('from')
                                    <small class="text-danger">{{ $errors->first('from') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ date('d/M/Y', strtotime($camp->to_date)) }}" name="to_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('to')
                                    <small class="text-danger">{{ $errors->first('to') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Camp Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $camp->name }}" name="name" class="form-control form-control-md" placeholder="Camp Name">
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Proc. Validity in Days<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ $camp->validity }}" name="validity" class="form-control form-control-md" placeholder="0 Days">
                                    @error('validity')
                                    <small class="text-danger">{{ $errors->first('validity') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Status<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="status">
                                    <option value="">Select</option>
                                    <option value="1" {{ ($camp->status == 1) ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ ($camp->status == 0) ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                    @enderror
                                </div>
                                <div class="row mt-3">
                                    <div class="col"><h5 class="text-primary">Procedures</h5></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        @forelse($procedures as $key => $proc)
                                        <div class="col-sm-2 form-check form-check-inline">
                                                <label class="form-check-label" for="flexCheckDefault">{{ $proc->name }}</label>{{ Form::checkbox('procedure[]', $proc->id, in_array($proc->id, $proc->inhouseprocedures->where('camp_id', $camp->id)->pluck('procedure')->all()) ? true : false, array('class' => 'name, form-check-input')) }}
                                        </div>
                                        @empty
                                        @endforelse;
                                    </div>
                                    @error('procedure')
                                    <small class="text-danger">{{ $errors->first('procedure') }}</small>
                                    @enderror
                                </div>       
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
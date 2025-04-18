@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Add New Promotion</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('promotion.schedule.save') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label req">Schedule Name</label>
                                    {{ Form::text($name = 'name', old('name'), ['class' => 'form-control', 'placeholder' => 'Schedule Name']) }}
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label req">Schedule Date</label>
                                    {{ Form::date($name = 'scheduled_date', old('scheduled_date'), ['class' => 'form-control']) }}
                                    @error('scheduled_date')
                                    <small class="text-danger">{{ $errors->first('scheduled_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label req">Template Id</label>
                                    {{ Form::text($name = 'template_id', old('template_id'), ['class' => 'form-control', 'placeholder' => 'Template Id']) }}
                                    @error('template_id')
                                    <small class="text-danger">{{ $errors->first('template_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label req">Template Language</label>
                                    {{ Form::select($name = 'template_language', array('en' => 'English', 'en_GB' => 'English UK', 'en_US' => 'English US', 'en_IN' => 'English India', 'ml' => 'Malayalam'), old('template_language'), ['class' => 'form-control']) }}
                                    @error('template_language')
                                    <small class="text-danger">{{ $errors->first('template_language') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label req">SMS Limit per Hour</label>
                                    {{ Form::text($name = 'sms_limit_per_hour', old('sms_limit_per_hour'), ['class' => 'form-control', 'placeholder' => '0']) }}
                                    @error('sms_limit_per_hour')
                                    <small class="text-danger">{{ $errors->first('sms_limit_per_hour') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label req">Entity</label>
                                    {{ Form::select('entity', array('hospital' => 'Hospital'), old('entity'), ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                    @error('entity')
                                    <small class="text-danger">{{ $errors->first('entity') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label req">Branch</label>
                                    {{ Form::select('branch_id', $branches, old('branch_id'), ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                    @error('branch_id')
                                    <small class="text-danger">{{ $errors->first('branch_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label req">Status</label>
                                    {{ Form::select('status', array('publish' => 'Publish', 'draft' => 'Draft'), old('status'), ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                    @error('status')
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                    @enderror
                                </div>
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
        </div> <!-- .row end -->
    </div>
</div>
@endsection
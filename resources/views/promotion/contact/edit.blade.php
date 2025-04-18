@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Contact</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('promotion.contact.update', $contact->id) }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label req">Customer Name</label>
                                    {{ Form::text($name = 'name', $contact->name, ['class' => 'form-control', 'placeholder' => 'Customer Name']) }}
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label req">Contact Number</label>
                                    {{ Form::text($name = 'contact_number', $contact->contact_number, ['class' => 'form-control', 'placeholder' => 'Contact Number', 'maxlength' => 10]) }}
                                    @error('contact_number')
                                    <small class="text-danger">{{ $errors->first('contact_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label req">Type</label>
                                    {{ Form::select('type', array('include' => 'Include', 'exclude' => 'Exclude'), $contact->type, ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                    @error('type')
                                    <small class="text-danger">{{ $errors->first('type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label req">Entity</label>
                                    {{ Form::select('entity', array('hospital' => 'Hospital'), $contact->entity, ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                    @error('entity')
                                    <small class="text-danger">{{ $errors->first('entity') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label req">Branch</label>
                                    {{ Form::select('branch_id', $branches, $contact->branch_id, ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                    @error('branch_id')
                                    <small class="text-danger">{{ $errors->first('branch_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
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
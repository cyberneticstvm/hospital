@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Medical Fitness</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-3">MR.ID: <h5 class="text-primary">{{ $mrecord->id }}</h5></div>
                            <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ ($patient) ? $patient->patient_name : '' }}</h5></div>
                            <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ ($patient) ? $patient->patient_id : '' }}</h5></div>
                            <div class="col-sm-3">Branch: <h5 class="text-primary">{{ $branch->branch_name }}</h5></div>
                        </div>
                        <form action="{{ route('mfit.save') }}" method="post">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <label class="form-label">Fitness Advice<sup class="text-danger">*</sup></label>
                                    <select class="form-control" name="fitness_advice">
                                        <option value="">Select</option>
                                        @forelse($stypes as $key => $stype)
                                            <option value="{{ $stype->id }}">{{ $stype->surgery_name }} ({{ $stype->fitness_advice }})</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <!--<textarea name="fitness_advice" class="form-control" rows="5" placeholder="Fitness Advice">{{ ($surgery && $surgery->fitness_advice) ? $surgery->fitness_advice : '' }}</textarea>-->
                                    @error('fitness_advice')
                                    <small class="text-danger">{{ $errors->first('fitness_advice') }}</small>
                                    @enderror
                                </div>                                
                                <div class="col-sm-12">
                                    <label class="form-label">Notes<sup class="text-danger">*</sup></label>
                                    <textarea name="notes" class="form-control" rows="5" placeholder="Notes">{{ old('notes') }}</textarea>
                                    @error('notes')
                                    <small class="text-danger">{{ $errors->first('notes') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 text-end">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
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
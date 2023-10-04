@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Fetch Patient ID</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient.transaction.history.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Patient ID<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ old('patient_id') }}" name="patient_id" class="form-control form-control-md" placeholder="Mediical Record ID">
                                    @error('patient_id')
                                    <small class="text-danger">{{ $errors->first('patient_id') }}</small>
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
                <h5 class="mb-3 mt-3">Patient Transaction History</h5>
                <div class="card">
                    
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
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
                                    <label class="form-label">Patient ID (<small>Number Parts Only</small>)<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ old('patient_id') }}" name="patient_id" class="form-control form-control-md" placeholder="Patient ID">
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
                    @if($mrns)
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-6"><h5 class="text-primary"> Name: {{ (!empty($patient) && $patient->patient_name) ? $patient->patient_name : '' }}</h5></div>
                            <div class="col-6"><h5 class="text-primary">Patient ID: {{ (!empty($patient) && $patient->patient_id) ? $patient->patient_id : '' }}</h5></div>
                        </div>
                        <table class="table table-sm dataTable table-striped table-hover align-middle">
                            <thead><tr><th>SL No.</th><th>MRN</th><th class="text-end">Consumed</th><th class="text-end">Recieved</th><th class="text-end">Balance</th></tr></thead>
                            <tbody>
                                @forelse($mrns as $key => $mrn)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
                                        <td>{{ $mrn->id }}</td>
                                        <td class="text-end">{{  number_format(App\Helper\Helper::getOwedTotal($mrn->id), 2) }}</td>
                                        <td class="text-end">{{  number_format(App\Helper\Helper::getPaidTotal($mrn->id), 2) }}</td>
                                        <td class="text-end">{{ number_format(App\Helper\Helper::getOwedTotal($mrn->id) - App\Helper\Helper::getPaidTotal($mrn->id), 2) }}</td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="text-danger">No records found!</p>
                    @endif
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
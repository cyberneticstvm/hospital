@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Patient Outstandinng Due</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient.outstanding.due.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
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
                <h5 class="mb-3 mt-3">Patient Outstanding Due Register</h5>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                            <thead><tr><th>SL No.</th><th>Patient Name</th><th>Patient ID</th><th>Total Due</th><th>Recieved</th><th>Balance</th></tr></thead><tbody>
                            @php $tot = 0; @endphp
                            @forelse($outstandings as $key => $outstanding)
                                $tot += $outstanding->due - $outstanding->received;
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $outstanding->patient?->patient_name }}</td>                               
                                    <td>{{ $outstanding->patient?->patient_id }}</td>
                                    <td>{{ number_format($outstanding->due, 2) }}</td>                               
                                    <td>{{ number_format($outstanding->received, 2) }}</td>                               
                                    <td>{{ number_format($outstanding->balance, 2) }}</td>                               
                                </tr>
                            @empty
                            <tr><td colspan="6"><p class="text-danger text-center">No records found</p></td></tr>
                            @endforelse
                            </tbody>
                            <tfoot><tr><td colspan="5" class="fw-bold">Total</td class="fw-bold text-danger"><td>{{ number_format($tot, 2) }}</td></tr></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
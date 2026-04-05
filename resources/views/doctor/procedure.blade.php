@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Doctor Procedure</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-end">
            <div class="mb-3">
                <h5 class="mb-0">Procedures for {{ $doctor->doctor_name }}</h5>
                <span class="text-muted"></span>
            </div>
        </div>
        <div class="card">
            <form method="post" action="{{ route('doctor.procedure.update') }}">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}" />
                <div class="card-body table-responsive">
                    <table class="table table-sm dataTable table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>SL No.</th>
                                <th>Procedure</th>
                                <th>Discount %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($procedures as $key => $proc)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $proc->name }}</td>
                                <td>
                                    <input type="hidden" name="proc[]" value="{{ $proc->id }}" />
                                    <input type="number" name="disc[]" class="form-control" min="0" max="100" step="1" value="{{ $procs->where('procedure_id', $proc->id)?->first()?->discount_percentage ?? 0 }}" />
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
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
@endsection
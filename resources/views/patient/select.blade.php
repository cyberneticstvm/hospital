@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Patient(s) Data Found</h5>
        <span class="text-muted">Following patients are found in the system with the same mobile number.</span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <form method="post" action="{{ route('patient.proceed') }}">
        @csrf
        <table id="dataTbl" class="table display dataTable table-striped table-sm table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Patient Name</th><th>Patient ID</th><th>Address</th><th>Mobile Number</th><th>Select</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($patients as $patient)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $patient->patient_name }}</td>
                    <td>{{ $patient->patient_id }}</td>
                    <td>{{ $patient->address }}</td>
                    <td>{{ $patient->mobile_number }}</td>
                    <td class="text-center"><input type="radio" class="form-check-input" name="rad" value="{{ $patient->id }}"></td>
                </tr>
            @endforeach
        </tbody></table>
        <div class="text-center"><button type="submit" class="btn btn-primary btn-submit">Proceed</button></div>
        </form>
    </div>
</div>
@endsection
@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Tests Advised Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <!--<p class= "text-right my-3"><a href="#"><i class="fa fa-plus fa-lg text-success"></i></a></p>-->
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead><tr><th>SL No.</th><th>MR ID.</th><th>Patient Name</th><th>Patient ID</th><th>Doctor</th><th>Test</th><th>Notes</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($tests as $test)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $test->medical_record_id }}</td>
                    <td>{{ $test->patient->patient_name }}</td>
                    <td>{{ $test->patient->patient_id }}</td>
                    <td>{{ $test->doctor->doctor_name }}</td>
                    <td>{{ $test->test()->find($test->test)->name }}</td>
                    <td>{{ $test->notes }}</td>
                    <td><a class='btn btn-link' href="{{ route('tests.advised.edit', $test->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection
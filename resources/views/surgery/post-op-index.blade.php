@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Post Operative Suggestions Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <!--<p class= "text-right my-3"><a href="#"><i class="fa fa-plus fa-lg text-success"></i></a></p>-->
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead><tr><th>SL No.</th><th>MR ID.</th><th>Patient Name</th><th>Patient ID</th><th>Phone Number</th><th>Doctor Name</th><th>Surg. Date</th><th>Surgery Name</th><th>Eye</th><th>Surgeon</th><th>Status</th><th>notes</th><th>History</th><th>Medicine</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($surgeries as $surgery)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $surgery->medical_record_id }}</td>
                    <td>{{ $surgery->patient_name }}</td>
                    <td>{{ $surgery->patient_id }}</td>
                    <td>{{ $surgery->mobile_number }}</td>
                    <td>{{ $surgery->doctor_name }}</td>
                    <td>{{ $surgery->sdate }}</td>
                    <td>{{ $surgery->surgery_name }}</td>
                    <td>{{ ucfirst($surgery->eye) }}</td>
                    <td>{{ $surgery->surgeon }}</td>
                    <td>{{ $surgery->sname }}</td>
                    <td>{{ $surgery->remarks }}</td>
                    <td class="text-center"><a href="/patient-history/{{ $surgery->pid }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                    <td class="text-center"><a href="/postop/medicine/{{ $surgery->id }}/"><i class="fa fa-plus text-primary"></i></a></td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection
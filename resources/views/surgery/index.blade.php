@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Surgery Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <!--<p class= "text-right my-3"><a href="#"><i class="fa fa-plus fa-lg text-success"></i></a></p>-->
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>MR ID.</th>
                    <th>Patient Name</th>
                    <th>Patient ID</th>
                    <th>Phone Number</th>
                    <th>Doctor Name</th>
                    <th>Surg. Date</th>
                    <th>Adv. Date</th>
                    <th>Adv. Br.</th>
                    <th>Surgery Name</th>
                    <th>Eye</th>
                    <th>Fee</th>
                    <th>Surgeon</th>
                    <th>Status</th>
                    <th>notes</th>
                    <th>History</th>
                    <th>Medicine</th>
                    <th>Edit</th><!--<th>Remove</th>-->
                </tr>
            </thead>
            <tbody>
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
                    <td>{{ $surgery->adate }}</td>
                    <td>{{ $surgery->advised_branch }}</td>
                    <td>{{ $surgery->surgery_name }}</td>
                    <td>{{ ucfirst($surgery->eye) }}</td>
                    <td>{{ $surgery->surgery_fee }}</td>
                    <td>{{ $surgery->surgeon }}</td>
                    <td>{{ $surgery->sname }}</td>
                    <td>{{ $surgery->remarks }}</td>
                    <td><a href="/patient-history/{{ $surgery->pid }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                    <td class="text-center"><a href="/surgery/medicine/{{ $surgery->id }}/"><i class="fa fa-plus text-primary"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('surgery.edit', $surgery->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <!--<td>
                        <form method="post" action="{{ route('surgery.delete', $surgery->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Doctor Account</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-end">
            <div class="mb-3">
                <span class="text-muted"></span>
            </div>
        </div>
        <div class="card">
            <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th>SL No.</th>
                        <th>Doctor</th>
                        <th>Medical Record Id</th>
                        <th>Procedure</th>
                        <th>Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $key => $item)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $item->doctor?->doctor_name }}</td>
                        <td>{{ $item->medical_record_id }}</td>
                        <td>{{ $item->procedure?->name }}</td>
                        <td>{{ $item->type }}</td>
                        <td class="text-right fw-bold">{{ $item->amount }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
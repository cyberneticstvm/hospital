@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">B2B Pharmacy Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class="text-right my-3"><a href="{{ route('pharmacy.b2b.create') }}"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Buyer Name</th>
                    <th>Address</th>
                    <th>Date</th>
                    <th>Contact</th>
                    <th>GSTIN</th>
                    <th>Receipt</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 0; @endphp
                @forelse($records as $key => $record)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $record->patient_name }}</td>
                    <td>{{ $record->other_info }}</td>
                    <td>{{ $record->created_at->format('d.M.Y') }}</td>
                    <td>{{ $record->contact }}</td>
                    <td>{{ $record->gstin }}</td>
                    <td class="text-center"><a href="#" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('pharmacy.b2b.edit', encrypt($record->id)) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('pharmacy.b2b.delete', encrypt($record->id)) }}">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
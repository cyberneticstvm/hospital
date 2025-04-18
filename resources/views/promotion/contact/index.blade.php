@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Promotion Contact Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class="text-right my-3"><a href="{{ route('promotion.contact.create') }}"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead>
                <tr>
                    <th>SL No</th>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Entity</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $key => $contact)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $contact->created_at->format('d.M.Y') }}</td>
                    <td>{{ $contact->branch->name }}</td>
                    <td>{{ ucfirst($contact->entity) }}</td>
                    <td class="{{ ($contact->type == 'include') ? 'text-success' : 'text-danger' }}">{{ ucfirst($contact->type) }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->contact_number }}</td>
                    <td class="text-center"><a href="{{ route('promotion.contact.edit', encrypt($contact->id)) }}"><i class="fa fa-edit text-muted fa-lg"></i></a></td>
                    @if($contact->deleted_at)
                    <td>
                        <form method="get" action="{{ route('promotion.contact.restore', encrypt($contact->id)) }}">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to restore this Record?');"><i class="fa fa-recycle text-success"></i></button>
                        </form>
                    </td>
                    @else
                    <td>
                        <form method="get" action="{{ route('promotion.contact.delete', encrypt($contact->id)) }}">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                    @endif

                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
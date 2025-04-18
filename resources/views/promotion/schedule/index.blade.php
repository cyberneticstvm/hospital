@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Promotion Schedule Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class="text-right my-3"><a href="{{ route('promotion.schedule.create') }}"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead>
                <tr>
                    <th>SL No</th>
                    <th>Schedule Name</th>
                    <th>Scheduled Date</th>
                    <th>Branch</th>
                    <th>Tamplate ID</th>
                    <th>Language</th>
                    <th>Limit</th>
                    <th>Processed</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $key => $schedule)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $schedule->name }}</td>
                    <td>{{ $schedule->created_at->format('d.M.Y') }}</td>
                    <td>{{ $schedule->branch?->name ?? 'All' }}</td>
                    <td>{{ $schedule->template_id }}</td>
                    <td>{{ $schedule->template_language }}</td>
                    <td>{{ $schedule->sms_limit_per_hour }}</td>
                    <td class="text-center">{{ $schedule->waSmsProcessedCount() }}</td>
                    <td>{{ ucfirst($schedule->status) }}</td>
                    <td class="text-center"><a href="{{ route('promotion.schedule.edit', encrypt($schedule->id)) }}"><i class="fa fa-edit text-muted fa-lg"></i></a></td>
                    @if($schedule->deleted_at)
                    <td>
                        <form method="get" action="{{ route('promotion.schedule.restore', encrypt($schedule->id)) }}">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to restore this Record?');"><i class="fa fa-recycle text-success"></i></button>
                        </form>
                    </td>
                    @else
                    <td>
                        <form method="get" action="{{ route('promotion.schedule.delete', encrypt($schedule->id)) }}">
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
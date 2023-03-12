@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Active Users</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-sm dataTable table-striped table-hover align-middle">
                            <thead><tr><th>SL No.</th><th>User Name</th><th>Device</th><th>Logged In</th><th>Location</th><th>IP</th></tr></thead><tbody>
                                @php $slno = 1; @endphp
                                @forelse($users as $key => $user)
                                    <tr>
                                        <td>{{ $slno++ }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->loginlog->where('session_id', $user->session_id)->pluck('device')->implode(',') }}</td>
                                        <td>{{ date('d/M/Y h:i a', strtotime($user->loginlog->where('session_id', $user->session_id)->pluck('logged_in')->implode(','))) }}</td>
                                        <td>{{ $user->loginlog->where('session_id', $user->session_id)->pluck('city_name')->implode(',') }}</td>
                                        <td>{{ $user->loginlog->where('session_id', $user->session_id)->pluck('ip')->implode(',') }}</td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
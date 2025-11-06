@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mt-3">
                @if (Session::has('error'))
                <div class="text-danger text-center mt-2">
                    <h5>{{ Session::get('error') }}</h5>
                </div>
                @endif
                @if (Session::has('success'))
                <div class="text-success text-center mt-2">
                    <h5>{{ Session::get('success') }}</h5>
                </div>
                @endif
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-5">
                                <h5 class="">Purchase Return</h5>
                                <form action="{{ route('purchase.return.fetch') }}" method="post">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-sm-4">
                                            <label class="form-label">Purchase Id / No<sup class="text-danger">*</sup></label>
                                            {{ Form::text('term', old('term') ?? $inputs[0], ['class' => 'form-control', 'placeholder' => 'Purchase Id / No']) }}
                                            @error('term')
                                            <small class="text-danger">{{ $errors->first('term') }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="">Purchase Return Register</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Return Id</th>
                                    <th>Supplier</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($preturns as $key => $preturn)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $preturn->id }}</td>
                                    <td>{{ $preturn->supplier->name }}</td>
                                    <td>{{ $preturn->notes }}</td>
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
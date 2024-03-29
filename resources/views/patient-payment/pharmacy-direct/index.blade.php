@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Fetch Pharmacy-Direct Record</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('paypharma.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Bill Number<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ old('bill_number') }}" name="bill_number" class="form-control form-control-md" placeholder="Bill Number">
                                    @error('bill_number')
                                    <small class="text-danger">{{ $errors->first('bill_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                </div>
                            </div>
                            @if (Session::has('error'))
                            <div class="text-danger mt-2">{{ Session::get('error') }} 
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
                <h5 class="mb-3 mt-3">Pharmacy Direct Payments Register</h5>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>Bill No.</th><th>Patient Name</th><th>Address</th><th>Amount</th><th>Payment Mode</th><th>Notes</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @foreach($incomes as $income)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $income->billno }}</td>
                                <td>{{ $income->patient_name }}</td>
                                <td>{{ $income->other_info }}</td>
                                <td class="text-right">{{ $income->amount }}</td>
                                <td>{{ $income->name }}</td>
                                <td>{{ $income->notes }}</td>
                                <td><a class='btn btn-link' href="{{ route('paypharma.edit', $income->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <!--<td>
                                    <form method="post" action="{{ route('paypharma.delete', $income->id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                                    </form>
                                </td>-->
                            </tr>
                        @endforeach
                        </tbody></table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
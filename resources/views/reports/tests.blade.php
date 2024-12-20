@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Tests Report</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.tests.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date <sup class="text-danger">*</sup></label>
                                    <input type="date" class="form-control" name="from_date" value="{{ $inputs[0] }}">
                                    @error('from_date')
                                    <small class="text-danger">{{ $errors->first('from_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date <sup class="text-danger">*</sup></label>
                                    <input type="date" class="form-control" name="to_date" value="{{ $inputs[1] }}">
                                    @error('to_date')
                                    <small class="text-danger">{{ $errors->first('to_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Branch </label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="branch">
                                        <option value="0">Select All</option>
                                        @foreach($branches as $key => $branch)
                                        <option value="{{ $branch->id }}" {{ ($inputs[2] == $branch->id) ? 'selected'  : '' }}>{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Procedure </label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="procedure">
                                        <option value="0">Select All</option>
                                        @foreach($procs as $key => $proc)
                                        <option value="{{ $proc->id }}" {{ ($inputs[3] == $proc->id) ? 'selected'  : '' }}>{{ $proc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">Fetch</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="dTblApp" class="table table-sm dataTable table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>MR Id</th>
                                    <th>Patient Name</th>
                                    <th>Patient ID</th>
                                    <th>Branch</th>
                                    <th>Test Name</th>
                                    <th>Date</th>
                                    <th>Created By</th>
                                    <th>Fee Procedure</th>
                                    <th>Fee Collected</th>
                                    <th>Fee Diff.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $tot1=0; $tot2=0;
                                @endphp
                                @forelse($records as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->medical_record_id }}</td>
                                    <td>{{ $row->patient->patient_name }}</td>
                                    <td>{{ $row->patient->patient_id }}</td>
                                    <td>{{ $row->branches->branch_name }}</td>
                                    <td>{{ $row->procedures->name }}</td>
                                    <td>{{ $row->created_at->format('d/M/Y') }}</td>
                                    <td>{{ $row->createdBy->name }}</td>
                                    <td class="text-end">{{ $row->procedures->fee }}</td>
                                    <td class="text-end">{{ $row->fee }}</td>
                                    <td class="text-end">{{ number_format($row->procedures->fee - $row->fee, 2) }}</td>
                                </tr>
                                @php $tot1 += $row->procedures->fee; $tot2 += $row->fee; @endphp
                                @empty
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8" class="text-end">Total</td>
                                    <td class="text-end fw-bold">{{ number_format($tot1, 2) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($tot2, 2) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($tot1 - $tot2, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection
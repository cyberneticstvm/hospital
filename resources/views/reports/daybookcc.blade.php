@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Daybook Consolidated</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.fetchdaybook.cc') }}" method="post">
                            @csrf
                            @php $today = date('d/M/Y') @endphp
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">From Date</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs) ? $inputs[0] : $today }}" name="fromdate" class="form-control form-control-md {{ ($is_admin || $is_accounts || $isCEO) ? 'dtpicker' : '' }}" {{ ($is_admin || $is_accounts || $isCEO) ? '' : 'readonly' }}>
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('fromdate')
                                    <small class="text-danger">{{ $errors->first('fromdate') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">To Date</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($inputs) ? $inputs[1] : $today }}" name="todate" class="form-control form-control-md {{ ($is_admin || $is_accounts || $isCEO) ? 'dtpicker' : '' }}" {{ ($is_admin || $is_accounts || $isCEO) ? '' : 'readonly' }}>
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('todate')
                                    <small class="text-danger">{{ $errors->first('todate') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Branch<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="branch">
                                        @foreach($branches as $key => $branch)
                                        <option value="{{ $branch->id }}" {{ ($inputs && $inputs[2] == $branch->id) ? 'selected'  : '' }}>{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch')
                                    <small class="text-danger">{{ $errors->first('branch') }}</small>
                                    @enderror
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
                @php $rtot = $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $pharmacy + $medicine + $vision + $clinical_lab + $radiology_lab + $surgery_medicine + $postop_medicine + $surgery_consumables; @endphp
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-sm dataTable table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Particulars</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Receivables Today</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Opening Balance</td>
                                    <td class="text-end text-danger">{{ number_format($opening_balance, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Income from Registration</td>
                                    <td class="text-end text-danger">{{ number_format($reg_fee_total, 2) }}</td>
                                    <td></td>
                                    <td class="text-right text-danger">{{ number_format($reg_fee_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Income from Consultation</td>
                                    <td class="text-end">{{ number_format($consultation_fee_total->sum('doctor_fee'), 2) }}</td>
                                    <td></td>
                                    <td class="text-right text-danger"><a class="daybook text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="consultationModal" data-bs-target="#consultationModal" data-title="Consultation Fee Detailed" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="consultation">{{ number_format($consultation_fee_total, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Income from Procedures</td>
                                    <td class="text-end">{{ number_format($procedure_fee_total, 2) }}</td>
                                    <td></td>
                                    <td class="text-right text-danger"><a class="daybook text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="procedureModal" data-bs-target="#procedureModal" data-title="Procedure Fee Detailed" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="procedure">{{ number_format($procedure_fee_total, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Income from Certificates</td>
                                    <td class="text-end">{{ number_format($certificate_fee_total, 2) }}</td>
                                    <td></td>
                                    <td class="text-right text-danger"><a class="daybook text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="certificateModal" data-bs-target="#certificateModal" data-title="Certificate Fee Detailed" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="certificate">{{ number_format($certificate_fee_total, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Income from Pharmacy (Direct)</td>
                                    <td class="text-end text-danger">{{ number_format($pharmacy, 2) }}</td>
                                    <td></td>
                                    <td class="text-right text-danger"><a class="daybook text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="pharmacyModal" data-bs-target="#pharmacyModal" data-title="Pharmacy Income Detailed (Direct)" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="pharmacy">{{ number_format($pharmacy, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Income from Pharmacy</td>
                                    <td class="text-end">{{ number_format($medicine, 2) }}</td>
                                    <td></td>
                                    <td class="text-right text-danger"><a class="daybook text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="medicineModal" data-bs-target="#medicineModal" data-title="Pharmacy Income Detailed" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="medicine">{{ number_format($medicine, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Income from Vision</td>
                                    <td class="text-end text-danger">{{ number_format($vision, 2) }}</td>
                                    <td></td>
                                    <td class="text-right text-danger"><a class="daybook text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="visionModal" data-bs-target="#visionModal" data-title="Vision Income Detailed" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="vision">{{ number_format($vision, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Income from Clinical Lab</td>
                                    <td class="text-end">{{ number_format($clinical_lab, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($clinical_lab, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Income from Radiology Lab</td>
                                    <td class="text-end">{{ number_format($radiology_lab, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($radiology_lab, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>Income from Surgery Medicine</td>
                                    <td class="text-end">{{ number_format($surgery_medicine, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($surgery_medicine, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>Income from PostOp Medicine</td>
                                    <td class="text-end">{{ number_format($postop_medicine, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($postop_medicine, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>13</td>
                                    <td>Income from Surgery Consumables</td>
                                    <td class="text-end">{{ number_format($surgery_consumables, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($surgery_consumables, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>14</td>
                                    <td>Income from Other Sources</td>
                                    <td class="text-end text-primary">{{ number_format($income, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Grand Total</td>
                                    <td class="text-end fw-bold">{{ number_format($income_total, 2) }}</td>
                                    <td></td>
                                    <td class="text-end text-danger">{{ number_format($rtot, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Expenses</td>
                                    <td class="text-end fw-bold">{{ number_format($expense, 2) }}</td>
                                    <td></td>
                                    <td class="text-end text-warning"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bg-primary"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Net Total</td>
                                    <td class="text-end fw-bold">{{ number_format($income_total - $expense, 2) }}</td>
                                    <td></td>
                                    <td class="text-end text-danger">{{ number_format($rtot, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bg-primary"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Payments Received in Cash</td>
                                    <td class="text-end fw-bold">{{ number_format($income_received_cash, 2) }}</td>
                                    <td></td>
                                    <td class="text-end"><a class="daybook text-primary" href="javascript:void(0)" data-bs-toggle="modal" data-modal="incomeCashModal" data-bs-target="#incomeCashModal" data-title="Payments received in Cash" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="incomecash">{{ number_format($income_received_cash, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Payments received (UPI/Bank Transfer)</td>
                                    <td class="text-end fw-bold">{{ number_format($income_received_upi, 2) }}</td>
                                    <td></td>
                                    <td class="text-end"><a class="daybook text-primary" href="javascript:void(0)" data-bs-toggle="modal" data-modal="incomeUpiModal" data-bs-target="#incomeUpiModal" data-title="Payments received through UPI/Bank Transfer" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="incomeupi">{{ number_format($income_received_upi, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Payments received (Cheque/Card)</td>
                                    <td class="text-end fw-bold">{{ number_format($income_received_card, 2) }}</td>
                                    <td></td>
                                    <td class="text-end"><a class="daybook text-primary" href="javascript:void(0)" data-bs-toggle="modal" data-modal="incomeCardModal" data-bs-target="#incomeCardModal" data-title="Payments received through Card/Cheque" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="incomecard">{{ number_format($income_received_card, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Payments received (Staff)</td>
                                    <td class="text-end fw-bold">{{ number_format($income_received_staff, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Patient Outstanding Due</td>
                                    <td class="text-end fw-bold">{{ number_format($outstanding, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">0.00</td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Patient Outstanding Received (UPI/Card/Transfer)</td>
                                    <td class="text-end fw-bold">{{ number_format($outstanding_received_other, 2) }}</td>
                                    <td></td>
                                    <td class="text-end"><a class="daybook text-primary" href="javascript:void(0)" data-bs-toggle="modal" data-modal="outstandingReceivedCardModal" data-bs-target="#outstandingReceivedCardModal" data-title="Outstanding Payments Received" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="outstandingReceived">{{ number_format($outstanding_received_other, 2) }}</a></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Patient Outstanding Received (Cash)</td>
                                    <td class="text-end fw-bold">{{ number_format($outstanding_received, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($outstanding_received, 2) }}</td>
                                </tr>

                                <tr>
                                    <td colspan="5" class="bg-primary"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Balance</td>
                                    <td class="text-end fw-bold">{{ number_format($income_total+$outstanding_received-($income_received_upi + $income_received_card + $expense + $outstanding), 2) }}</td>
                                    <td></td>
                                    <td class="text-end text-danger"><a class="daybook text-primary" href="javascript:void(0)" data-bs-toggle="modal" data-modal="incomePendingdModal" data-bs-target="#incomePendingdModal" data-title="Pending Payments" data-fdate="{{ ($inputs) ? $inputs[0] : $today }}" data-tdate="{{ ($inputs) ? $inputs[1] : $today }}" data-branch="{{ ($inputs && $inputs[2]) ? $inputs[2] : 0 }}" data-type="incomepending">{{ number_format(($rtot)-($income_received_cash + $income_received_upi + $income_received_card + $income_received_staff + $outstanding_received + $outstanding), 2) }}</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
<div class="modal fade" id="outstandingReceivedCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="consultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="procedureModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="certificateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="medicineModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="incomeCashModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="incomeUpiModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="incomeCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="incomePendingdModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="visionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pharmacyModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive dayBookDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
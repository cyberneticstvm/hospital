<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-blue{
            color: blue;
        }
        .text-medium{
            font-size: 12px;
        }
        .text-small{
            font-size: 10px;
        }
        .text-large{
            font-size: 15px;
        }
        table tr td{
            height: 25px;
            padding-left: 5px;
        }
        .bordered th, .bordered td{
            border: 1px solid #000;
        }
        .text-right{
            text-align: right;
        }
        .fw-bold{
            font-weight: bold;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body>
    <center>
    <img src="./images/assets/Devi-Logo-Transparent.jpg" height='100' width='115'/>
    <p>{{ $ds->branches->branch_name }}, {{ $ds->branches->address }}, {{ $ds->branches->contact_number }}</p>
    <h3>Discharge Summary</h3>
    </center>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large bordered">
        <tr>
            <td>Medical Record No</td><td colspan="3">{{ $ds->medical_record_id }}</td>
            <td>Patient ID</td><td>{{ $ds->patient->patient_id }}</td>            
        </tr>
        <tr>
            <td>Name/Age/Gender</td><td colspan="3">{{ $ds->patient->patient_name }} / {{ $ds->patient->age }} / {{ $ds->patient->gender }}</td>
            <td>Date</td><td>{{ $ds->created_at->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Address</td><td colspan="3">{{ $ds->patient->address }}</td><td>Hospital ID</td><td>HOSP32P155802</td>
        </tr>
        <tr>
            <td>D.O.A</td><td>{{ ($ds->doa) ? $ds->doa->format('d.m.Y') : '' }}</td>
            <td>D.O.S</td><td>{{ ($ds->dos) ? $ds->dos->format('d.m.Y') : '' }}</td>
            <td>D.O.D</td><td>{{ ($ds->dod) ? $ds->dod->format('d.m.Y') : '' }}</td>
        </tr>
        <tr>
            <td>Reason for Admission</td><td colspan="5">{{ $ds->reason_for_admission }}</td>
        </tr>
        <tr>
            <td>Findings</td><td colspan="5">{{ $ds->findings }}</td>
        </tr>
        <tr>
            <td>Investigation Results</td><td colspan="5">{{ $ds->investigation_result }}</td>
        </tr>
        <tr>
            <td>General Examination</td><td colspan="5">{{ $ds->general_examination }}</td>
        </tr>
        <tr>
            <td>Diagnosis</td><td colspan="5">{{ $diagnosis }}</td>
        </tr>
        <tr>
            <td>Procedure</td><td colspan="5">{{ $procedure->whereIn('id', $ds->procedures()->pluck('procedure')->toArray())->pluck('name')->implode(', ') }}</td>
        </tr>
        <tr>
            <td>Condition at Discharge</td><td colspan="5">{{ $ds->discharge_condition }}</td>
        </tr>
        <tr>
            <td>Medication</td><td colspan="5">{{ $ds->medication }}</td>
        </tr>
        <tr>
            <td></td><td colspan="5">
                @forelse($ds->medicines as $key => $value)
                    @php $type = DB::table('medicine_types')->where('id', $value->type)->first(); @endphp
                    {{ $type->name.' - '.$value->product->product_name.' - '.$value->notes.' - '.$value->qty }}
                    <br>
                @empty
                @endforelse
            </td>
        </tr>
        <tr>
            <td>Post-operative Instruction</td><td colspan="5">
                @forelse($ds->instructions as $key => $value)
                    {{ $value->instruction->name }}
                    <br>
                @empty
                @endforelse
            </td>
        </tr>
        <tr>
        <td>Reviews</td><td colspan="5">
                @forelse($ds->reviews as $key => $value)
                    @php
                        $times = 'First';
                        if($key == 1):
                            $times = 'Second';
                        elseif($key == 2):
                            $times = 'Third';
                        endif;
                    @endphp
                    <table width="100%" cellpadding='0' cellspacing='0' class="text-large"><tr><td width="30%">{{ $times.' Review Date' }}</td>
                    <td width="20%">{{ ($value && $value->review_date) ? $value->review_date->format('d.m.Y') : '' }}</td>
                    <td width="30%">{{ $times.' Review time' }}</td>
                    <td width="20%">{{ $value->review_time }}</td></tr></table>
                @empty
                @endforelse
            </td>
        </tr>
        <tr>
            <td>Special Instruction</td><td colspan="5">{{ $ds->special_instruction }}</td>
        </tr>
        <tr>
            <td colspan="6">For emergency please contact: +91 9188836222</td>
        </tr>
    </table>
    <br><br>
    <div class="text-right">
        {{ ($ds->doctors) ? $ds->doctors->doctor_name : '' }}<br>
        {{ ($ds->doctors) ? $ds->doctors->designation : '' }}
    </div>
</body>
</html>
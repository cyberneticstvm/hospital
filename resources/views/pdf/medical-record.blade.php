<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        table{
            border: 1px solid #e6e6e6;
            font-size: 12px;
        }
        thead{
            border-bottom: 1px solid #e6e6e6;
        }
        table thead th, table tbody td{
            padding: 5px;
        }
        tbody td{
            border: 1px solid #e6e6e6;
        }
        .text-right{
            text-align: right;
        }
        .bold{
            font-weight: bold;
            font-size: 14px;
            color: #696969;
        }
        table.no-border{
            border: 0px;
        }
        .text-green, p{
            color: #00bdaa;
        }
    </style>
</head>
<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%"/><br/>
            {{ $branch->address }}, Phone:
            {{ $branch->contact_number }}
    </center>
    <br/>
    <center><h5>PATIENT MEDICAL RECORD (MRN: {{ $record->mrn }})</h5></center>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>Patient Name</td><td>{{ $patient->patient_name }}</td>
                <td>Patient ID</td><td>{{ $patient->patient_id }}</td>
                <td>AGE / SEX</td><td>{{ $patient->age }} / {{ $patient->gender }}</td>
            </tr>
            <tr>
                <td>Doctor Name</td><td>{{ $doctor->doctor_name }}</td>
                <td>Medical Record Number</td><td>{{ $record->id }}</td>
                <td>Date</td><td>{{ ($record->created_at) ? date('d/M/Y', strtotime($record->created_at)) : '' }}</td>
            </tr>
        </tbody>
    </table>
    <p> Symptoms (Consultation)</p>
    @foreach($symptoms as $sympt)
        {{ $sympt->symptom_name }}, 
    @endforeach
    <br />
    <p> Symptoms / Doctor Findings</p>
        {{ $record->symptoms_other }}
    <br />
    <p> Patient History</p>
        {{ $record->history }}
    <br />
    <p>Vision</p>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead class="text-center"><tr><th>&nbsp;</th><th><!--VB--></th><th><!--SPH--></th><th><!--CYL--></th><th><!--AXIS--></th><th><!--ADD--></th><th><!--VA--></th></tr></thead>
        <tbody>
            <tr>
                <td class="text-center fw-bold">RE/OD</td>                                            
                <td>{{ ($spectacle) ? $spectacle->re_dist_sph : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_cyl : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_axis : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_add : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->vbr : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_near_va : '--' }}</td>
            </tr>
            <tr>
                <td class="text-center fw-bold">LE/OS</td>                                            
                <td>{{ ($spectacle) ? $spectacle->le_dist_sph : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_cyl : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_axis : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_add : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->vbl : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_near_va : '--' }}</td>
            </tr>
        </tbody>
    </table>
    <p>Biometry</p>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead class="text-center">
            <tr><th></th><th>K1 (Auto)</th><th>K2 (Auto)</th><th>K1 (Manual)</th><th>K2 (Manual)</th><th>AXL</th></tr>
        </thead>
        <tbody>
        <tr>
                <td class="text-center fw-bold">OD</td>
                <td>{{ ($spectacle) ? $spectacle->bm_k1_od_a : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->bm_k2_od_a : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->bm_k1_od_m : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->bm_k2_od_m : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->bm_od_axl : '--' }}</td>
            </tr>
            <tr>
                <td class="text-center fw-bold">OS</td>
                <td>{{ ($spectacle) ? $spectacle->bm_k1_os_a : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->bm_k2_os_a : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->bm_k1_os_m : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->bm_k2_os_m : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->bm_os_axl : '--' }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table boredr="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_1 != 'Na')
                    <img src="{{ $record->vision_od_img1 }}" width="50%" alt=""/><br/>{{ $v_od_1 }}
                    @endif            
                </td>
                <td>
                    @if($v_os_1 != 'Na')
                    <img src="{{ $record->vision_os_img1 }}" width="50%" alt=""/><br/>{{ $v_os_1 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead class="text-center">
            <tr><th>Vision</th><th>OD</th><th>OS</th></tr>
        </thead>
        <tbody>
            <tr><td>Appearance</td><td>{{ $sel_1_od }}</td><td>{{ $sel_1_os }}</td></tr>
            <tr><td>Extraocular Movements</td><td>{{ $sel_2_od }}</td><td>{{ $sel_2_os }}</td></tr>
            <tr><td>Orbital Margins</td><td>{{ $sel_3_od }}</td><td>{{ $sel_3_os }}</td></tr>
            <tr><td>LID and Adnexa</td><td>{{ $sel_4_od }}</td><td>{{ $sel_4_os }}</td></tr>
            <tr><td>Conjunctiva</td><td>{{ $sel_5_od }}</td><td>{{ $sel_5_os }}</td></tr>
            <tr><td>Sclera</td><td>{{ $sel_6_od }}</td><td>{{ $sel_6_os }}</td></tr>
            <tr><td>Cornea</td><td>{{ $sel_7_od }}</td><td>{{ $sel_7_os }}</td></tr>
            <tr><td>Anterior Chamber</td><td>{{ $sel_8_od }}</td><td>{{ $sel_8_os }}</td></tr>
            <tr><td>Iris</td><td>{{ $sel_9_od }}</td><td>{{ $sel_9_os }}</td></tr>
            <tr><td>Pupil</td><td>{{ $sel_10_od }}</td><td>{{ $sel_10_os }}</td></tr>
            <tr><td>Lens</td><td>{{ $sel_11_od }}</td><td>{{ $sel_11_os }}</td></tr>
            <tr><td>AVR</td><td>{{ $sel_12_od }}</td><td>{{ $sel_12_os }}</td></tr>
            <tr><td>Fundus</td><td>{{ $sel_13_od }}</td><td>{{ $sel_13_os }}</td></tr>
            <tr><td>Media</td><td>{{ $sel_14_od }}</td><td>{{ $sel_14_os }}</td></tr>
            <tr><td>Disc Margins</td><td>{{ $sel_15_od }}</td><td>{{ $sel_15_os }}</td></tr>
            <tr><td>CDR</td><td>{{ $sel_16_od }}</td><td>{{ $sel_16_os }}</td></tr>
            <tr><td>NRR</td><td>{{ $sel_17_od }}</td><td>{{ $sel_17_os }}</td></tr>
            <tr><td>AV Ratio & Bloodvessels</td><td>{{ $sel_18_od }}</td><td>{{ $sel_18_os }}</td></tr>
            <tr><td>FR</td><td>{{ $sel_19_od }}</td><td>{{ $sel_19_os }}</td></tr>
            <tr><td>Background Retina & Periphery</td><td>{{ $sel_20_od }}</td><td>{{ $sel_20_os }}</td></tr>
        </tbody>
    </table>
    <table boredr="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_2 != 'Na')
                    <img src="{{ $record->vision_od_img2 }}" width="50%" alt=""/><br/>{{ $v_od_2 }}
                    @endif            
                </td>
                <td>
                    @if($v_os_2 != 'Na')
                    <img src="{{ $record->vision_os_img2 }}" width="50%" alt=""/><br/>{{ $v_os_2 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <table boredr="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_3 != 'Na')
                    <img src="{{ $record->vision_od_img3 }}" width="50%" alt=""/><br/>{{ $v_od_3 }}
                    @endif            
                </td>
                <td>
                    @if($v_os_3 != 'Na')
                    <img src="{{ $record->vision_os_img3 }}" width="50%" alt=""/><br/>{{ $v_os_3 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <table boredr="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_4 != 'Na')
                    <img src="{{ $record->vision_od_img4 }}" width="50%" alt=""/><br/>{{ $v_od_4 }}
                    @endif            
                </td>
                <td>
                    @if($v_os_4 != 'Na')
                    <img src="{{ $record->vision_os_img4 }}" width="50%" alt=""/><br/>{{ $v_os_4 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <p>Gonioscopy</p>
    <table boredr="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td ><img src="./images/assets/x-png-30.png" alt=""/></td>
                <td><img src="./images/assets/x-png-30.png" alt=""/></td>
            </tr>
        </tbody>
    </table>
    <p> Signs</p>
        {{ $record->signs }}
    <br>
    <p> Retina</p>
    <table boredr="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            @forelse($retinas as $key => $retina)
                <tr>
                    <td width="50%"><img src="{{ public_path().'/storage/'.$retina->retina_img }}" width='50%' /></td>
                    <td width="50%"><img src="{{ '/public/storage/'.$retina->retina_img }}" width='50%' /></td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
    <p> Diagnosis</p>
    @foreach($diagnosis as $diag)
        {{ $diag->diagnosis_name }}, 
    @endforeach
    <br>
    <p>Doctor Recommondations</p>
    {{ $record->doctor_recommondations }}
    <br />
    <p> Medicine / Lab Advised</p>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead><th>SL No.</th><th>Medicine Name</th><th>Dosage</th><th>Qty</th><th>Notes</th></thead>
        <tbody>
        @php $c = 1 @endphp
        @foreach($medicines as $medicine)
        <tr>
            <td>{{ $c++ }}</td>
            <td>{{ $medicine->product_name }}</td>
            <td>{{ $medicine->dosage }}</td>
            <td class="text-right">{{ $medicine->qty }}</td>
            <td>{{ $medicine->notes }}</td>            
        </tr>
        @endforeach
        </tbody>
    </table>
    <br />
    <p>Other Details</p>
    <table width="100%" class="no-border">
        <tr><th>Admission Advised: </th><th>Surgery Advised: </th><th>Review Date: </th></tr>
        <tr><th>{{ ($record->is_patient_admission == 'N') ? 'No' : 'Yes' }}</th><th>{{ ($record->is_patient_surgery == 'N') ? 'No' : 'Yes' }}</th><th>{{ ($record->review_date) ? date('d/M/Y', strtotime($record->review_date)) : '' }}</th></tr>
    </table>
</body>
</html>
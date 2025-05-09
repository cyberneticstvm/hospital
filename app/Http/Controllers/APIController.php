<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\CampMaster;
use App\Models\PatientMedicalRecord;
use App\Models\PatientRegistrations;
use App\Models\Spectacle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class APIController extends Controller
{
    protected $secret;
    public function __construct()
    {
        $this->secret = Helper::apiSecret();
    }
    function getMrecord($id, $secret)
    {
        if ($secret == $this->secret) :
            $mrecord = PatientMedicalRecord::where('id', $id ?? 0)->first();
            $mrns = PatientMedicalRecord::where('patient_id', $mrecord->patient_id ?? 0)->pluck('id');
            $patient = PatientRegistrations::where('id', $mrecord->patient_id ?? 0)->first();
            $prescription = Spectacle::selectRaw("CONCAT_WS(' / ', 'MRN', medical_record_id, DATE_FORMAT(created_at, '%d/%b/%Y')) AS name, id")->whereIn('medical_record_id', $mrns)->pluck('name', 'id');
            return response()->json([
                'status' => true,
                'mrecord' => $mrecord,
                'mrns' => $mrns,
                'patient' => $patient,
                'prescription' => $prescription,
            ], 200);
        else :
            return response()->json([
                'status' => false,
                'data' => "No Record Found!"
            ], 404);
        endif;
    }

    function getPrescription($id, $secret)
    {
        if ($secret == $this->secret) :
            $spectacle = Spectacle::leftJoin('users as u', 'u.id', 'spectacles.created_by')->selectRaw("re_dist_sph as re_sph, re_dist_cyl as re_cyl, re_dist_axis as re_axis, re_dist_add as re_add, re_dist_va as re_va, rpd as re_pd, lpd as le_pd, le_dist_sph as le_sph, le_dist_cyl as le_cyl, le_dist_axis as le_axis, le_dist_add as le_add, le_dist_va as le_va, re_int_add, le_int_add, '' as a_size, '' as b_size, '' as dbl, '' as fh, '' as ed, '' as vd, '' as w_angle, 0 as doctor, u.name as optometrist")->where('spectacles.id', $id)->first();
            return response()->json([
                'status' => true,
                'spectacle' => $spectacle,
            ], 200);
        else :
            return response()->json([
                'status' => false,
                'data' => "No Record Found!"
            ], 404);
        endif;
    }

    function getCustomer($qstring, $secret)
    {
        if ($secret == $this->secret) :
            $mrecord = PatientMedicalRecord::where('id', $qstring ?? 0)->first();
            $patient = PatientRegistrations::where('id', $mrecord->patient_id ?? 0)->first();
            $spectacle = Spectacle::where('medical_record_id', $qstring ?? 0)->first();
            return response()->json([
                'status' => true,
                'mrecord' => $mrecord,
                'patient' => $patient,
                'spectacle' => $spectacle,
            ], 200);
        else :
            return response()->json([
                'status' => false,
                'data' => "No Record Found!"
            ], 404);
        endif;
    }

    function getCamps($secret)
    {
        if ($secret == $this->secret) :
            $camps = CampMaster::whereDate('to', '>=', Carbon::today())->selectRaw("CONCAT_WS('-', camp_id, venue) AS name, CONCAT_WS('-', camp_id, venue) AS id")->pluck('name', 'id');
            return response()->json([
                'status' => true,
                'camps' => collect($camps),
            ], 200);
        else :
            return response()->json([
                'status' => false,
                'data' => "No Record Found!"
            ], 404);
        endif;
    }
}

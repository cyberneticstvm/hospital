<?php

namespace App\Helper;

use App\Models\Branch;
use App\Models\Procedure;
use App\Models\PatientMedicalRecord;
use App\Models\PatientReference;
use App\Models\PatientSurgeryConsumable;
use App\Models\InhouseCamp;
use App\Models\InhouseCampProcedure;
use App\Models\PatientRegistrations;
use App\Models\RoyaltyCardProcedure;
use App\Models\UserBranch;
use App\Models\VehicleAccount;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Helper
{
    protected $url, $secret;

    public function __construct()
    {
        $this->url = "https://store.devihospitals.in";
        $this->secret = 'fdjsvsgdf4dhgf687f4bg54g4hf787';
    }
    public static function api_url()
    {
        return "https://store.devihospitals.in";
    }
    public static function apiSecret()
    {
        return 'fdjsvsgdf4dhgf687f4bg54g4hf787';
    }

    public static function sendRequestedDocviaWa($mobile, $name, $mrid, $doc_type)
    {
        $token = Config::get('myconfig.whatsapp.token');
        if ($doc_type == 'mrecord'):
            $config = [
                "messaging_product" => "whatsapp",
                "to" => "+91" . $mobile,
                "type" => "template",
                "template" => [
                    "name" => "hosp_emr",
                    "language" => ["code" => "en"],
                    "components" => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "image",
                                    "image" =>
                                    [
                                        "link" => "https://store.devihospitals.in/public/backend/assets/images/logo/devi-hospital-logo.png",
                                    ],
                                ],
                            ]
                        ],
                        [
                            "type" => "body",
                            "parameters" => [
                                ["type" => "text", "text" => $name],
                                ["type" => "text", "text" => "+91 9188836222"],
                            ]
                        ],
                        [
                            "type" => "button",
                            "sub_type" => "url",
                            "index" => 0,
                            "parameters" => [
                                ["type" => "text", "text" => encrypt($mrid)],
                            ]
                        ]
                    ]
                ]
            ];
        endif;

        if ($doc_type == 'phistory'):
            $config = [
                "messaging_product" => "whatsapp",
                "to" => "+91" . $mobile,
                "type" => "template",
                "template" => [
                    "name" => "hosp_phistory",
                    "language" => ["code" => "en"],
                    "components" => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "image",
                                    "image" =>
                                    [
                                        "link" => "https://store.devihospitals.in/public/backend/assets/images/logo/devi-hospital-logo.png",
                                    ],
                                ],
                            ]
                        ],
                        [
                            "type" => "body",
                            "parameters" => [
                                ["type" => "text", "text" => $name],
                                ["type" => "text", "text" => "+91 9188836222"],
                            ]
                        ],
                        [
                            "type" => "button",
                            "sub_type" => "url",
                            "index" => 0,
                            "parameters" => [
                                ["type" => "text", "text" => encrypt($mrid)],
                            ]
                        ]
                    ]
                ]
            ];
        endif;

        if ($doc_type == 'spectacle'):
            $config = [
                "messaging_product" => "whatsapp",
                "to" => "+91" . $mobile,
                "type" => "template",
                "template" => [
                    "name" => "hosp_prescription",
                    "language" => ["code" => "en"],
                    "components" => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "image",
                                    "image" =>
                                    [
                                        "link" => "https://store.devihospitals.in/public/backend/assets/images/logo/devi-hospital-logo.png",
                                    ],
                                ],
                            ]
                        ],
                        [
                            "type" => "body",
                            "parameters" => [
                                ["type" => "text", "text" => $name],
                                ["type" => "text", "text" => "+91 9188836222"],
                            ]
                        ],
                        [
                            "type" => "button",
                            "sub_type" => "url",
                            "index" => 0,
                            "parameters" => [
                                ["type" => "text", "text" => encrypt($mrid)],
                            ]
                        ]
                    ]
                ]
            ];
        endif;

        $curl = curl_init();
        $data_string = json_encode($config);
        $ch = curl_init('https://graph.facebook.com/v22.0/543653938835557/messages');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        $res = json_decode($result, true);
        return $res;
    }

    public static function sendWaPromotion($schedule, $name, $mobile)
    {
        $token = Config::get('myconfig.whatsapp.token');
        $config = [
            "messaging_product" => "whatsapp",
            "to" => "+91" . $mobile,
            "type" => "template",
            "template" => [
                "name" => $schedule->template_id,
                "language" => ["code" => $schedule->template_language],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" =>
                                [
                                    "link" => ($schedule->entity == 'store') ? "https://store.devihospitals.in/public/backend/assets/images/logo/devi-hospital-logo.png" : "https://store.devihospitals.in/public/backend/assets/images/logo/devi-hospital-logo.png",
                                ],
                            ],
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => []
                    ],
                ]
            ]
        ];
        $curl = curl_init();
        $data_string = json_encode($config);
        $ch = curl_init('https://graph.facebook.com/v22.0/543653938835557/messages');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        $res = json_decode($result, true);
        return $res;
    }

    public static function sendAppointmentConfirmation($appointment, $action)
    {
        $token = Config::get('myconfig.whatsapp.token');
        $config = [
            "messaging_product" => "whatsapp",
            "to" => "+91" . $appointment->mobile_number,
            "type" => "template",
            "template" => [
                "name" => "appointment_confirmation",
                "language" => ["code" => "en"],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" =>
                                [
                                    "link" => "https://store.devihospitals.in/public/backend/assets/images/logo/devi-hospital-logo.png",
                                ],
                            ],
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => [
                            ["type" => "text", "text" => $appointment->patient_name],
                            ["type" => "text", "text" => ($action == 'save') ? 'Booked' : 'Updated'],
                            ["type" => "text", "text" => $appointment->appointment_date->format('d.M.Y')],
                            ["type" => "text", "text" => Carbon::parse($appointment->appointment_time)->format('g:i A')],
                            ["type" => "text", "text" => $appointment->doctors->doctor_name],
                            ["type" => "text", "text" => $appointment->branches->branch_name],
                            ["type" => "text", "text" => $appointment->branches->address],
                        ]
                    ],
                    [
                        "type" => "button",
                        "sub_type" => "url",
                        "index" => 0,
                        "parameters" => [
                            ["type" => "text", "text" => $appointment->branches->map_id],
                        ]
                    ]
                ]
            ]
        ];
        $curl = curl_init();
        $data_string = json_encode($config);
        $ch = curl_init('https://graph.facebook.com/v22.0/543653938835557/messages');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        $res = json_decode($result, true);
        return $res;
    }


    public static function getAvailableStock($product, $batch, $from_branch)
    {
        if ($from_branch == 0):
            $total_purchase = DB::table('purchases')->where('product', '=', $product)->where('batch_number', '=', $batch)->sum('qty');
            $total_transfer = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->sum('qty');
        else:
            $total_purchase = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->where('to_branch', '=', $from_branch)->sum('qty');
            $total_transfer = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->where('from_branch', '=', $from_branch)->sum('qty');
        endif;
        return $total_purchase - $total_transfer;
    }

    public static function sendSms($sms)
    {
        $curl = curl_init();
        $data_string = json_encode($sms);
        $ch = curl_init('https://www.bulksmsplans.com/api/send_sms');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        $res = json_decode($result, true);
        //return ($res['code'] == 200) ? 200 : $res['code'];
        return $res;
    }

    public function getVehicle($vcode, $rc_type)
    {
        $data = null;
        if ($vcode && $rc_type == 2):
            $url = Helper::api_url() . "/api/vehicle/$vcode/" . $this->secret;
            $json = file_get_contents($url);
            $vehicle = json_decode($json);
            if ($vehicle->status):
                if ($vehicle->vstatus == 'Active'):
                    $data = $vehicle->data;
                endif;
            endif;
        endif;
        return $data;
    }

    public static function getProcedureFee($medical_record_id, $procedure)
    {
        $proc = Procedure::find($procedure);
        $fee = $proc->fee;
        $discount = 0;
        $credit = 0;
        $discount_category = 'Na';
        $discount_category_id = 0;
        $mrecord = PatientMedicalRecord::find($medical_record_id);
        $pref = PatientReference::find($mrecord->mrn);
        $patient = PatientRegistrations::find($mrecord->patient_id);
        if ($pref->camp_id > 0):
            $camp = InhouseCamp::find($pref->camp_id);
            $valid_to = Carbon::parse($pref->created_at)->addDays($camp->validity)->format('Y-m-d');
            $camps = InhouseCampProcedure::where('camp_id', $camp->id)->pluck('procedure')->all();
            $fee = (in_array($procedure, $camps) && $valid_to >= Carbon::today()) ? 0 : $proc->fee;
            $discount = ($fee == 0) ? $proc->fee : $discount;
            $discount_category = ($fee == 0) ? 'camp' : $discount_category;
            $discount_category_id = ($fee == 0) ? $camp->id : $discount_category_id;
        endif;
        if ($pref->rc_type && $pref->rc_number):
            $pro = RoyaltyCardProcedure::where('proc_id', $proc->id)->where('royalty_card_id', $pref->rc_type)->first();
            if ($pro && $pro->discount_percentage > 0 && $pref->rc_type == 3):
                // Gold card - 35% of discount will be credited to vehicle owner and 65% will be go to patient.
                $disctot = ($proc->fee * $pro->discount_percentage) / 100;
                $credit = ($disctot * 35) / 100;
                $discount = $disctot - $credit;
            elseif ($pro && $pro->discount_percentage > 0):
                $discount = ($proc->fee * $pro->discount_percentage) / 100;
            endif;
            $vehicle = (new self)->getVehicle($pref->rc_number, $pref->rc_type);
            if ($vehicle?->contact_number == $patient->mobile_number && strtoupper($vehicle->owner_name) == strtoupper($patient->patient_name)):
                $discount = $proc->fee;
            endif;
            $fee = $proc->fee - $discount;
            $discount_category = 'royalty-card';
            $discount_category_id = $pref->rc_type;
            if ($credit > 0 && $vehicle?->id > 0):
                VehicleAccount::updateOrCreate(
                    [
                        'vehicle_id' => $vehicle?->id,
                        'patient_id' => $patient->id,
                        'medical_record_id' => $medical_record_id,
                        'procedure_id' => $procedure,
                        'type' => 'cr',
                    ],
                    [
                        'vehicle_id' => $vehicle?->id,
                        'patient_id' => $patient->id,
                        'medical_record_id' => $medical_record_id,
                        'procedure_id' => $procedure,
                        'type' => 'cr',
                        'amount' => $credit,
                        'notes' => 'Royalty Card',
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]
                );
            endif;
        endif;
        return array($fee, $discount, $discount_category, $discount_category_id);
    }

    public static function getOwedTotal($mrid)
    {
        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pref.id', 'pmr.mrn')->where('pref.review', 'no')->where('pmr.id', $mrid)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->where('pmr.id', $mrid)->where('pr.status', 1)->where('pr.discount', 0)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pp.medical_record_id', $mrid)->whereNull('pp.deleted_at')->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->where('pc.medical_record_id', $mrid)->where('pcd.status', 'I')->sum('pcd.fee');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('p.id', $mrid)->where('m.status', 1)->sum('m.total');

        $pharmacy = DB::table('pharmacy_records as pr')->leftJoin('pharmacies as p', 'p.id', 'pr.pharmacy_id')->where('p.medical_record_id', $mrid)->sum('total');

        $vision = DB::table('spectacles')->where('medical_record_id', $mrid)->sum('fee');

        $clinical_lab = DB::table('lab_clinics')->where('medical_record_id', $mrid)->where('tested_from', 1)->sum('fee');

        $radiology_lab = DB::table('lab_radiologies')->where('medical_record_id', $mrid)->where('tested_from', 1)->sum('fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->where('m.medical_record_id', $mrid)->sum('d.total');

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->where('m.medical_record_id', $mrid)->sum('d.total');

        $surgery_consumables = PatientSurgeryConsumable::where('medical_record_id', $mrid)->sum('total_after_discount');

        return $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $medicine + $pharmacy + $vision + $clinical_lab + $radiology_lab + $surgery_medicine + $postop_medicine + $surgery_consumables;
    }

    public static function getPaidTotal($mrid)
    {
        $paid = DB::table('patient_payments as p')->where('p.medical_record_id', $mrid)->where('type', '!=', 8)->sum('amount');
        return $paid;
    }

    public static function getOwedTotalForStatement($mrid)
    {
        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pref.id', 'pmr.mrn')->where('pref.review', 'no')->where('pmr.id', $mrid)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->where('pmr.id', $mrid)->where('pr.status', 1)->where('pr.discount', 0)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pp.medical_record_id', $mrid)->whereNull('pp.deleted_at')->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->where('pc.medical_record_id', $mrid)->where('pcd.status', 'I')->sum('pcd.fee');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('p.id', $mrid)->where('m.status', 1)->sum('m.total');

        $pharmacy = DB::table('pharmacy_records as pr')->leftJoin('pharmacies as p', 'p.id', 'pr.pharmacy_id')->where('p.medical_record_id', $mrid)->sum('total');

        $vision = DB::table('spectacles')->where('medical_record_id', $mrid)->sum('fee');

        $clinical_lab = DB::table('lab_clinics')->where('medical_record_id', $mrid)->where('tested_from', 1)->sum('fee');

        $radiology_lab = DB::table('lab_radiologies')->where('medical_record_id', $mrid)->where('tested_from', 1)->sum('fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->where('m.medical_record_id', $mrid)->sum('d.total');

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->where('m.medical_record_id', $mrid)->sum('d.total');

        $surgery_consumables = PatientSurgeryConsumable::where('medical_record_id', $mrid)->sum('total_after_discount');

        return array('registration' => $reg_fee_total, 'consultation' => $consultation_fee_total, 'procedure' => $procedure_fee_total, 'certificate' => $certificate_fee_total, 'pharmacy' => $medicine, 'medicine' => $pharmacy, 'vision' => $vision, 'clinic' => $clinical_lab, 'radiology' => $radiology_lab, 'surgerymed' => $surgery_medicine, 'postop' => $postop_medicine, 'surgeryconsumable' => $surgery_consumables);
    }

    public static function getPatientOutstanding($startDate, $endDate, $brn)
    {
        $outstandings = [];
        if ($startDate && $endDate):
            $refs = PatientReference::where('branch', $brn)->whereBetween('created_at', [$startDate, $endDate])->get();
        else:
            $refs = PatientReference::where('branch', $brn)->get();
        endif;
        foreach ($refs as $key => $val):
            $owed = Helper::getOwedTotal($val->id);
            $paid = Helper::getPaidTotal($val->id);
            if ($owed - $paid != 0):
                $outstandings[] = [
                    'due' => $owed,
                    'received' => $paid,
                    'balance' => $owed - $paid,
                    'patient_name' => $val->patient->patient_name,
                    'patient_id' => $val->patient_id,
                ];
            endif;
        endforeach;
        return $outstandings;
    }
}

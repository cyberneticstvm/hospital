<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER add_patient_admission AFTER INSERT ON patient_medical_records FOR EACH ROW
        BEGIN
            IF (NEW.is_patient_admission = "Y") THEN
                INSERT INTO admissions (medical_record_id, patient_id, doctor_id, is_patient_surgery, updated_by) VALUES (NEW.id, NEW.patient_id, NEW.doctor_id, NEW.is_patient_surgery, NEW.created_by);
            END IF;
            IF (NEW.is_patient_surgery = "Y") THEN
                INSERT INTO surgeries (medical_record_id, patient_id, doctor_id, updated_by) VALUES (NEW.id, NEW.patient_id, NEW.doctor_id, NEW.created_by);
            END IF;
        END');

        DB::unprepared('CREATE TRIGGER update_patient_admission AFTER UPDATE ON patient_medical_records FOR EACH ROW
        BEGIN
            IF (NEW.is_patient_admission = "N") THEN
                DELETE FROM admissions WHERE medical_record_id = NEW.id;
            END IF;
            IF (NEW.is_patient_admission = "Y" AND OLD.is_patient_admission = "N") THEN
                INSERT INTO admissions (medical_record_id, patient_id, doctor_id, is_patient_surgery, updated_by) VALUES (NEW.id, NEW.patient_id, NEW.doctor_id, NEW.is_patient_surgery, NEW.created_by);
            END IF;
            IF (NEW.is_patient_surgery = "N") THEN
                DELETE FROM surgeries WHERE medical_record_id = NEW.id;
            END IF;
            IF (NEW.is_patient_surgery = "Y" AND OLD.is_patient_surgery = "N") THEN
                INSERT INTO surgeries (medical_record_id, patient_id, doctor_id, updated_by) VALUES (NEW.id, NEW.patient_id, NEW.doctor_id, NEW.created_by);
            END IF;
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER add_patient_admission');
        DB::unprepared('DROP TRIGGER update_patient_admission');
    }
};

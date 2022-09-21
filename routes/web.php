<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('/login');
})->name('login');
Route::post('/', 'App\Http\Controllers\AuthController@userlogin')->name('login');

Route::group(['middleware' => ['auth']], function(){

    Route::get('/dash/', function () {
        return view('dash');
    })->name('dash');

    Route::post('store_branch_session', 'App\Http\Controllers\AuthController@store_branch_session')->name('store_branch_session');

    Route::get('/permission/not-authorized/', function () {
        return view('permission');
    })->name('notauth');

    Route::get('/logout/', 'App\Http\Controllers\AuthController@userlogout');


    // User Route //
    Route::get('/user/', 'App\Http\Controllers\AuthController@index')->name('user.index');
    Route::post('/user/create/', 'App\Http\Controllers\AuthController@store')->name('user.create');
    Route::get('/user/create/', 'App\Http\Controllers\AuthController@show');
    Route::get('/user/{id}/edit/', 'App\Http\Controllers\AuthController@edit')->name('user.edit');
    Route::put('/user/{id}/edit/', 'App\Http\Controllers\AuthController@update')->name('user.update');
    Route::delete('/user/{id}/delete/', 'App\Http\Controllers\AuthController@destroy')->name('user.delete');
    // End User Route //

    // Role Route //
    Route::get('/roles/', 'App\Http\Controllers\RoleController@index')->name('roles.index');
    Route::get('/roles/create/', 'App\Http\Controllers\RoleController@show');
    Route::post('/roles/create/', 'App\Http\Controllers\RoleController@store')->name('roles.create');
    Route::get('/roles/{id}/edit/', 'App\Http\Controllers\RoleController@edit')->name('roles.edit');
    Route::put('/roles/{id}/edit/', 'App\Http\Controllers\RoleController@update')->name('roles.update');
    Route::delete('/roles/{id}/delete/', 'App\Http\Controllers\RoleController@destroy')->name('roles.delete');
    // End Role Route //

    // Patient Registration //
    Route::get('/patient/', 'App\Http\Controllers\PatientRegistrationController@index')->name('patient.index');
    Route::get('/patient/create/', 'App\Http\Controllers\PatientRegistrationController@create');
    Route::post('/patient/create/', 'App\Http\Controllers\PatientRegistrationController@store')->name('patient.create');
    Route::get('/patient/{id}/edit/', 'App\Http\Controllers\PatientRegistrationController@edit')->name('patient.edit');
    Route::put('/patient/{id}/edit/', 'App\Http\Controllers\PatientRegistrationController@update')->name('patient.update');
    Route::delete('/patient/{id}/delete/', 'App\Http\Controllers\PatientRegistrationController@destroy')->name('patient.delete');

    Route::get('/patient/history/{id}/', 'App\Http\Controllers\PatientRegistrationController@show')->name('patient.history');
    // End Patient Registration //

    // Doctor Registration//
    Route::get('/doctor/', 'App\Http\Controllers\DoctorRegistrationController@index')->name('doctor.index');
    Route::get('/doctor/create/', 'App\Http\Controllers\DoctorRegistrationController@create');
    Route::post('/doctor/create/', 'App\Http\Controllers\DoctorRegistrationController@store')->name('doctor.create');
    Route::get('/doctor/{id}/edit/', 'App\Http\Controllers\DoctorRegistrationController@edit')->name('doctor.edit');
    Route::put('/doctor/{id}/edit/', 'App\Http\Controllers\DoctorRegistrationController@update')->name('doctor.update');
    Route::delete('/doctor/{id}/delete/', 'App\Http\Controllers\DoctorRegistrationController@destroy')->name('doctor.delete');
    // End Doctor Registration//

    // Patient Reference //
    Route::get('/consultation/refer_to_department/', 'App\Http\Controllers\PatientReferenceController@index')->name('consultation.patient-reference');
    Route::get('/consultation/create-patient-reference/{id}/', 'App\Http\Controllers\PatientReferenceController@create');
    Route::post('/consultation/create-patient-reference/', 'App\Http\Controllers\PatientReferenceController@store')->name('patient_reference.create');
    Route::get('/consultation/edit-patient-reference/{id}/', 'App\Http\Controllers\PatientReferenceController@edit')->name('patient_reference.edit');
    Route::put('/consultation/edit-patient-reference/{id}/', 'App\Http\Controllers\PatientReferenceController@update')->name('patient_reference.update');
    Route::delete('/consultation/edit-patient-reference/{id}/', 'App\Http\Controllers\PatientReferenceController@destroy')->name('patient_reference.delete');
    // End Patient Reference //

    // Branch Registration //
    Route::get('/branch/', 'App\Http\Controllers\BranchController@index')->name('branch.index');
    Route::get('/branch/create/', function () {
        return view('branch.create');
    })->name('branch.create');
    Route::post('/branch/create/', 'App\Http\Controllers\BranchController@store')->name('branch.create');
    Route::get('/branch/{id}/edit/', 'App\Http\Controllers\BranchController@edit')->name('branch.edit');
    Route::put('/branch/{id}/edit/', 'App\Http\Controllers\BranchController@update')->name('branch.update');
    Route::delete('/branch/{id}/delete/', 'App\Http\Controllers\BranchController@destroy')->name('branch.delete');
    // End Branch Registration //

    // Department Registration //
    Route::get('/department/', 'App\Http\Controllers\DepartmentController@index')->name('department.index');
    Route::get('/department/create/', function () {
        return view('department.create');
    })->name('department.create');
    Route::post('/department/create/', 'App\Http\Controllers\DepartmentController@store')->name('department.create');
    Route::get('/department/{id}/edit/', 'App\Http\Controllers\DepartmentController@edit')->name('department.edit');
    Route::put('/department/{id}/edit/', 'App\Http\Controllers\DepartmentController@update')->name('department.update');
    Route::delete('/department/{id}/delete/', 'App\Http\Controllers\DepartmentController@destroy')->name('department.delete');
    // End Department Registration //

    // Consultation & Medical Records //
    Route::get('/consultation/medical/{id}', 'App\Http\Controllers\PatientReferenceController@show')->name('consultation.medical-records');
    Route::get('/consultation/medical-records/', 'App\Http\Controllers\PatientMedicalRecordController@index')->name('consultation.index');
    Route::post('/consultation/medical-records/create/', 'App\Http\Controllers\PatientMedicalRecordController@store')->name('medical-records.create');
    Route::get('/consultation/medical-records/edit/{id}/', 'App\Http\Controllers\PatientMedicalRecordController@edit')->name('medical-records.edit');
    Route::put('/consultation/medical-records/edit/{id}/', 'App\Http\Controllers\PatientMedicalRecordController@update')->name('medical-records.update');
    Route::delete('/consultation/medical-records/delete/{id}', 'App\Http\Controllers\PatientMedicalRecordController@destroy')->name('medical-records.delete');
    // End Consultation & Medical Records //

    // Symptoms //
    Route::get('/symptom/index/{type}', 'App\Http\Controllers\SymptomController@index');
    Route::post('/symptom/create/{type}', 'App\Http\Controllers\SymptomController@store');
    // End Symptoms //

    // Product //
    Route::get('/product/', 'App\Http\Controllers\ProductController@index')->name('product.index');
    Route::get('/product/create/', 'App\Http\Controllers\ProductController@create')->name('product.create');
    Route::post('/product/create/', 'App\Http\Controllers\ProductController@store')->name('product.create');
    Route::get('/product/edit/{id}/', 'App\Http\Controllers\ProductController@edit')->name('product.edit');
    Route::put('/product/edit/{id}/', 'App\Http\Controllers\ProductController@update')->name('product.update');
    Route::delete('/product/delete/{id}/', 'App\Http\Controllers\ProductController@destroy')->name('product.delete');
    // End Product //

    // Patient Medicine Records //
    Route::get('/consultation/medicine-records/', 'App\Http\Controllers\MedicineController@index')->name('medicine.index');
    Route::get('/consultation/medicine/create/{id}/', 'App\Http\Controllers\MedicineController@create')->name('medicine.create');
    Route::post('/consultation/medicine/create/{id}/', 'App\Http\Controllers\MedicineController@store')->name('medicine.create');
    Route::get('/consultation/medicine/edit/{id}/', 'App\Http\Controllers\MedicineController@edit')->name('medicine.edit');
    Route::put('/consultation/medicine/edit/{id}/', 'App\Http\Controllers\MedicineController@update')->name('medicine.update');
    Route::delete('/consultation/medicine/delete/{id}/', 'App\Http\Controllers\MedicineController@destroy')->name('medicine.delete');
    // End Patient Medicine Records //

    // supplier //
    Route::get('/supplier/', 'App\Http\Controllers\SupplierController@index')->name('supplier.index');
    Route::get('/supplier/create/', 'App\Http\Controllers\SupplierController@create')->name('supplier.create');
    Route::post('/supplier/create/', 'App\Http\Controllers\SupplierController@store')->name('supplier.save');
    Route::get('/supplier/edit/{id}/', 'App\Http\Controllers\SupplierController@edit')->name('supplier.edit');
    Route::put('/supplier/edit/{id}/', 'App\Http\Controllers\SupplierController@update')->name('supplier.update');
    Route::delete('/supplier/delete/{id}/', 'App\Http\Controllers\SupplierController@destroy')->name('supplier.delete');
    // End supplier //

    // purchase //
    Route::get('/purchase/', 'App\Http\Controllers\PurchaseController@index')->name('purchase.index');
    Route::get('/purchase/create/', 'App\Http\Controllers\PurchaseController@create')->name('purchase.create');
    Route::post('/purchase/create/', 'App\Http\Controllers\PurchaseController@store')->name('purchase.save');
    Route::get('/purchase/edit/{id}/', 'App\Http\Controllers\PurchaseController@edit')->name('purchase.edit');
    Route::put('/purchase/edit/{id}/', 'App\Http\Controllers\PurchaseController@update')->name('purchase.update');
    Route::delete('/purchase/delete/{id}/', 'App\Http\Controllers\PurchaseController@destroy')->name('purchase.delete');
    // End purchase //

    // purchase //
    Route::get('/product-transfer/', 'App\Http\Controllers\ProductTransferController@index')->name('product-transfer.index');
    Route::get('/product-transfer/create/', 'App\Http\Controllers\ProductTransferController@create')->name('product-transfer.create');
    Route::post('/product-transfer/create/', 'App\Http\Controllers\ProductTransferController@store')->name('product-transfer.save');
    Route::get('/product-transfer/edit/{id}/', 'App\Http\Controllers\ProductTransferController@edit')->name('product-transfer.edit');
    Route::put('/product-transfer/edit/{id}/', 'App\Http\Controllers\ProductTransferController@update')->name('product-transfer.update');
    Route::delete('/product-transfer/delete/{id}/', 'App\Http\Controllers\ProductTransferController@destroy')->name('product-transfer.delete');
    // End purchase //

    // spectacles //
    Route::get('/spectacle/', 'App\Http\Controllers\SpectacleController@index')->name('spectacle.index');
    Route::get('/spectacle/fetch/', 'App\Http\Controllers\SpectacleController@fetch');
    Route::post('/spectacle/show/', 'App\Http\Controllers\SpectacleController@show')->name('spectacle.show');
    Route::get('/spectacle/create/', 'App\Http\Controllers\SpectacleController@create')->name('spectacle.create');
    Route::post('/spectacle/create/', 'App\Http\Controllers\SpectacleController@store')->name('spectacle.save');
    Route::get('/spectacle/edit/{id}/', 'App\Http\Controllers\SpectacleController@edit')->name('spectacle.edit');
    Route::put('/spectacle/edit/{id}/', 'App\Http\Controllers\SpectacleController@update')->name('spectacle.update');
    Route::delete('/spectacle/delete/{id}/', 'App\Http\Controllers\SpectacleController@destroy')->name('spectacle.delete');
    // end spectacles //

    // surgery register //
    Route::get('/surgery/', 'App\Http\Controllers\SurgeryController@index')->name('surgery.index');
    Route::get('/surgery/edit/{id}', 'App\Http\Controllers\SurgeryController@edit')->name('surgery.edit');
    Route::put('/surgery/edit/{id}', 'App\Http\Controllers\SurgeryController@update')->name('surgery.update');
    Route::delete('/surgery/delete/{id}/', 'App\Http\Controllers\SurgeryController@destroy')->name('surgery.delete');
    // end surgery register//

    // surgery types management //
    Route::get('/surgery-type/', 'App\Http\Controllers\SurgeryTypeController@index')->name('stype.index');
    Route::get('/surgery-type/create/', 'App\Http\Controllers\SurgeryTypeController@create')->name('stype.create');
    Route::post('/surgery-type/create/', 'App\Http\Controllers\SurgeryTypeController@store')->name('stype.save');
    Route::get('/surgery-type/edit/{id}/', 'App\Http\Controllers\SurgeryTypeController@edit')->name('stype.edit');
    Route::put('/surgery-type/edit/{id}/', 'App\Http\Controllers\SurgeryTypeController@update')->name('stype.update');
    Route::delete('/surgery-type/delete/{id}/', 'App\Http\Controllers\SurgeryTypeController@destroy')->name('stype.delete');
    // end surgery types management //

    // lab test types management //
    Route::get('/lab-test-type/', 'App\Http\Controllers\LabTypeController@index')->name('ltype.index');
    Route::get('/lab-test-type/create/', 'App\Http\Controllers\LabTypeController@create')->name('ltype.create');
    Route::post('/lab-test-type/create/', 'App\Http\Controllers\LabTypeController@store')->name('ltype.save');
    Route::get('/lab-test-type/edit/{id}/', 'App\Http\Controllers\LabTypeController@edit')->name('ltype.edit');
    Route::put('/lab-test-type/edit/{id}/', 'App\Http\Controllers\LabTypeController@update')->name('ltype.update');
    Route::delete('/lab-test-type/delete/{id}/', 'App\Http\Controllers\LabTypeController@destroy')->name('ltype.delete');
    // end lab test types management //

    // lab-clinical //
    Route::get('/lab/clinic/', 'App\Http\Controllers\LabClinicController@index')->name('lab.clinic.index');
    Route::get('/lab/clinic/fetch/', 'App\Http\Controllers\LabClinicController@fetch');
    Route::post('/lab/clinic/show/', 'App\Http\Controllers\LabClinicController@show')->name('lab.clinic.show');
    Route::get('/lab/clinic/create/', 'App\Http\Controllers\LabClinicController@create')->name('lab.clinic.create');
    Route::post('/lab/clinic/create/', 'App\Http\Controllers\LabClinicController@store')->name('lab.clinic.save');
    Route::get('/lab/clinic/edit/{id}/', 'App\Http\Controllers\LabClinicController@edit')->name('lab.clinic.edit');
    Route::put('/lab/clinic/edit/{id}/', 'App\Http\Controllers\LabClinicController@update')->name('lab.clinic.update');
    Route::delete('/lab/clinic/delete/{id}/', 'App\Http\Controllers\LabClinicController@destroy')->name('lab.clinic.delete');
    Route::get('/lab/clinic/result/{id}', 'App\Http\Controllers\LabClinicController@editresult')->name('lab.clinic.result');
    Route::put('/lab/clinic/result/{id}/', 'App\Http\Controllers\LabClinicController@updateresult')->name('lab.clinic.result.update');
    // end lab-clinical //

    // lab-radiology //
    Route::get('/lab/radiology/', 'App\Http\Controllers\LabRadiologyController@index')->name('lab.radiology.index');
    Route::get('/lab/radiology/fetch/', 'App\Http\Controllers\LabRadiologyController@fetch');
    Route::post('/lab/radiology/show/', 'App\Http\Controllers\LabRadiologyController@show')->name('lab.radiology.show');
    Route::get('/lab/radiology/create/', 'App\Http\Controllers\LabRadiologyController@create')->name('lab.radiology.create');
    Route::post('/lab/radiology/create/', 'App\Http\Controllers\LabRadiologyController@store')->name('lab.radiology.save');
    Route::get('/lab/radiology/edit/{id}/', 'App\Http\Controllers\LabRadiologyController@edit')->name('lab.radiology.edit');
    Route::put('/lab/radiology/edit/{id}/', 'App\Http\Controllers\LabRadiologyController@update')->name('lab.radiology.update');
    Route::delete('/lab/radiology/delete/{id}/', 'App\Http\Controllers\LabRadiologyController@destroy')->name('lab.radiology.delete');
    Route::get('/lab/radiology/result/{id}', 'App\Http\Controllers\LabRadiologyController@editresult')->name('lab.radiology.result');
    Route::put('/lab/radiology/result/{id}/', 'App\Http\Controllers\LabRadiologyController@updateresult')->name('lab.radiology.result.update');
    // end lab-radiology //

    // admission //
    Route::get('/admission/', 'App\Http\Controllers\AdmissionController@index')->name('admission.index');
    Route::get('/admission/edit/{id}', 'App\Http\Controllers\AdmissionController@edit')->name('admission.edit');
    Route::put('/admission/edit/{id}', 'App\Http\Controllers\AdmissionController@update')->name('admission.update');
    Route::delete('/admission/delete/{id}/', 'App\Http\Controllers\AdmissionController@destroy')->name('admission.delete');
    // end admission //

    // Procedures //
    Route::get('/procedure/', 'App\Http\Controllers\ProcedureController@index')->name('procedure.index');
    Route::post('/procedure/', 'App\Http\Controllers\ProcedureController@store')->name('procedure.create');
    Route::get('/procedure/edit/{id}/', 'App\Http\Controllers\ProcedureController@edit')->name('procedure.edit');
    Route::put('/procedure/edit/{id}/', 'App\Http\Controllers\ProcedureController@update')->name('procedure.update');
    Route::delete('/procedure/delete/{id}/', 'App\Http\Controllers\ProcedureController@destroy')->name('procedure.delete');

    Route::get('/consultation/procedure/', 'App\Http\Controllers\ProcedureController@fetch')->name('procedure.fetch');
    Route::post('/consultation/procedure/', 'App\Http\Controllers\ProcedureController@show')->name('procedure.show');
    Route::post('/consultation/procedure/save/', 'App\Http\Controllers\ProcedureController@saveadvise')->name('procedure.saveadvise');
    Route::get('/consultation/procedure/edit/{id}/', 'App\Http\Controllers\ProcedureController@editadvise')->name('procedure.editadvise');
    Route::put('/consultation/procedure/edit/{id}/', 'App\Http\Controllers\ProcedureController@updateadvise')->name('procedure.updateadvise');
    Route::delete('/consultation/procedure/delete/{id}/', 'App\Http\Controllers\ProcedureController@destroyadvise')->name('procedure.destroyadvise');
    // End Procedures //

    // Expenses //
    Route::get('/expense/', 'App\Http\Controllers\ExpenseController@index')->name('expense.index');
    Route::get('/expense/create/', 'App\Http\Controllers\ExpenseController@create')->name('expense.create');
    Route::post('/expense/create/', 'App\Http\Controllers\ExpenseController@store')->name('expense.save');
    Route::get('/expense/edit/{id}/', 'App\Http\Controllers\ExpenseController@edit')->name('expense.edit');
    Route::put('/expense/edit/{id}/', 'App\Http\Controllers\ExpenseController@update')->name('expense.update');
    Route::delete('/expense/delete/{id}/', 'App\Http\Controllers\ExpenseController@destroy')->name('expense.delete');
    // End Expenses //

    // OCT/HFA/FFA //
    Route::get('/ohf/', 'App\Http\Controllers\OHFController@index')->name('ohf.index');
    // End OCT/HFA/FFA //

    // Reports //
    Route::get('/reports/daybook/', 'App\Http\Controllers\ReportController@showdaybook')->name('reports.showdaybook');
    Route::post('/reports/daybook/', 'App\Http\Controllers\ReportController@fetchdaybook')->name('reports.fetchdaybook');
    // End Reports //

    // PDFs //
    Route::get('/generate-token/{id}/', [PDFController::class, 'token']);
    Route::get('/generate-prescription/{id}/', [PDFController::class, 'prescription']);
    Route::get('/generate-receipt/{id}/', [PDFController::class, 'receipt']);
    Route::get('/generate-pharmacy-bill/{id}/', [PDFController::class, 'pharmacybill']);
    Route::get('/generate-pharmacy-out/{id}/', [PDFController::class, 'pharmacyout']);
    Route::get('/generate-medical-record/{id}/', [PDFController::class, 'medicalrecord']);
    Route::get('/generate-medical-record-history/{id}/', [PDFController::class, 'medicalrecordhistory']);
    Route::get('/generate-spectacle-prescription/{id}/', [PDFController::class, 'spectacleprescription']);
    Route::get('/lab/radiology/prescription/{id}/', [PDFController::class, 'radiologyprescription']);
    Route::get('/lab/radiology/bill/{id}/', [PDFController::class, 'radiologybill']);
    Route::get('/lab/radiology/report/{id}/', [PDFController::class, 'radiologyreport']);
    Route::get('/lab/clinic/prescription/{id}/', [PDFController::class, 'clinicprescription']);
    Route::get('/lab/clinic/bill/{id}/', [PDFController::class, 'clinicbill']);
    Route::get('/lab/clinic/report/{id}/', [PDFController::class, 'clinicreport']);
    // End PDFs //

    // Settings //
    Route::get('/settings/consultation/', [SettingsController::class, 'showConsultation'])->name('settings.showconsultation');
    Route::put('/settings/consultation/', [SettingsController::class, 'updateConsultation'])->name('settings.consultation.update');
    // End Settings //
});


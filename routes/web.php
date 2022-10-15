<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HelperController;

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

// Authentication //
Route::get('/auth/certificate/{id}/', [HelperController::class, 'certificateAuthentication'])->name('auth.certificateAuth');
// End Authentication //

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

    Route::get('/patient/search/', 'App\Http\Controllers\PatientRegistrationController@search')->name('patient.search');
    Route::post('/patient/search/', 'App\Http\Controllers\PatientRegistrationController@fetch')->name('patient.fetch');

    Route::get('/patient/consultation/search/', 'App\Http\Controllers\PatientRegistrationController@searchc')->name('patientc.search');
    Route::post('/patient/consultation/search/', 'App\Http\Controllers\PatientRegistrationController@fetchconsultation')->name('patient-consultation.fetch');

    Route::get('/patient/medical-record/search/', 'App\Http\Controllers\PatientRegistrationController@searchm')->name('patientm.search');
    Route::post('/patient/medical-record/search/', 'App\Http\Controllers\PatientRegistrationController@fetchmedicalrecord')->name('patient-medical-record.fetch');
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
    Route::delete('/consultation/medicinesingle/delete/{id}/', 'App\Http\Controllers\MedicineController@remove')->name('medicinesingle.delete');
    // End Patient Medicine Records //

    // Pharmacy -> for both inside and outside customers //
    Route::get('/extras/pharmacy/', 'App\Http\Controllers\PharmacyController@index')->name('pharmacy.index');
    Route::get('/extras/pharmacy/create/', 'App\Http\Controllers\PharmacyController@create')->name('pharmacy.create');
    Route::post('/extras/pharmacy/create/', 'App\Http\Controllers\PharmacyController@store')->name('pharmacy.create');
    Route::get('/extras/pharmacy/edit/{id}/', 'App\Http\Controllers\PharmacyController@edit')->name('pharmacy.edit');
    Route::put('/extras/pharmacy/edit/{id}/', 'App\Http\Controllers\PharmacyController@update')->name('pharmacy.update');
    Route::delete('/extras/pharmacy/delete/{id}/', 'App\Http\Controllers\PharmacyController@destroy')->name('pharmacy.delete');
    Route::delete('/extras/pharmacy/medicinesingle/delete/{id}/', 'App\Http\Controllers\PharmacyController@remove')->name('pharmacysingle.delete');    
    // End Pharmacy //

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

    // keratometry //
    Route::get('/keratometry/', 'App\Http\Controllers\KeratometryController@index')->name('keratometry.index');
    Route::post('/keratometry/show/', 'App\Http\Controllers\KeratometryController@show')->name('keratometry.show');
    Route::post('/keratometry/create/', 'App\Http\Controllers\KeratometryController@store')->name('keratometry.save');
    Route::get('/keratometry/edit/{id}/', 'App\Http\Controllers\KeratometryController@edit')->name('keratometry.edit');
    Route::put('/keratometry/edit/{id}/', 'App\Http\Controllers\KeratometryController@update')->name('keratometry.update');
    Route::delete('/keratometry/delete/{id}/', 'App\Http\Controllers\KeratometryController@destroy')->name('keratometry.delete');
    // end keratometry //

    // tonometry //
    Route::get('/tonometry/', 'App\Http\Controllers\TonometryController@index')->name('tonometry.index');
    Route::post('/tonometry/show/', 'App\Http\Controllers\TonometryController@show')->name('tonometry.show');
    Route::post('/tonometry/create/', 'App\Http\Controllers\TonometryController@store')->name('tonometry.save');
    Route::get('/tonometry/edit/{id}/', 'App\Http\Controllers\TonometryController@edit')->name('tonometry.edit');
    Route::put('/tonometry/edit/{id}/', 'App\Http\Controllers\TonometryController@update')->name('tonometry.update');
    Route::delete('/tonometry/delete/{id}/', 'App\Http\Controllers\TonometryController@destroy')->name('tonometry.delete');
    // end tonometry //

    // ascan //
    Route::get('/ascan/', 'App\Http\Controllers\AscanController@index')->name('ascan.index');
    Route::post('/ascan/show/', 'App\Http\Controllers\AscanController@show')->name('ascan.show');
    Route::post('/ascan/create/', 'App\Http\Controllers\AscanController@store')->name('ascan.save');
    Route::get('/ascan/edit/{id}/', 'App\Http\Controllers\AscanController@edit')->name('ascan.edit');
    Route::put('/ascan/edit/{id}/', 'App\Http\Controllers\AscanController@update')->name('ascan.update');
    Route::delete('/ascan/delete/{id}/', 'App\Http\Controllers\AscanController@destroy')->name('ascan.delete');
    // end ascan //

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

    // Income / Expense Heads //
    Route::get('/income-expense-heads/', 'App\Http\Controllers\IncomeExpenseHeadController@index')->name('income-expense-heads.index');
    Route::get('/income-expense-heads/create/', 'App\Http\Controllers\IncomeExpenseHeadController@create')->name('income-expense-heads.create');
    Route::post('/income-expense-heads/create/', 'App\Http\Controllers\IncomeExpenseHeadController@store')->name('income-expense-heads.save');
    Route::get('/income-expense-heads/edit/{id}/', 'App\Http\Controllers\IncomeExpenseHeadController@edit')->name('income-expense-heads.edit');
    Route::put('/income-expense-heads/edit/{id}/', 'App\Http\Controllers\IncomeExpenseHeadController@update')->name('income-expense-heads.update');
    Route::delete('/income-expense-heads/delete/{id}/', 'App\Http\Controllers\IncomeExpenseHeadController@destroy')->name('income-expense-heads.delete');
    // End Income / Expense Heads //

    // Expense //
    Route::get('/expense/', 'App\Http\Controllers\ExpenseController@index')->name('expense.index');
    Route::get('/expense/create/', 'App\Http\Controllers\ExpenseController@create')->name('expense.create');
    Route::post('/expense/create/', 'App\Http\Controllers\ExpenseController@store')->name('expense.save');
    Route::get('/expense/edit/{id}/', 'App\Http\Controllers\ExpenseController@edit')->name('expense.edit');
    Route::put('/expense/edit/{id}/', 'App\Http\Controllers\ExpenseController@update')->name('expense.update');
    Route::delete('/expense/delete/{id}/', 'App\Http\Controllers\ExpenseController@destroy')->name('expense.delete');
    // End Expenses //

    // Income //
    Route::get('/income/', 'App\Http\Controllers\IncomeController@index')->name('income.index');
    Route::get('/income/create/', 'App\Http\Controllers\IncomeController@create')->name('income.create');
    Route::post('/income/create/', 'App\Http\Controllers\IncomeController@store')->name('income.save');
    Route::get('/income/edit/{id}/', 'App\Http\Controllers\IncomeController@edit')->name('income.edit');
    Route::put('/income/edit/{id}/', 'App\Http\Controllers\IncomeController@update')->name('income.update');
    Route::delete('/income/delete/{id}/', 'App\Http\Controllers\IncomeController@destroy')->name('income.delete');
    // End Income //

    // Patient Payments //
    Route::get('/patient-payment/', 'App\Http\Controllers\PatientPaymentController@index')->name('patient-payment.index');
    Route::post('/patient-payment/fetch/', 'App\Http\Controllers\PatientPaymentController@show')->name('patient-payment.show');
    Route::post('/patient-payment/create/', 'App\Http\Controllers\PatientPaymentController@store')->name('patient-payment.save');
    Route::get('/patient-payment/list/', 'App\Http\Controllers\PatientPaymentController@index')->name('patient-payment.list');
    Route::get('/patient-payment/edit/{id}/', 'App\Http\Controllers\PatientPaymentController@edit')->name('patient-payment.edit');
    Route::put('/patient-payment/edit/{id}/', 'App\Http\Controllers\PatientPaymentController@update')->name('patient-payment.update');
    Route::delete('/patient-payment/delete/{id}/', 'App\Http\Controllers\PatientPaymentController@destroy')->name('patient-payment.delete');
    // End Patient Payments //

    // Certificates //
    Route::get('/consultation/certificates/', 'App\Http\Controllers\PatientCertificateController@index')->name('certificate.index');
    Route::get('/consultation/certificate/edit/{id}', 'App\Http\Controllers\PatientCertificateController@edit')->name('certificate.edit');
    Route::put('/consultation/certificate/edit/{id}', 'App\Http\Controllers\PatientCertificateController@update')->name('certificate.update');
    Route::delete('/consultation/certificate/delete/{id}/', 'App\Http\Controllers\PatientCertificateController@destroy')->name('certificate.delete');
    // End Certificates //

    // OCT/HFA/FFA //
    Route::get('/ohf/', 'App\Http\Controllers\OHFController@index')->name('ohf.index');
    // End OCT/HFA/FFA //

    // Reports //
    Route::get('/reports/daybook/', 'App\Http\Controllers\ReportController@showdaybook')->name('reports.showdaybook');
    Route::post('/reports/daybook/', 'App\Http\Controllers\ReportController@fetchdaybook')->name('reports.fetchdaybook');
    Route::get('/reports/income-expense/', 'App\Http\Controllers\ReportController@showincomeexpense')->name('reports.showincomeexpense');
    Route::post('/reports/income-expense/', 'App\Http\Controllers\ReportController@fetchincomeexpense')->name('reports.fetchincomeexpense');
    // End Reports //

    // Helper //
    Route::get('/helper/getmedicinetype/{mid}', 'App\Http\Controllers\HelperController@getMedicineType');
    Route::get('/helper/daybook/', 'App\Http\Controllers\HelperController@getDayBookDetailed');
    // End Helper //

    // PDFs //
    Route::get('/patient-history/{id}/', [PDFController::class, 'patienthistory'])->name('patienthistory');
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
    Route::get('/pharmacy/receipt/{id}/', [PDFController::class, 'pharmacyreceipt']);
    Route::get('/license/vision/{id}/', [PDFController::class, 'visioncertificate']);
    Route::get('/license/medical/{id}/', [PDFController::class, 'medicalcertificate']);
    // End PDFs //

    // Settings //
    Route::get('/settings/consultation/', [SettingsController::class, 'showConsultation'])->name('settings.showconsultation');
    Route::put('/settings/consultation/', [SettingsController::class, 'updateConsultation'])->name('settings.consultation.update');
    Route::get('/settings/change-password/', [SettingsController::class, 'showpassword'])->name('settings.showpassword');
    Route::post('/settings/change-password/', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    // End Settings //
});


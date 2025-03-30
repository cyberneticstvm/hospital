<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AxialLengthController;
use App\Http\Controllers\CampController;
use App\Http\Controllers\CampMasterController;
use App\Http\Controllers\OperationNoteController;
use App\Http\Controllers\LetterheadController;
use App\Http\Controllers\MedicalFitnessController;
use App\Http\Controllers\MedicalFitnessHeadController;
use App\Http\Controllers\PatientReferenceController;
use App\Http\Controllers\PostOperativeMedicineController;
use App\Http\Controllers\SurgeryMedicineController;
use App\Http\Controllers\PachymetryController;
use App\Http\Controllers\HFAController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DischargeSummaryController;
use App\Http\Controllers\InhouseCampController;
use App\Http\Controllers\SurgeryConsumableController;
use App\Http\Controllers\PostOperativeInstructionController;
use App\Http\Controllers\RoyaltyCardProcedure;
use App\Http\Controllers\TestAdvisedController;

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

Route::get('/test', [DashboardController::class, 'test'])->name('test');

Route::get('/testsmsapi', [AppointmentController::class, 'testsmsapi'])->name('testsmsapi');
Route::post('/testsmsapi', [AppointmentController::class, 'testsmsapisend'])->name('testsmsapisend');

Route::get('/', function () {
    return view('/login');
})->name('login');

Route::get('/schedule/appointment', 'App\Http\Controllers\AppointmentController@publicAppointment')->name('schedule.appointment');
Route::post('/schedule/appointment', 'App\Http\Controllers\AppointmentController@publicAppointmentUpdate')->name('schedule.appointment.update');
Route::get('/schedule/appointment/confirmation', function () {
    return view('appointment.people.confirmation');
})->name('schedule.appointment.confirmation');
Route::post('/', 'App\Http\Controllers\DashboardController@userlogin')->name('login');

// Authentication //
Route::get('/auth/certificate/{id}/', [HelperController::class, 'certificateAuthentication'])->name('auth.certificateAuth');
// End Authentication //

Route::get('/errors/error/', function () {
    return view('errors.error');
})->name('error');

Route::group(['middleware' => ['auth']], function () {
    /*Route::get('/dash/', function () {
        return view('dash');
    })->name('dash');*/
    Route::get('/dash/', [DashboardController::class, 'index'])->name('dash.index');
    Route::get('/patientOverview/', [DashboardController::class, 'patientOverview'])->name('dash.patientoverview');
    Route::get('/patientmonth/', [DashboardController::class, 'patientMonth'])->name('dash.patientmonth');
    Route::get('/reviewmonth/', [DashboardController::class, 'reviewMonth'])->name('dash.reviewmonth');
    Route::get('/incomeexpense/', [DashboardController::class, 'incomeExpense'])->name('dash.incomeexpense');
    Route::get('/logout/', 'App\Http\Controllers\AuthController@userlogout');
    Route::post('store_branch_session', 'App\Http\Controllers\AuthController@store_branch_session')->name('store_branch_session');
});

Route::group(['middleware' => ['auth', 'branch']], function () {

    Route::get('/test/{branch}', 'App\Http\Controllers\ReportController@getClosingBalance');

    Route::get('/permission/not-authorized/', function () {
        return view('permission');
    })->name('notauth');

    // Tests Advised //
    Route::get('/tests-advised', [TestAdvisedController::class, 'index'])->name('tests.advised');
    Route::get('/tests-advised/edit/{id}', [TestAdvisedController::class, 'edit'])->name('tests.advised.edit');
    Route::put('/tests-advised/edit/{id}', [TestAdvisedController::class, 'update'])->name('tests.advised.update');

    // End Tests Advised // 

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

    // Appointments //
    Route::get('/appointment/gettime/{date}/{branch}/{doctor}/', [AppointmentController::class, 'gettime'])->name('appointment.gettime');
    Route::get('/appointment/patient/create/{id}/', [AppointmentController::class, 'createPatient'])->name('appointment.patient.create');
    Route::get('/appointment/', [AppointmentController::class, 'index'])->name('appointment.index');
    Route::get('/appointment/create/', [AppointmentController::class, 'create'])->name('appointment.create');
    Route::post('/appointment/show/', [AppointmentController::class, 'show'])->name('appointment.show');
    Route::post('/appointment/create/', [AppointmentController::class, 'store'])->name('appointment.save');
    Route::get('/appointment/edit/{id}/', [AppointmentController::class, 'edit'])->name('appointment.edit');
    Route::put('/appointment/edit/{id}/', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}/', [AppointmentController::class, 'destroy'])->name('appointment.delete');
    Route::get('/appointment/list/', [AppointmentController::class, 'activelist'])->name('appointment.list');
    Route::delete('/appointment/list/delete/{id}/', [AppointmentController::class, 'listdestroy'])->name('appointment.list.delete');
    Route::get('/appointment/check/', [AppointmentController::class, 'check'])->name('appointment.check');
    // End Appointments //

    // Patient Registration //
    Route::get('/patient/', 'App\Http\Controllers\PatientRegistrationController@index')->name('patient.index');
    Route::get('/patient/create/', 'App\Http\Controllers\PatientRegistrationController@create');
    Route::post('/patient/create/', 'App\Http\Controllers\PatientRegistrationController@store')->name('patient.create');
    Route::get('/patient/{id}/edit/', 'App\Http\Controllers\PatientRegistrationController@edit')->name('patient.edit');
    Route::put('/patient/{id}/edit/', 'App\Http\Controllers\PatientRegistrationController@update')->name('patient.update');
    Route::delete('/patient/{id}/delete/', 'App\Http\Controllers\PatientRegistrationController@destroy')->name('patient.delete');

    Route::get('/patient/history/{id}/', 'App\Http\Controllers\PatientRegistrationController@show')->name('patient.history');

    Route::post('/patient/select', 'App\Http\Controllers\PatientRegistrationController@proceed')->name('patient.proceed');
    // End Patient Registration //

    // Search //
    Route::get('/patient/search/', 'App\Http\Controllers\PatientRegistrationController@search')->name('patient.search');
    Route::post('/patient/search/', 'App\Http\Controllers\PatientRegistrationController@fetch')->name('patient.fetch');

    Route::get('/patient/consultation/search/', 'App\Http\Controllers\PatientRegistrationController@searchc')->name('patientc.search');
    Route::post('/patient/consultation/search/', 'App\Http\Controllers\PatientRegistrationController@fetchconsultation')->name('patient-consultation.fetch');

    Route::get('/patient/medical-record/search/', 'App\Http\Controllers\PatientRegistrationController@searchm')->name('patientm.search');
    Route::post('/patient/medical-record/search/', 'App\Http\Controllers\PatientRegistrationController@fetchmedicalrecord')->name('patient-medical-record.fetch');

    Route::get('/spectacle/search/', [SearchController::class, 'spectaclesearch'])->name('spectacle.search');
    Route::post('/spectacle/search/', [SearchController::class, 'spectaclefetch'])->name('spectacle.fetch');

    Route::get('/income-expense/search/', [SearchController::class, 'iesearch'])->name('ie.search');
    Route::post('/income-expense/search/', [SearchController::class, 'iefetch'])->name('ie.fetch');

    Route::get('/search/patient/', [SearchController::class, 'searchPatient'])->name('search.patient');
    Route::post('/search/patient/', [SearchController::class, 'fetchPatient'])->name('fetch.patient');

    Route::get('/search/kta/', [SearchController::class, 'searchKta'])->name('search.kta');
    Route::post('/search/kta/', [SearchController::class, 'fetchKta'])->name('fetch.kta');
    // End Search //

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
    Route::get('/consultation/reopen/{id}/{appid}/', 'App\Http\Controllers\PatientReferenceController@reopen'); //patient_id & appointment_id
    Route::get('/consultation/create-patient-reference/{id}/', 'App\Http\Controllers\PatientReferenceController@create');
    Route::post('/consultation/create-patient-reference/', 'App\Http\Controllers\PatientReferenceController@store')->name('patient_reference.create');
    Route::get('/consultation/edit-patient-reference/{id}/', 'App\Http\Controllers\PatientReferenceController@edit')->name('patient_reference.edit');
    Route::put('/consultation/edit-patient-reference/{id}/', 'App\Http\Controllers\PatientReferenceController@update')->name('patient_reference.update');
    Route::delete('/consultation/edit-patient-reference/{id}/', 'App\Http\Controllers\PatientReferenceController@destroy')->name('patient_reference.delete');

    Route::controller(PatientReferenceController::class)->group(function () {
        Route::get('/consultation/opto-to-doc', 'optoToDoc')->name('doc.opto');
        Route::post('/consultation/opto-to-doc', 'optoToDocUpdate')->name('doc.opto.assign');
    });

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
    Route::post('/consultation/medical-records/edit/{id}/', 'App\Http\Controllers\PatientMedicalRecordController@update')->name('medical-records.update');
    Route::delete('/consultation/medical-records/delete/{id}', 'App\Http\Controllers\PatientMedicalRecordController@destroy')->name('medical-records.delete');
    // End Consultation & Medical Records //

    // Symptoms //
    Route::get('/symptom/index/{type}', 'App\Http\Controllers\SymptomController@index');
    Route::get('/symptom/products/{type}', 'App\Http\Controllers\SymptomController@products');
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

    Route::get('/consultation/medicine/update/{id}/', 'App\Http\Controllers\MedicineController@addUpdate')->name('medicine.add.update');
    Route::put('/consultation/medicine/update/{id}/', 'App\Http\Controllers\MedicineController@addUpdateSave')->name('medicine.add.update.save');
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

    // Postop Medicine //
    Route::get('/postop/medicine/', [PostOperativeMedicineController::class, 'index'])->name('postop.medicine.index');
    Route::get('/postop/medicine/{id}/', [PostOperativeMedicineController::class, 'create'])->name('postop.medicine.create');
    Route::post('/postop/medicine/create/', [PostOperativeMedicineController::class, 'store'])->name('postop.medicine.save');
    Route::get('/postop/medicine/edit/{id}/', [PostOperativeMedicineController::class, 'edit'])->name('postop.medicine.edit');
    Route::put('/postop/medicine/edit/{id}/', [PostOperativeMedicineController::class, 'update'])->name('postop.medicine.update');
    Route::delete('/postop/medicine/edit/{id}/',  [PostOperativeMedicineController::class, 'destroy'])->name('surgery.medicine.delete');
    // End Postop Medicine//

    // Surgery Medicine //
    Route::get('/surgery/medicine/', [SurgeryMedicineController::class, 'index'])->name('surgery.medicine.index');
    Route::get('/surgery/medicine/{id}/', [SurgeryMedicineController::class, 'create'])->name('surgery.medicine.create');
    Route::post('/surgery/medicine/create/', [SurgeryMedicineController::class, 'store'])->name('surgery.medicine.save');
    Route::get('/surgery/medicine/edit/{id}/', [SurgeryMedicineController::class, 'edit'])->name('surgery.medicine.edit');
    Route::put('/surgery/medicine/edit/{id}/', [SurgeryMedicineController::class, 'update'])->name('surgery.medicine.update');
    Route::delete('/surgery/medicine/edit/{id}/',  [SurgeryMedicineController::class, 'destroy'])->name('surgery.medicine.delete');
    // End Surgery Medicine //

    // supplier //
    Route::get('/supplier/', 'App\Http\Controllers\SupplierController@index')->name('supplier.index');
    Route::get('/supplier/create/', 'App\Http\Controllers\SupplierController@create')->name('supplier.create');
    Route::post('/supplier/create/', 'App\Http\Controllers\SupplierController@store')->name('supplier.save');
    Route::get('/supplier/edit/{id}/', 'App\Http\Controllers\SupplierController@edit')->name('supplier.edit');
    Route::put('/supplier/edit/{id}/', 'App\Http\Controllers\SupplierController@update')->name('supplier.update');
    Route::delete('/supplier/delete/{id}/', 'App\Http\Controllers\SupplierController@destroy')->name('supplier.delete');
    // End supplier //

    // manufacturer //
    Route::get('/manufacturer/', [ManufacturerController::class, 'index'])->name('manufacturer.index');
    Route::get('/manufacturer/create/', [ManufacturerController::class, 'create'])->name('manufacturer.create');
    Route::post('/manufacturer/create/', [ManufacturerController::class, 'store'])->name('manufacturer.save');
    Route::get('/manufacturer/edit/{id}/', [ManufacturerController::class, 'edit'])->name('manufacturer.edit');
    Route::put('/manufacturer/edit/{id}/', [ManufacturerController::class, 'update'])->name('manufacturer.update');
    Route::delete('/manufacturer/delete/{id}/', [ManufacturerController::class, 'destroy'])->name('manufacturer.delete');
    // End manufacturer //

    // purchase //
    Route::get('/purchase/', 'App\Http\Controllers\PurchaseController@index')->name('purchase.index');
    Route::get('/purchase/create/', 'App\Http\Controllers\PurchaseController@create')->name('purchase.create');
    Route::post('/purchase/create/', 'App\Http\Controllers\PurchaseController@store')->name('purchase.save');
    Route::get('/purchase/edit/{id}/', 'App\Http\Controllers\PurchaseController@edit')->name('purchase.edit');
    Route::put('/purchase/edit/{id}/', 'App\Http\Controllers\PurchaseController@update')->name('purchase.update');
    Route::delete('/purchase/delete/{id}/', 'App\Http\Controllers\PurchaseController@destroy')->name('purchase.delete');
    // End purchase //

    // transfer //
    Route::get('/product-transfer/', 'App\Http\Controllers\ProductTransferController@index')->name('product-transfer.index');
    Route::get('/product-transfer/create/', 'App\Http\Controllers\ProductTransferController@create')->name('product-transfer.create');
    Route::post('/product-transfer/create/', 'App\Http\Controllers\ProductTransferController@store')->name('product-transfer.save');
    Route::get('/product-transfer/edit/{id}/', 'App\Http\Controllers\ProductTransferController@edit')->name('product-transfer.edit');
    Route::put('/product-transfer/edit/{id}/', 'App\Http\Controllers\ProductTransferController@update')->name('product-transfer.update');
    Route::delete('/product-transfer/delete/{id}/', 'App\Http\Controllers\ProductTransferController@destroy')->name('product-transfer.delete');
    Route::get('/stock-in-hand/', 'App\Http\Controllers\ProductTransferController@show')->name('stock-in-hand.show');
    Route::post('/stock-in-hand/', 'App\Http\Controllers\ProductTransferController@fetch')->name('stock-in-hand.fetch');

    Route::get('/product/transfer/pending', 'App\Http\Controllers\ProductTransferController@pendingRegister')->name('product.transfer.pending.register');
    Route::get('/product/transfer/pending/edit/{id}', 'App\Http\Controllers\ProductTransferController@transferPendingEdit')->name('product.transfer.pending.edit');
    Route::post('/product/transfer/pending/edit/{id}', 'App\Http\Controllers\ProductTransferController@transferPendingUpdate')->name('product.transfer.pending.update');
    // End transfer //

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

    // HFA //
    Route::get('/review/hfa', [HFAController::class, 'review'])->name('hfa.review');
    Route::get('/completed/hfa', [HFAController::class, 'completed'])->name('hfa.completed');
    Route::get('/direct/hfa', [HFAController::class, 'direct'])->name('hfa.direct');
    Route::get('/hfa', [HFAController::class, 'index'])->name('hfa.index');
    Route::post('/hfa/show/', [HFAController::class, 'show'])->name('hfa.show');
    Route::post('/hfa/create/', [HFAController::class, 'store'])->name('hfa.save');
    Route::get('/hfa/edit/{id}/', [HFAController::class, 'edit'])->name('hfa.edit');
    Route::put('/hfa/edit/{id}/', [HFAController::class, 'update'])->name('hfa.update');
    Route::delete('/hfa/delete/{id}/', [HFAController::class, 'destroy'])->name('hfa.delete');
    // End HFA //

    // Axial Length //
    Route::get('/procedure/axial-length', [AxialLengthController::class, 'index'])->name('procedure.axial.length');
    Route::post('/procedure/axial-length/show/', [AxialLengthController::class, 'show'])->name('procedure.axial.show');
    Route::post('/procedure/axial-length/create/', [AxialLengthController::class, 'store'])->name('procedure.axial.save');
    Route::get('/procedure/axial-length/edit/{id}/', [AxialLengthController::class, 'edit'])->name('procedure.axial.edit');
    Route::put('/procedure/axial-length/edit/{id}/', [AxialLengthController::class, 'update'])->name('procedure.axial.update');
    Route::delete('/procedure/axial-length/delete/{id}/', [AxialLengthController::class, 'destroy'])->name('procedure.axial.delete');
    // End Axial Length //

    // Pachymetry //
    Route::get('/pachymetry/', [PachymetryController::class, 'index'])->name('pachymetry.index');
    Route::post('/pachymetry/show/', [PachymetryController::class, 'show'])->name('pachymetry.show');
    Route::post('/pachymetry/create/', [PachymetryController::class, 'store'])->name('pachymetry.save');
    Route::get('/pachymetry/edit/{id}/', [PachymetryController::class, 'edit'])->name('pachymetry.edit');
    Route::put('/pachymetry/edit/{id}/', [PachymetryController::class, 'update'])->name('pachymetry.update');
    Route::delete('/pachymetry/delete/{id}/', [PachymetryController::class, 'destroy'])->name('pachymetry.delete');
    // end Pachymetry //

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

    // oct //
    Route::get('/oct', 'App\Http\Controllers\OCTController@index')->name('oct.index');
    Route::post('/oct', 'App\Http\Controllers\OCTController@show')->name('oct.show');
    Route::post('/oct/create/', 'App\Http\Controllers\OCTController@store')->name('oct.save');
    Route::get('/oct/edit/{id}/', 'App\Http\Controllers\OCTController@edit')->name('oct.edit');
    Route::put('/oct/edit/{id}/', 'App\Http\Controllers\OCTController@update')->name('oct.update');
    Route::delete('/oct/delete/{id}/', 'App\Http\Controllers\OCTController@destroy')->name('oct.delete');
    // end oct //

    // ascan //
    Route::get('/ascan/', 'App\Http\Controllers\AscanController@index')->name('ascan.index');
    Route::post('/ascan/show/', 'App\Http\Controllers\AscanController@show')->name('ascan.show');
    Route::post('/ascan/create/', 'App\Http\Controllers\AscanController@store')->name('ascan.save');
    Route::get('/ascan/edit/{id}/', 'App\Http\Controllers\AscanController@edit')->name('ascan.edit');
    Route::put('/ascan/edit/{id}/', 'App\Http\Controllers\AscanController@update')->name('ascan.update');
    Route::delete('/ascan/delete/{id}/', 'App\Http\Controllers\AscanController@destroy')->name('ascan.delete');
    // end ascan //

    // bscan //
    Route::get('/bscan', 'App\Http\Controllers\BscanController@index')->name('bscan.index');
    Route::post('/bscan', 'App\Http\Controllers\BscanController@show')->name('bscan.show');
    Route::post('/bscan/create/', 'App\Http\Controllers\BscanController@store')->name('bscan.save');
    Route::get('/bscan/edit/{id}/', 'App\Http\Controllers\BscanController@edit')->name('bscan.edit');
    Route::put('/bscan/edit/{id}/', 'App\Http\Controllers\BscanController@update')->name('bscan.update');
    Route::delete('/bscan/delete/{id}/', 'App\Http\Controllers\BscanController@destroy')->name('bscan.delete');
    // end bscan //

    // laser //
    Route::get('/laser', 'App\Http\Controllers\LaserController@index')->name('laser.index');
    Route::post('/laser', 'App\Http\Controllers\LaserController@show')->name('laser.show');
    Route::post('/laser/create/', 'App\Http\Controllers\LaserController@store')->name('laser.save');
    Route::get('/laser/edit/{id}/', 'App\Http\Controllers\LaserController@edit')->name('laser.edit');
    Route::put('/laser/edit/{id}/', 'App\Http\Controllers\LaserController@update')->name('laser.update');
    Route::delete('/laser/delete/{id}/', 'App\Http\Controllers\LaserController@destroy')->name('laser.delete');
    // end laser //

    // surgery register //
    Route::get('/surgery/', 'App\Http\Controllers\SurgeryController@index')->name('surgery.index');
    Route::get('/surgery/edit/{id}', 'App\Http\Controllers\SurgeryController@edit')->name('surgery.edit');
    Route::put('/surgery/edit/{id}', 'App\Http\Controllers\SurgeryController@update')->name('surgery.update');
    Route::delete('/surgery/delete/{id}/', 'App\Http\Controllers\SurgeryController@destroy')->name('surgery.delete');
    // end surgery register//

    // Post Operative register //
    Route::get('/post-operative-suggestion/', 'App\Http\Controllers\PostOperativeController@index')->name('pop.index');
    // end Post Operative register//

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
    //Route::get('/patient-payment/fetch/', 'App\Http\Controllers\PatientPaymentController@show')->name('patient-payment.index');
    Route::post('/patient-payment/fetch/', 'App\Http\Controllers\PatientPaymentController@show')->name('patient-payment.show');
    Route::post('/patient-payment/create/', 'App\Http\Controllers\PatientPaymentController@store')->name('patient-payment.save');
    Route::get('/patient-payment/list/', 'App\Http\Controllers\PatientPaymentController@index')->name('patient-payment.list');
    Route::get('/patient-payment/edit/{id}/', 'App\Http\Controllers\PatientPaymentController@edit')->name('patient-payment.edit');
    Route::put('/patient-payment/edit/{id}/', 'App\Http\Controllers\PatientPaymentController@update')->name('patient-payment.update');
    Route::delete('/patient-payment/delete/{id}/', 'App\Http\Controllers\PatientPaymentController@destroy')->name('patient-payment.delete');

    Route::get('/outstanding/due', 'App\Http\Controllers\PatientPaymentController@oustandingDue')->name('patient.outstanding.due');
    Route::post('/outstanding/due', 'App\Http\Controllers\PatientPaymentController@oustandingDueFetch')->name('patient.outstanding.due.fetch');

    Route::get('/transaction/history', 'App\Http\Controllers\PatientPaymentController@transactionHistory')->name('patient.transaction.history');
    Route::post('/transaction/history', 'App\Http\Controllers\PatientPaymentController@transactionHistoryFetch')->name('patient.transaction.history.fetch');
    Route::get('/patient/transaction/history/{id}', 'App\Http\Controllers\PatientPaymentController@patientTransactionHistoryFetch')->name('patient.transaction.history.fetch1');

    Route::post('/patient-payment/discount/update', 'App\Http\Controllers\PatientPaymentController@patientPaymentDiscountUpdate')->name('patient.procedure.discount.update');


    Route::get('/paypharma/', 'App\Http\Controllers\PharmacyPaymentController@index')->name('paypharma.index');
    //Route::get('/paypharmafetch/', 'App\Http\Controllers\PharmacyPaymentController@index')->name('paypharma.index');
    Route::post('/paypharmafetch/', 'App\Http\Controllers\PharmacyPaymentController@create')->name('paypharma.fetch');
    Route::post('/paypharmasave/', 'App\Http\Controllers\PharmacyPaymentController@store')->name('paypharma.save');
    Route::get('/paypharmaedit/{id}/', 'App\Http\Controllers\PharmacyPaymentController@edit')->name('paypharma.edit');
    Route::put('/paypharmaedit/{id}/', 'App\Http\Controllers\PharmacyPaymentController@update')->name('paypharma.update');
    Route::delete('/paypharmadelete/{id}/', 'App\Http\Controllers\PharmacyPaymentController@destroy')->name('paypharma.delete');

    // End Patient Payments //

    // Certificates //
    Route::get('/consultation/certificates/', 'App\Http\Controllers\PatientCertificateController@index')->name('certificate.index');
    Route::get('/consultation/certificate/edit/{id}', 'App\Http\Controllers\PatientCertificateController@edit')->name('certificate.edit');
    Route::put('/consultation/certificate/edit/{id}', 'App\Http\Controllers\PatientCertificateController@update')->name('certificate.update');
    Route::delete('/consultation/certificate/delete/{id}/', 'App\Http\Controllers\PatientCertificateController@destroy')->name('certificate.delete');
    // End Certificates //

    // Camp //
    Route::get('/camp/', [CampController::class, 'index'])->name('camp.index');
    Route::get('/camp/create/{id}/', [CampController::class, 'create'])->name('camp.create');
    Route::post('/camp/create/{id}/', [CampController::class, 'store'])->name('camp.save');
    Route::get('/camp/edit/{id}/', [CampController::class, 'edit'])->name('camp.edit');
    Route::put('/camp/edit/{id}/', [CampController::class, 'update'])->name('camp.update');
    Route::delete('/camp/delete/{id}/', [CampController::class, 'destroy'])->name('camp.delete');
    // End Camp //

    // Camp Master //
    Route::get('/campmaster/', [CampMasterController::class, 'index'])->name('campmaster.index');
    Route::get('/campmaster/create/', [CampMasterController::class, 'create'])->name('campmaster.create');
    Route::post('/campmaster/create/', [CampMasterController::class, 'store'])->name('campmaster.save');
    Route::get('/campmaster/edit/{id}/', [CampMasterController::class, 'edit'])->name('campmaster.edit');
    Route::put('/campmaster/edit/{id}/', [CampMasterController::class, 'update'])->name('campmaster.update');
    Route::delete('/campmaster/delete/{id}/', [CampMasterController::class, 'destroy'])->name('campmaster.delete');
    // End Camp Master //

    // Inhouse Camp Master //
    Route::get('/inhousecamp', [InhouseCampController::class, 'index'])->name('inhousecamp.index');
    Route::get('/inhousecamp/create', [InhouseCampController::class, 'create'])->name('inhousecamp.create');
    Route::post('/inhousecamp/create', [InhouseCampController::class, 'store'])->name('inhousecamp.save');
    Route::get('/inhousecamp/edit/{id}', [InhouseCampController::class, 'edit'])->name('inhousecamp.edit');
    Route::put('/inhousecamp/edit/{id}', [InhouseCampController::class, 'update'])->name('inhousecamp.update');
    Route::delete('/inhousecamp/delete/{id}', [InhouseCampController::class, 'destroy'])->name('inhousecamp.delete');
    // End Inhouse Camp Master //

    // Royalty Card Procedure Management //
    Route::get('/royalty-cards', [RoyaltyCardProcedure::class, 'index'])->name('rcard.proc.index');
    Route::get('/royalty-card-procs/{id}', [RoyaltyCardProcedure::class, 'show'])->name('rcard.proc.show');
    Route::post('/royalty-card-procs/{id}', [RoyaltyCardProcedure::class, 'store'])->name('rcard.proc.save');
    // End Royalty Card Procedure Management //

    // Operation Notes //
    Route::get('/operation-notes/', [OperationNoteController::class, 'index'])->name('onote.index');
    Route::post('/operation-notes/show/', [OperationNoteController::class, 'show'])->name('onote.show');
    Route::post('/operation-notes/create/', [OperationNoteController::class, 'store'])->name('onote.save');
    Route::get('/operation-notes/edit/{id}/', [OperationNoteController::class, 'edit'])->name('onote.edit');
    Route::put('/operation-notes/edit/{id}/', [OperationNoteController::class, 'update'])->name('onote.update');
    Route::delete('/operation-notes/delete/{id}/', [OperationNoteController::class, 'destroy'])->name('onote.delete');
    // end Operation Notes //

    // Letterheads //
    Route::get('/letterheads/', [LetterheadController::class, 'index'])->name('letterheads.index');
    Route::get('/letterhead/create/', [LetterheadController::class, 'create'])->name('letterhead.create');
    Route::post('/letterhead/create/', [LetterheadController::class, 'store'])->name('letterhead.save');
    Route::get('/letterhead/edit/{id}/', [LetterheadController::class, 'edit'])->name('letterhead.edit');
    Route::put('/letterhead/edit/{id}/', [LetterheadController::class, 'update'])->name('letterhead.update');
    Route::delete('/letterhead/delete/{id}/', [LetterheadController::class, 'destroy'])->name('letterhead.delete');
    // end Letterheads //

    // Medical Fitness //
    Route::get('/medical-fitness/', [MedicalFitnessController::class, 'index'])->name('mfit.index');
    Route::post('/medical-fitness/show/', [MedicalFitnessController::class, 'show'])->name('mfit.show');
    Route::post('/medical-fitness/create/', [MedicalFitnessController::class, 'store'])->name('mfit.save');
    Route::get('/medical-fitness/edit/{id}/', [MedicalFitnessController::class, 'edit'])->name('mfit.edit');
    Route::put('/medical-fitness/edit/{id}/', [MedicalFitnessController::class, 'update'])->name('mfit.update');
    Route::delete('/medical-fitness/delete/{id}/', [MedicalFitnessController::class, 'destroy'])->name('mfit.delete');
    // end Medical Fitness //

    // Medical Fitness Head//
    Route::get('/medical-fitness-head/', [MedicalFitnessHeadController::class, 'index'])->name('mfithead.index');
    Route::get('/medical-fitness-head/create/', [MedicalFitnessHeadController::class, 'create'])->name('mfithead.create');
    Route::post('/medical-fitness-head/create/', [MedicalFitnessHeadController::class, 'store'])->name('mfithead.save');
    Route::get('/medical-fitness-head/edit/{id}/', [MedicalFitnessHeadController::class, 'edit'])->name('mfithead.edit');
    Route::put('/medical-fitness-head/edit/{id}/', [MedicalFitnessHeadController::class, 'update'])->name('mfithead.update');
    Route::delete('/medical-fitness-head/delete/{id}/', [MedicalFitnessHeadController::class, 'destroy'])->name('mfithead.delete');
    // end Medical Fitness Head//

    // Documents //
    Route::get('/documents/', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents/', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/create/{id}/', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::post('/documents/create/', [DocumentController::class, 'store'])->name('documents.save');

    // End Documents //

    // Surgery Consumables //
    Route::get('/inventory/surgery-consumables', [SurgeryConsumableController::class, 'index'])->name('surgery.consumable.index');
    Route::get('/inventory/surgery-consumables/create', [SurgeryConsumableController::class, 'create'])->name('surgery.consumable.create');
    Route::post('/inventory/surgery-consumables/create', [SurgeryConsumableController::class, 'store'])->name('surgery.consumable.save');
    Route::get('/inventory/surgery-consumables/edit/{id}', [SurgeryConsumableController::class, 'edit'])->name('surgery.consumable.edit');
    Route::put('/inventory/surgery-consumables/edit/{id}', [SurgeryConsumableController::class, 'update'])->name('surgery.consumable.update');
    Route::delete('/inventory/surgery-consumables/delete/{id}', [SurgeryConsumableController::class, 'destroy'])->name('surgery.consumable.delete');

    Route::get('/manage-surgery-consumables', [SurgeryConsumableController::class, 'showsurgeryconsumable'])->name('surgery.consumable.surgey');
    Route::post('/manage-surgery-consumables', [SurgeryConsumableController::class, 'savesurgeryconsumable'])->name('surgery.consumable.surgey.save');
    Route::get('/manage-surgery-consumables/edit/{id}', [SurgeryConsumableController::class, 'editsurgeryconsumable'])->name('surgery.consumable.surgey.edit');
    Route::put('/manage-surgery-consumables/edit/{id}', [SurgeryConsumableController::class, 'updatesurgeryconsumable'])->name('surgery.consumable.surgey.update');
    Route::delete('/manage-surgery-consumables/delete/{id}', [SurgeryConsumableController::class, 'deletesurgeryconsumable'])->name('surgery.consumable.surgey.delete');

    Route::get('/patient/surgery/consumable', [SurgeryConsumableController::class, 'patientsurgeryconsumablelist'])->name('patient.surgey.consumable');
    Route::get('/patient/surgery/consumable/create', [SurgeryConsumableController::class, 'patientsurgeryconsumablecreate'])->name('patient.surgey.consumable.create');
    Route::post('/patient/surgery/consumable/create', [SurgeryConsumableController::class, 'patientsurgeryconsumablefetch'])->name('patient.surgey.consumable.fetch');
    Route::get('/patient/surgery/consumable/show', [SurgeryConsumableController::class, 'patientsurgeryconsumablelistshow'])->name('patient.surgey.consumable.show');
    Route::post('/patient/surgery/consumable/show', [SurgeryConsumableController::class, 'patientsurgeryconsumablelistsave'])->name('patient.surgey.consumable.save');
    Route::get('/patient/surgery/consumable/edit/{id}', [SurgeryConsumableController::class, 'patientsurgeryconsumablelistedit'])->name('patient.surgey.consumable.edit');
    Route::put('/patient/surgery/consumable/edit/{id}', [SurgeryConsumableController::class, 'patientsurgeryconsumablelistupdate'])->name('patient.surgey.consumable.update');
    Route::delete('/patient/surgery/consumable/delete/{id}', [SurgeryConsumableController::class, 'patientsurgeryconsumablelistdelete'])->name('patient.surgey.consumable.delete');
    // End Surgery Consumables //

    // Post-operative Instructions //
    Route::get('/post-oprerative-instruction', [PostOperativeInstructionController::class, 'index'])->name('poi.index');
    Route::get('/post-oprerative-instruction/create', [PostOperativeInstructionController::class, 'create'])->name('poi.show');
    Route::post('/post-oprerative-instruction/create', [PostOperativeInstructionController::class, 'store'])->name('poi.save');
    Route::get('/post-oprerative-instruction/edit/{id}', [PostOperativeInstructionController::class, 'edit'])->name('poi.edit');
    Route::put('/post-oprerative-instruction/edit/{id}', [PostOperativeInstructionController::class, 'update'])->name('poi.update');
    Route::delete('/post-oprerative-instruction/delete/{id}', [PostOperativeInstructionController::class, 'destroy'])->name('poi.delete');
    // End post-operative instructions //

    // Discharge Summary //
    Route::get('/discharge-summary', [DischargeSummaryController::class, 'index'])->name('dsummary.index');
    Route::post('/discharge-summary', [DischargeSummaryController::class, 'show'])->name('dsummary.show');
    Route::get('/discharge-summary/create/', [DischargeSummaryController::class, 'create'])->name('dsummary.create');
    Route::post('/discharge-summary/create/', [DischargeSummaryController::class, 'store'])->name('dsummary.save');
    Route::get('/discharge-summary/edit/{id}/', [DischargeSummaryController::class, 'edit'])->name('dsummary.edit');
    Route::put('/discharge-summary/edit/{id}/', [DischargeSummaryController::class, 'update'])->name('dsummary.update');
    Route::delete('/discharge-summary/delete/{id}/', [DischargeSummaryController::class, 'destroy'])->name('dsummary.delete');
    // End Discharge Summary //

    // Reports //

    Route::get('/reports/daybookcc/', 'App\Http\Controllers\ReportController@showdaybookcc')->name('reports.showdaybook.cc');
    Route::post('/reports/daybookcc/', 'App\Http\Controllers\ReportController@fetchdaybookcc')->name('reports.fetchdaybook.cc');

    Route::get('/reports/daybook/', 'App\Http\Controllers\ReportController@showdaybookcc')->name('reports.showdaybook');
    Route::post('/reports/daybook/', 'App\Http\Controllers\ReportController@fetchdaybookcc')->name('reports.fetchdaybook');
    Route::get('/reports/income-expense/', 'App\Http\Controllers\ReportController@showincomeexpense')->name('reports.showincomeexpense');
    Route::post('/reports/income-expense/', 'App\Http\Controllers\ReportController@fetchincomeexpense')->name('reports.fetchincomeexpense');
    Route::get('/reports/payments/', 'App\Http\Controllers\ReportController@showpayment')->name('reports.showpayment');
    Route::post('/reports/payments/', 'App\Http\Controllers\ReportController@fetchpayment')->name('reports.fetchpayment');

    Route::get('/reports/active-users', 'App\Http\Controllers\ReportController@activeusers')->name('reports.activeusers');

    Route::get('/reports/login-log', 'App\Http\Controllers\ReportController@showloginlog')->name('reports.showloginlog');
    Route::post('/reports/login-log', 'App\Http\Controllers\ReportController@fetchloginlog')->name('reports.fetchloginlog');

    Route::get('/reports/appointments/', 'App\Http\Controllers\ReportController@showappointment')->name('reports.appointment.show');
    Route::post('/reports/appointments/', 'App\Http\Controllers\ReportController@fetchappointment')->name('reports.appointment.fetch');

    Route::get('/reports/patient/', 'App\Http\Controllers\ReportController@showpatient')->name('reports.patient.show');
    Route::post('/reports/patient/', 'App\Http\Controllers\ReportController@fetchpatient')->name('reports.patient.fetch');
    Route::get('/reports/patient/surgery/payments', 'App\Http\Controllers\ReportController@surgeryPayments')->name('reports.patient.surgery.payments');
    Route::post('/reports/patient/surgery/payments', 'App\Http\Controllers\ReportController@fetchSurgeryPayments')->name('reports.patient.surgery.payments.fetch');

    Route::get('/reports/patient/pharmacy', 'App\Http\Controllers\ReportController@pharmacy')->name('reports.patient.pharmacy');
    Route::post('/reports/patient/pharmacy', 'App\Http\Controllers\ReportController@fetchPharmacy')->name('reports.patient.pharmacy.fetch');

    Route::get('/reports/mrecord/', 'App\Http\Controllers\ReportController@showmRecord')->name('reports.mrecord.show');
    Route::post('/reports/mrecord/', 'App\Http\Controllers\ReportController@fetchmRecord')->name('reports.mrecord.fetch');

    Route::get('/reports/surgeyregister/', 'App\Http\Controllers\ReportController@showSurgery')->name('reports.surgeyregister.show');
    Route::post('/reports/surgeyregister/', 'App\Http\Controllers\ReportController@fetchSurgery')->name('reports.surgeyregister.fetch');

    Route::get('/reports/postop/', 'App\Http\Controllers\ReportController@showPostOp')->name('reports.postop.show');
    Route::post('/reports/postop/', 'App\Http\Controllers\ReportController@fetchPostOp')->name('reports.postop.fetch');

    Route::get('/reports/testsadvised/', 'App\Http\Controllers\ReportController@showtAdvised')->name('reports.tadvised.show');
    Route::post('/reports/testsadvised/', 'App\Http\Controllers\ReportController@fetchtAdvised')->name('reports.tadvised.fetch');

    Route::get('/reports/hfa/', 'App\Http\Controllers\ReportController@showHfa')->name('reports.hfa.show');
    Route::post('/reports/hfa/', 'App\Http\Controllers\ReportController@fetchHfa')->name('reports.hfa.fetch');

    Route::get('/reports/tests/', 'App\Http\Controllers\ReportController@showTests')->name('reports.tests.show');
    Route::post('/reports/tests/', 'App\Http\Controllers\ReportController@fetchTests')->name('reports.tests.fetch');

    Route::get('/reports/glasses/prescribed', 'App\Http\Controllers\ReportController@glassesPrescribed')->name('reports.glasses.prescribed');
    Route::post('/reports/glasses/prescribed', 'App\Http\Controllers\ReportController@fetchGlassesPrescribed')->name('reports.glasses.prescribed.fetch');

    Route::get('/reports/discount/', 'App\Http\Controllers\ReportController@showDiscount')->name('reports.discount.show');
    Route::post('/reports/discount/', 'App\Http\Controllers\ReportController@fetchDiscount')->name('reports.discount.fetch');

    Route::get('/reports/procedure/cancelled', 'App\Http\Controllers\ReportController@procedureCancelled')->name('reports.procedure.cancelled');
    Route::post('/reports/procedure/cancelled', 'App\Http\Controllers\ReportController@fetchProcedureCancelled')->name('reports.procedure.cancelled.fetch');

    // End Reports //

    // Helper //
    Route::get('/helper/getmedicinetype/{mid}', 'App\Http\Controllers\HelperController@getMedicineType');
    Route::get('/helper/daybook/', 'App\Http\Controllers\HelperController@getDayBookDetailed');
    Route::get('/helper/inventory/', 'App\Http\Controllers\HelperController@getInventoryDetailed');
    Route::get('/helper/getproductfortransfer/', [HelperController::class, 'getProductForTransfer']);
    Route::get('/helper/getPdctPrice/', [HelperController::class, 'getProductPrice']);
    Route::get('/helper/getlabtests/', [HelperController::class, 'getlabtests']);
    Route::get('/helper/updatesmsstatus/', [HelperController::class, 'updatesmsstatus']);
    Route::get('/helper/getsurgeryconsumables/', [HelperController::class, 'getsurgeryconsumables']);
    // End Helper //

    // PDFs //
    Route::get('/patient-history/{id}/', [PDFController::class, 'patienthistory'])->name('patienthistory');
    Route::get('/generate-token/{id}/', [PDFController::class, 'token']);
    Route::get('/generate-prescription/{id}/', [PDFController::class, 'prescription']);
    Route::get('/generate-receipt/{id}/', [PDFController::class, 'receipt']);
    Route::get('/certificate/receipt/{id}/', [PDFController::class, 'certreceipt']);
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
    Route::get('/camp/print/{id}/', [PDFController::class, 'campprint']);
    Route::get('/campmaster/print/{id}/', [PDFController::class, 'campmasterprint']);
    Route::get('/tonometry/receipt/{id}/', [PDFController::class, 'tonometryreceipt']);
    Route::get('/tonometry/report/{id}/', [PDFController::class, 'tonometryreport']);
    Route::get('/keratometry/receipt/{id}/', [PDFController::class, 'keratometryreceipt']);
    Route::get('/keratometry/report/{id}/', [PDFController::class, 'keratometryreport']);
    Route::get('/ascan/receipt/{id}/', [PDFController::class, 'ascanreceipt']);
    Route::get('/ascan/report/{id}/', [PDFController::class, 'ascanreport']);
    Route::get('/vision-receipt/{id}/', [PDFController::class, 'visionreceipt']);
    Route::get('/printletterhead/{id}/', [PDFController::class, 'printletterhead']);
    Route::get('/medical-fitness/print/{id}/', [PDFController::class, 'printmfit']);
    Route::get('/pachymetry/receipt/{id}/', [PDFController::class, 'pachymetryreceipt']);
    Route::get('/pachymetry/report/{id}/', [PDFController::class, 'pachymetryreport']);
    Route::get('/purchase/bill/{id}/', [PDFController::class, 'purchasebill']);
    Route::get('/product-transfer/bill/{id}/', [PDFController::class, 'producttransferbill']);
    Route::get('/patient/payments/bill/{id}/', [PDFController::class, 'patientpaymentbill']);
    Route::get('/onote/report/{id}/', [PDFController::class, 'printonote']);
    Route::get('/hfa/receipt/{id}/', [PDFController::class, 'hfareceipt']);
    Route::get('/oct/receipt/{id}/', [PDFController::class, 'octreceipt']);
    Route::get('/bscan/receipt/{id}/', [PDFController::class, 'bscanreceipt']);
    Route::get('/laser/receipt/{id}/', [PDFController::class, 'laserreceipt']);
    Route::get('/surgery/consumable/receipt/{id}/', [PDFController::class, 'surgeryconsumablereceipt']);
    Route::get('/dsummary/report/{id}', [PDFController::class, 'dsummary']);
    Route::get('/patient/owed/history/{id}', [PDFController::class, 'patientTransactionHistory'])->name('patient.transaction.history.pdf');
    Route::get('/patient/owed/history/mrn/{id}', [PDFController::class, 'patientTransactionHistoryMrn'])->name('patient.transaction.history.mrn.pdf');

    Route::get('/axial-length/receipt/{id}/', [PDFController::class, 'axialLengthReceipt'])->name('receipt.axial.length');
    Route::get('/axial-length/report/{id}/', [PDFController::class, 'axialLengthReport'])->name('report.axial.length');
    // End PDFs //

    // Settings //
    Route::get('/settings/consultation/', [SettingsController::class, 'showConsultation'])->name('settings.showconsultation');
    Route::put('/settings/consultation/', [SettingsController::class, 'updateConsultation'])->name('settings.consultation.update');
    Route::get('/settings/change-password/', [SettingsController::class, 'showpassword'])->name('settings.showpassword');
    Route::post('/settings/change-password/', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::get('/settings/revise-closing-balance/', [SettingsController::class, 'fetchclosingbalance'])->name('settings.fetchclosingbalance');
    Route::post('/settings/revise-closing-balance/', [SettingsController::class, 'fetchClosingBalanceforUpdate'])->name('settings.fetchClosingBalanceforUpdate');
    Route::post('/settings/revise-closing-balance/update/', [SettingsController::class, 'updateClosingBalance'])->name('settings.closingbalance.update');
    Route::get('/settings/appointment/', [SettingsController::class, 'showAppointment'])->name('settings.showappointment');
    Route::put('/settings/appointment/', [SettingsController::class, 'updateAppointment'])->name('settings.appointment.update');
    // End Settings //

    Route::get('/switch/branch/{branch}', [HelperController::class, 'switchBranch'])->name('switch.branch');
});

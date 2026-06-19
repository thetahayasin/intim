<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
//associate
use App\Http\Controllers\AssociateAuthController;
use App\Http\Controllers\AssociateAttendanceController;
use App\Http\Controllers\AssociateLeaveController;
use App\Http\Controllers\AssociateDashboardController;
use App\Http\Controllers\AssociateProgressController;
use App\Http\Controllers\AssociateProfileController;
//admin
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AdminLeaveController;
use App\Http\Controllers\AdminAssociateController;
use App\Http\Controllers\AdminProgressController;
use App\Http\Controllers\AdminPassController;
use App\Http\Controllers\AdminSettingsController;
//bill
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AdminBillingController;
use App\Http\Controllers\AdminReceiptController;

//holidays
use App\Http\Controllers\PublicHolidayController;
//resources
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\AssociateResourceController;
//documents
use App\Http\Controllers\AdminDocumentController;
//duplicates (temp)
use App\Http\Controllers\DuplicateClientController;










/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//admin
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //dashboard
    Route::get('/e/dashboard', [AdminDashboardController::class, 'index'])->name('e.dash');
    Route::get('/export-client-report', [AdminDashboardController::class, 'exportClientReport'])->name('export.client.report');
    Route::get('/e/reports', [ReportsController::class, 'index'])->name('e.reports');


    //holidays
    Route::get('/calendar', [PublicHolidayController::class, 'index'])->name('calender.holidays');
    Route::post('/save-holidays', [PublicHolidayController::class, 'store'])->name('save.holidays');
    Route::put('/delete-holidays/{id}', [PublicHolidayController::class, 'destroy'])->name('delete.holidays');


    //leave
    Route::get('/e/leaves', [AdminLeaveController::class, 'index'])->name('e.leave');
    Route::post('/e/leaves/approve/{id}', [AdminLeaveController::class, 'approveAllLeaves'])->name('e.leave.approve');
    Route::get('/e/leaves/view/{id}', [AdminLeaveController::class, 'viewLeaveDates'])->name('e.leave.view');
    Route::post('/e/leaves/reject/{id}', [AdminLeaveController::class, 'reject'])->name('e.leave.reject');
    Route::get('/e/leaves/{associate_id}', [AdminLeaveController::class, 'show'])->name('e.leave.show');


    Route::post('/e/leaves/sapprove/{id}', [AdminLeaveController::class, 'sapprove'])->name('e.leave.sapprove');
    Route::post('/e/leaves/sreject/{id}', [AdminLeaveController::class, 'sreject'])->name('e.leave.sreject');


    //associate
    Route::get('/e/associates', [AdminAssociateController::class, 'index'])->name('e.associate');
    Route::put('/e/associates/deactivate/{id}', [AdminAssociateController::class, 'deactive'])->name('e.associate.deactive');
    Route::put('/e/associates/reactivate/{id}', [AdminAssociateController::class, 'reactive'])->name('e.associate.reactive');
    Route::get('/e/associates/create', [AdminAssociateController::class, 'create'])->name('e.associate.create');
    Route::post('/e/associates/add', [AdminAssociateController::class, 'add'])->name('e.associate.add');
    Route::get('/associate/edit/{id}', [AdminAssociateController::class, 'edit'])->name('e.associate.edit');
    Route::put('/associate/{id}', [AdminAssociateController::class, 'update'])->name('e.associate.update');

    //progress
    Route::get('/e/progress', [AdminProgressController::class, 'index'])->name('e.progress');
    Route::get('/e/progress/breakup/{id}', [AdminProgressController::class, 'breakup'])->name('e.progress.breakup');
    
    //settings
    Route::get('/e/settings', [AdminSettingsController::class, 'index'])->name('e.settings');
    Route::post('/e/settings/password', [AdminSettingsController::class, 'updatePassword'])->name('e.settings.password');
    Route::post('/e/settings/smtp', [AdminSettingsController::class, 'updateSmtp'])->name('e.settings.smtp');
    Route::post('/e/settings/branding', [AdminSettingsController::class, 'updateBranding'])->name('e.settings.branding');
    // Legacy redirect
    Route::get('/e/password/change', fn() => redirect()->route('e.settings'))->name('e.password');
    Route::post('/e/password/update', [AdminPassController::class, 'update'])->name('e.password.update');


    //billing
    //client
    Route::get('/e/clients', [AdminClientController::class, 'index'])->name('e.client');
    Route::get('/e/client/create', [AdminClientController::class, 'create'])->name('e.client.create');
    Route::post('/e/client/add', [AdminClientController::class, 'add'])->name('e.client.add');
    Route::get('/client/edit/{id}', [AdminClientController::class, 'edit'])->name('e.client.edit');
    Route::put('/client/update/{id}', [AdminClientController::class, 'update'])->name('e.client.update');
    Route::get('/client/stats/{id}', [AdminClientController::class, 'stats'])->name('e.client.stats');
    Route::get('/search/client/', [AdminClientController::class, 'search'])->name('e.client.search');




    //bill
    Route::get('/e/billings', [AdminBillingController::class, 'index'])->name('e.billings');
    Route::get('/e/billings/filter/{name}', [AdminBillingController::class, 'filter'])->name('e.billings.filter');
    Route::get('/e/billing/create', [AdminBillingController::class, 'create'])->name('e.billing.create');
    Route::get('/e/billing/print/{id}', [AdminBillingController::class, 'print'])->name('e.billing.print');
    Route::get('/e/billing/edit/{id}', [AdminBillingController::class, 'editForm'])->name('e.billing.edit');
    Route::put('/e/billing/delete/{id}', [AdminBillingController::class, 'delete'])->name('e.billing.delete');
    // Route::put('/e/billing/halt/{id}', [AdminBillingController::class, 'halt'])->name('e.billing.halt');
    Route::get('/e/billing/stats/{id}', [AdminBillingController::class, 'stats'])->name('e.billing.stats');
    Route::get('/search', [AdminBillingController::class, 'search'])->name('e.bill.search');


    //sale
    // Route::put('/e/sale/delete/{id}', [AdminBillingController::class, 'saleDelete'])->name('e.sale.delete');

    //receipt
    Route::get('/e/receipts', [AdminReceiptController::class, 'index'])->name('e.receipts');
    Route::put('/e/receipt/delete/{id}', [AdminReceiptController::class, 'delete'])->name('e.receipt.delete');
    Route::get('/e/receipts/create', [AdminReceiptController::class, 'create'])->name('e.receipts.create');


    Route::get('/associate/{id}/month/{year}/{month}',
        [AdminProgressController::class, 'monthDetails']
    )->name('e.month.details');
    Route::post('/e/attendance/{id}/update-day',
        [AdminProgressController::class, 'updateDayAttendance']
    )->name('e.attendance.update.day');

    // Resources
    Route::get('/e/resources', [AdminResourceController::class, 'index'])->name('e.resources');
    Route::get('/e/resources/create', [AdminResourceController::class, 'create'])->name('e.resources.create');
    Route::post('/e/resources/store', [AdminResourceController::class, 'store'])->name('e.resources.store');
    Route::get('/e/resources/{id}/edit', [AdminResourceController::class, 'edit'])->name('e.resources.edit');
    Route::post('/e/resources/{id}/update', [AdminResourceController::class, 'update'])->name('e.resources.update');
    Route::post('/e/resources/{id}/approve', [AdminResourceController::class, 'approve'])->name('e.resources.approve');
    Route::post('/e/resources/{id}/reject', [AdminResourceController::class, 'reject'])->name('e.resources.reject');
    Route::delete('/e/resources/{id}', [AdminResourceController::class, 'destroy'])->name('e.resources.destroy');

    // Documents (Proposals & Agreements)
    Route::get('/e/documents', [AdminDocumentController::class, 'index'])->name('e.documents');
    Route::get('/e/documents/create', [AdminDocumentController::class, 'create'])->name('e.documents.create');
    Route::post('/e/documents/store', [AdminDocumentController::class, 'store'])->name('e.documents.store');
    Route::get('/e/documents/view/{id}', [AdminDocumentController::class, 'view'])->name('e.documents.view');
    Route::get('/e/documents/{id}/edit', [AdminDocumentController::class, 'edit'])->name('e.documents.edit');
    Route::post('/e/documents/{id}/update', [AdminDocumentController::class, 'update'])->name('e.documents.update');
    Route::delete('/e/documents/{id}', [AdminDocumentController::class, 'destroy'])->name('e.documents.destroy');
    Route::post('/e/documents/{id}/signed-pdf', [AdminDocumentController::class, 'uploadSignedPdf'])->name('e.documents.signed-pdf.upload');
    Route::get('/e/documents/{id}/signed-pdf', [AdminDocumentController::class, 'downloadSignedPdf'])->name('e.documents.signed-pdf');
    Route::delete('/e/documents/{id}/signed-pdf', [AdminDocumentController::class, 'destroySignedPdf'])->name('e.documents.signed-pdf.destroy');

    // Duplicate Client Scanner (temp)
    Route::get('/e/duplicates', [DuplicateClientController::class, 'index'])->name('e.duplicates');
    Route::get('/e/duplicates/scan', [DuplicateClientController::class, 'scan'])->name('e.duplicates.scan');
    Route::post('/e/duplicates/merge', [DuplicateClientController::class, 'merge'])->name('e.duplicates.merge');
















});

require __DIR__.'/auth.php';











//associates
Route::get('/', [AssociateAuthController::class, 'index'])->name('ass.login');
Route::post('/a/login', [AssociateAuthController::class, 'login'])->name('ass');


//protected associate routes
Route::middleware(['auth.associate'])->group(function () {
    //dashboard
    Route::get('/a/dashboard', [AssociateDashboardController::class, 'index'])->name('ass.dash');


    //logout
    Route::post('/a/logout', [AssociateAuthController::class, 'logout'])->name('ass.logout');

    //attendance
    Route::get('/a/attendance', [AssociateAttendanceController::class, 'index'])->name('ass.attendance');
    Route::post('/a/attendance/store', [AssociateAttendanceController::class, 'storeOrUpdate'])->name('ass.attendance.store');

    //leave
    Route::get('/a/leave', [AssociateLeaveController::class, 'index'])->name('ass.leave');
    Route::post('/a/leave/store', [AssociateLeaveController::class, 'store'])->name('ass.leave.store');
    Route::post('/a/leave/cancel/{id}', [AssociateLeaveController::class, 'cancel'])->name('ass.leave.cancel');
    Route::post('/a/leave/cancel-bulk', [AssociateLeaveController::class, 'cancelBulk'])->name('ass.leave.cancel.bulk');


    //progress
    Route::get('/a/progress', [AssociateProgressController::class, 'index'])->name('ass.progress');

    //resources
    Route::get('/a/resources', [AssociateResourceController::class, 'index'])->name('ass.resources');
    Route::get('/a/resources/download/{id}', [AssociateResourceController::class, 'download'])->name('ass.resources.download');
    Route::post('/a/resources/upload', [AssociateResourceController::class, 'upload'])->name('ass.resources.upload');

    //get report
    Route::get('/a/report/{year}/{month}', [AssociateProgressController::class, 'downloadReport'])->name('ass.report');

    //get profile
    Route::get('/a/profile', [AssociateProfileController::class, 'index'])->name('ass.profile');

});
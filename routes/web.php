<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\AuthLoginController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfficeStaffController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SentDocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TrashController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//LOGIN
Route::redirect('/', 'login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/login', [AuthLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthLoginController::class, 'login'])->middleware('throttle:3,2');
Route::post('login-verified', [AuthLoginController::class, 'loginverified'])->name('login.verified');
Route::get('/logout', [AuthLoginController::class, 'logout'])->middleware('auth')->name('logout');

// Route::get('/test', function(){
//     return 'SAMPLE';
// })->middleware('role:1');

// Email verification routes
Route::prefix('email')->group(function () {
    Route::get('/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.send');
    Route::get('/confirmed', [EmailVerificationController::class, 'verified'])->name('email.confirmed');
});

// Static pages for terms of service and privacy policy
Route::view('/terms-of-service', 'terms.show')->name('terms.show');
Route::view('/privacy-policy', 'policy.show')->name('policy.show');

//ROUTE FOR ADMIN
Route::middleware('role:1')->group(function () {
    Route::get('/home/admin', [AdminController::class, 'adminHome'])->name('home.admin');
    Route::prefix('admin')->group(function () {
        Route::post('/update-profile', [ProfileController::class, 'updateAdminProfile'])->name('profile.update');

        Route::prefix('documents')->group(function () {
            Route::get('/review_docs', [AdminController::class, 'review_docs'])->name('admin.documents.review_docs');
            Route::get('/approved_docs', [AdminController::class, 'approved_docs'])->name('admin.documents.approved_docs');
            Route::get('/declined_docs', [AdminController::class, 'declined_docs'])->name('admin.documents.declined_docs');
            Route::get('/memorandum', [AdminController::class, 'memorandum'])->name('admin.documents.memorandum');
            Route::get('/admin_order', [AdminController::class, 'admin_order'])->name('admin.documents.admin_order');
            Route::get('/mrsp', [AdminController::class, 'mrsp'])->name('admin.documents.mrsp');
            Route::get('/cms', [AdminController::class, 'cms'])->name('admin.documents.cms');
            Route::get('/audited_dv', [AdminController::class, 'audited_dv'])->name('admin.documents.audited_dv');
            Route::get('/request_docs', [AdminController::class, 'request_docs'])->name('admin.documents.request_docs');
            Route::post('/request_docs', [AdminController::class, 'declineRequest'])->name('admin.documents.request_docs');
            Route::get('/view_docs', [AdminController::class, 'view_docs'])->name('admin.documents.view_docs');
            Route::get('/all_docs', [AdminController::class, 'all_docs'])->name('admin.documents.all_docs');
            Route::post('/approve_docs/{id}', [DocumentController::class, 'approve'])->name('admin.documents.approve');
            Route::get('/approved_docs', [DocumentController::class, 'showApprovedDocuments'])->name('admin.documents.approved_docs');
            Route::post('/declined_docs/{id}', [DocumentController::class, 'decline'])->name('admin.documents.decline');
            Route::get('/view_docs/{document_id}', [AdminController::class, 'view'])->name('admin.documents.view_docs');
            Route::get('/edit_docs/{document_id}', [AdminController::class, 'edit'])->name('admin.documents.edit_docs');
            Route::put('/update/{document_id}', [AdminController::class, 'update'])->name('admin.documents.update');
            Route::get('/memorandum', [AdminController::class, 'showMemorandums'])->name('admin.documents.memorandum');
            Route::get('/mrsp', [AdminController::class, 'showMrsp'])->name('admin.documents.mrsp');
            Route::get('/cms', [AdminController::class, 'showCms'])->name('admin.documents.cms');
            Route::get('/audited_dv', [AdminController::class, 'showAuditedDV'])->name('admin.documents.audited_dv');
            Route::get('/sent_docs', [SentDocumentController::class, 'index'])
                ->name('admin.documents.sent_docs')
                ->defaults('viewName', 'admin.documents.sent_docs');
        });
        Route::get('/admin_dashboard', [AdminController::class, 'dashboard'])->name('admin.admin_dashboard');
        Route::get('/admin_account', [AdminController::class, 'admin_account'])->name('admin.admin_account');
        Route::get('/admin_upload_document', [AdminController::class, 'admin_upload_document'])->name('admin.admin_upload_document');
        Route::get('/admin_view_document', [AdminController::class, 'view_document'])->name('admin.admin_view_document');
        Route::get('/college_dean', [AdminController::class, 'college_dean'])->name('admin.college_dean');
        Route::get('/office_staff', [AdminController::class, 'office_staff'])->name('admin.office_staff');
        Route::get('/admin_notification', [AdminController::class, 'notification'])->name('admin.admin_notification');
        Route::get('/college_dean', [DeanController::class, 'deanList'])->name('admin.college_dean');
        Route::get('/college_dean', [CollegeController::class, 'showCollegeDeanForm'])->name('admin.college_dean');
        Route::get('/admin_upload_document', [DocumentController::class, 'create_admin'])->name('admin.admin_upload_document');
        Route::post('/admin_upload_document', [DocumentController::class, 'store']);
        Route::get('/admin_dashboard', [AdminController::class, 'adminDashboard'])->name('admin.admin_dashboard');
        Route::get('/admin_dashboard', [AdminController::class, 'category_count'])->name('admin.admin_dashboard');
        Route::get('/admin_dashboard', [AdminController::class, 'display_uploaded_docs'])->name('admin.admin_dashboard');
        Route::get('/office_staff', [AdminController::class, 'showOfficeStaff'])->name('admin.office_staff');
        Route::get('/admin_search', [AdminController::class, 'searchDocuments'])->name('admin.admin_search');
        Route::get('/admin_notifications', [NotificationController::class, 'index'])
            ->name('admin.admin_notification')
            ->middleware('auth')
            ->defaults('viewName', 'admin.admin_notification');
        Route::get('/viewRequested', [DocumentController::class, 'viewRequest'])->name('viewRequest');
        Route::get('/archive_document/{id}', [AdminController::class, 'archiveDocument'])->name('admin.archive_docs');
        Route::get('/archive_notif', [AdminController::class, 'archiveNotif'])->name('admin.archive_notif');
        Route::get('/archive_docs', [AdminController::class, 'archiveDocs'])->name('admin.archive_docs');
        Route::get('/trash', [AdminController::class, 'trash'])->name('admin.trash');
        
    });
});

//ROUTE FOR STAFF
Route::middleware(['auth', 'role:2'])->group(function () {

    Route::get('/home/office_staff', [OfficeStaffController::class, 'showHomePage'])->name('home.office_staff');
    Route::prefix('office_staff')->group(function () {
        Route::post('/update-profile', [ProfileController::class, 'updateOfficeStaffProfile'])->name('profile.office.update');
        Route::get('/os_dashboard', [OfficeStaffController::class, 'dashboard'])->name('office_staff.os_dashboard');
        Route::get('/os_account', [OfficeStaffController::class, 'os_account'])->name('office_staff.os_account');
        Route::get('/os_upload_document', [OfficeStaffController::class, 'os_upload_document'])->name('office_staff.os_upload_document');
        Route::get('/os_notification', [OfficeStaffController::class, 'os_notification'])->name('office_staff.os_notification');
        Route::get('/os_upload_document', [DocumentController::class, 'create'])->name('office_staff.os_upload_document');
        Route::post('/os_upload_document', [DocumentController::class, 'store']);
        Route::get('/os_dashboard', [OfficeStaffController::class, 'display_uploaded_docs'])->name('office_staff.os_dashboard');
        Route::get('/os_dashboard', [OfficeStaffController::class, 'category_count'])->name('office_staff.os_dashboard');
        Route::prefix('documents')->group(function () {
            Route::get('/memorandum', [OfficeStaffController::class, 'memorandum'])->name('office_staff.documents.memorandum');
            Route::get('/os_view_docs', [OfficeStaffController::class, 'os_view_docs'])->name('office_staff.documents.os_view_docs');
            Route::get('/edit_docs', [OfficeStaffController::class, 'edit_docs'])->name('office_staff.documents.edit_docs');
            Route::get('/os_search', [OfficeStaffController::class, 'showApprovedDocuments'])->name('office_staff.documents.os_search');
            Route::get('/os_view_docs/{document_id}', [OfficeStaffController::class, 'view'])->name('office_staff.documents.os_view_docs');
            Route::get('/edit_docs/{document_id}', [OfficeStaffController::class, 'edit'])->name('office_staff.documents.edit_docs');
            Route::put('/update/{document_id}', [OfficeStaffController::class, 'update'])->name('office_staff.documents.update');
            Route::get('/os_search', [OfficeStaffController::class, 'showAllDocs'])->name('office_staff.documents.os_search');
            Route::get('/memorandum', [OfficeStaffController::class, 'showMemorandums'])->name('office_staff.documents.memorandum');
            Route::get('/mrsp', [OfficeStaffController::class, 'showMrsp'])->name('office_staff.documents.mrsp');
            Route::get('/cms', [OfficeStaffController::class, 'showCms'])->name('office_staff.documents.cms');
            Route::get('/audtied_dv', [OfficeStaffController::class, 'showAuditedDV'])->name('office_staff.documents.audited_dv');
            Route::get('/os_search', [OfficeStaffController::class, 'searchDocuments'])->name('office_staff.documents.os_search');
            Route::get('/sent_docs', [SentDocumentController::class, 'index'])
                ->name('office_staff.documents.sent_docs')
                ->defaults('viewName', 'office_staff.documents.sent_docs');
            Route::get('/os_notification', [NotificationController::class, 'index'])
                ->name('office_staff.os_notification')
                ->middleware('auth')
                ->defaults('viewName', 'office_staff.os_notification');
        });
        Route::get('/os_archive', [OfficeStaffController::class, 'archiveDocs'])->name('office_staff.os_archive');
        Route::get('/os_trash', [OfficeStaffController::class, 'trash'])->name('office_staff.os_trash');
    });
});

//ROUTE FOR DEAN
Route::middleware(['auth', 'role:3'])->group(function () {

    Route::get('/home/dean', [DeanController::class, 'showDeanHome'])->name('home.dean');
    Route::prefix('dean')->group(function () {
        Route::post('/update-profile', [ProfileController::class, 'updateDeanProfile'])->name('profile.office.update');
        Route::get('/dean_dashboard', [DeanController::class, 'dashboard'])->name('dean.dean_dashboard');
        Route::get('/dean_account', [ProfileController::class, 'dean_account'])->name('dean.dean_account');
        Route::get('/dean_upload_document', [DeanController::class, 'upload_document'])->name('dean.dean_upload_document');
       
        Route::prefix('documents')->group(function () {
            Route::get('/dean_edit_docs', [DeanController::class, 'edit_docs'])->name('dean.documents.dean_edit_docs');
            Route::get('/dean_notification', [DeanController::class, 'notification'])->name('dean.documents.dean_notification');
            Route::get('/dean_request', [DeanController::class, 'request'])->name('dean.documents.dean_request');
            Route::get('/dean_requested_docs', [DeanController::class, 'requestedDocument'])
                ->name('dean.documents.dean_requested_docs')
                ->defaults('viewName', 'dean.documents.dean_requested_docs');
            Route::get('/dean_search', [DeanController::class, 'search'])->name('dean.documents.dean_search');
            Route::get('/dean_view_docs', [DeanController::class, 'view_docs'])->name('dean.documents.dean_view_docs');
            Route::get('/view_docs/{document_id}', [DeanController::class, 'view'])->name('dean.documents.dean_view_docs');
            Route::get('/memorandum', [DeanController::class, 'memorandum'])->name('dean.documents.memorandum');
            Route::get('/memorandum', [DeanController::class, 'showMemorandums'])->name('dean.documents.memorandum');
            Route::get('/admin_order', [DeanController::class, 'admin_order'])->name('dean.documents.admin_order');
            Route::get('/mrsp', [DeanController::class, 'mrsp'])->name('dean.documents.mrsp');
            Route::get('/cms', [DeanController::class, 'cms'])->name('dean.documents.cms');
            Route::get('/audited_dv', [DeanController::class, 'audited_dv'])->name('dean.documents.audited_dv');
            Route::get('/dean_search', [DeanController::class, 'showApprovedDocuments'])->name('dean.documents.dean_search');
            Route::get('/dean_notification', [NotificationController::class, 'index'])
                ->name('dean.documents.dean_notification')
                ->middleware('auth')
                ->defaults('viewName', 'dean.documents.dean_notification');
        });
        Route::get('/dean_archive', [DeanController::class, 'archiveDocs'])->name('dean.dean_archive');
        Route::get('/dean_trash', [DeanController::class, 'trash'])->name('dean.dean_trash');
    });
});


Route::middleware(['auth'])->group(function () {
    Route::prefix('documents')->group(function () {
        Route::get('/{filename}', function ($filename) {
            $path = public_path('storage/documents/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            return response()->file($path);
        })->name('document.serve');
        Route::post('/forward', [AdminController::class, 'forwardDocument']);
        Route::post('/forward', [OfficeStaffController::class, 'forwardDocument']);
        Route::post('/forward', [DocumentController::class, 'forwardDocument'])->name('documents.forward');
        Route::get('/view/{id}', [DocumentController::class, 'show'])->name('documents.show');
    });


    Route::prefix('search-documents')->group(function () {
        Route::get('/', [DocumentController::class, 'searchDocuments'])->name('search.documents');
        Route::get('/dean', [DocumentController::class, 'searchDocumentsdean'])->name('search.documentsDean');
    });

    Route::get('/profile', [OfficeStaffController::class, 'showProfile'])->middleware('auth')->name('office_staff.os_account');
    Route::get('/profile', [AdminController::class, 'showProfile'])->middleware('auth')->name('admin.admin_account');
    Route::get('/layouts/admin_layout', [AdminController::class, 'showPendings'])->name('layouts.admin_layout');
    Route::post('/add-dean-account', [DeanController::class, 'storeDeanAccount'])->name('dean.store');
    Route::post('/add-college', [CollegeController::class, 'store'])->name('add-college');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/recent-documents', [DocumentController::class, 'showRecentDocuments'])->name('recent-documents');
    Route::get('/logout', [AuthLoginController::class, 'logout'])->name('logout');
    Route::get('/get-document-details/{id}', [DocumentController::class, 'getDocumentDetails']);
    Route::patch('/forwarded-documents/{forwardedDocumentId}/update-status', [DocumentController::class, 'updateStatus'])->name('forwardedDocuments.updateStatus');
    Route::patch('/sent-documents/{forwardedDocumentId}/update-status', [DocumentController::class, 'updateStatusSent'])->name('forwardedDocuments.updateStatussent');
    Route::get('/notification/count', [NotificationController::class, 'getNotificationCount'])->name('notification.count');
    Route::post('/dean_request', [RequestController::class, 'index'])->name('dean.request');
    Route::get('/verification', function (Request $req) {
        $userID = $req->query('userID');
        $user = User::where('employee_id', $userID)->first();
        $user->sendEmailVerificationNotification();
        return response()->json(['success' => true, 'id' => $userID]);
    })->name('verification.notices');
    Route::post('/sent/upload', [SentDocumentController::class, 'sentUpload'])->name('admin.admin_send_document');
    Route::get('/deleteNotif/{id}/{status}', [NotificationController::class, 'destroy'])->name('deleteNotif');
    Route::get('/deleteNotifsent/{id}/{status}', [NotificationController::class, 'destroysent'])->name('deleteNotifsent');
    Route::get('/trash/{id}', [TrashController::class, 'deleteNotifForever'])->name('deleteNotifForever');
    Route::get('/trash/sent/{id}', [TrashController::class, 'deleteNotifForeversent'])->name('deleteNotifForeversent');
    Route::get('/restore/{id}', [AdminController::class, 'restoreDocs'])->name('restoreDocs');
    Route::get('/employees/exclude-current', [AdminController::class, 'getEmployee']);
    Route::get('/employees/exclude-current', [OfficeStaffController::class, 'getEmployee']);

});


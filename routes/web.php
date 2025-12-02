<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BahamasController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\pages\UserProfile;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ModeOfDeliveryController;
use App\Http\Controllers\ProjectPriorityController;
use App\Http\Controllers\ProjectStatusController;
use App\Http\Controllers\ProjectDeliveryFrequencyController;
use App\Http\Controllers\InputOutputFormatController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IndustryVerticalController;
use App\Http\Controllers\ServiceOfferingController;
use App\Http\Controllers\UnitOfMeasurementController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DescriptionController;
use App\Http\Controllers\PricingMasterController;
use App\Http\Controllers\SkillMasterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MainTaskController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\TaskTrackerController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\PoNumberController;
use App\Http\Controllers\IntakeStatusController;
use App\Http\Controllers\IntakeQueryTypeController;
use App\Http\Controllers\IntakeLeaseTypeController;
use App\Http\Controllers\IntakeWorkTypeController;
use App\Http\Controllers\IntakeLanguageController;
use App\Http\Controllers\IntakeQueryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceFormatController;
use App\Http\Controllers\QueryStatusController;
use App\Http\Controllers\FeedbackCategoryController;
use App\Http\Controllers\ProjectCategoryController;
Route::fallback(function () {
    return redirect()->route('login');
});

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('optimize:clear');
    echo "Cache cleared successfully";
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login-post', [LoginController::class, 'store'])->name('auth-login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('forgot-password-cover');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'index'])->name('reset-password-cover.get');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('reset-password-cover');
Route::get('/reset-password-check', [ResetPasswordController::class, 'resetPasswordCheck'])->name('reset-password-check');
Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('auth-forgot-password-cover');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('projects/fileshow', [ProjectController::class, 'files_code'])
    ->name('projects.fileshow');
    Route::get('projects/{parent}/files', [ProjectController::class, 'fileView'])
    ->name('projects.fileView');
    Route::post('/project/file', [ProjectController::class, 'store2'])->name('project.file.store');
    Route::put('/project/file',  [ProjectController::class, 'store2'])->name('project.file.update');
    // Roles & Permission
    Route::resource('roles', RoleController::class);
    Route::resource('project-types', ProjectTypeController::class);
    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::resource('permissions', PermissionController::class);
    Route::resource('mode-of-delivery', ModeOfDeliveryController::class);
    Route::resource('project-priorities', ProjectPriorityController::class);
    Route::resource('project-statuses', ProjectStatusController::class);
    Route::resource('project-delivery-frequencies', ProjectDeliveryFrequencyController::class);
    Route::resource('input-output-formats', InputOutputFormatController::class);
    Route::resource('projects',ProjectController::class);
    Route::resource('industry-verticals', IndustryVerticalController::class);
    Route::resource('service-offerings', ServiceOfferingController::class)->except(['show']);
    Route::resource('unit-of-measurements',UnitOfMeasurementController::class)->except(['show']);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('descriptions', DescriptionController::class);
    Route::resource('pricing-master', PricingMasterController::class);
    Route::post('pricing-master/{id}/submit',  [PricingMasterController::class, 'submit'])->name('pricing-master.submit');
    Route::post('pricing-master/{id}/approve', [PricingMasterController::class, 'approve'])->name('pricing-master.approve');
    Route::post('pricing-master/{id}/reject',  [PricingMasterController::class, 'reject'])->name('pricing-master.reject');
    Route::post('pricing-master/{id}/send-for-approval', [PricingMasterController::class, 'sendForApproval'])->name('pricing-master.send-for-approval');
    Route::post('pricing-master/{id}/need-modification', [PricingMasterController::class, 'needModification'])->name('pricing-master.need-modification');
    Route::resource('skill-masters', SkillMasterController::class);
    Route::resource('customers',CompanyController::class)->names('companies');
    Route::delete('/companies/{encryptedId}', [CompanyController::class, 'destroy'])
    ->name('companies.destroy');
    Route::resource('document',DocumentController::class);
    Route::get('/customers/{customer}/pocs', [CustomerController::class, 'pocs'])
     ->name('customers.pocs');
    Route::get('/pricing/by-department/{department}', [PricingMasterController::class, 'byDepartment'])
    ->name('pricing.byDepartment');
    Route::get('/users/{id}/team-members', [UserController::class, 'teamMembers'])
    ->name('users.team-members');
    Route::get('users/unassigned', [UserController::class, 'unassigned'])
    ->name('users.unassigned');
    Route::get('projects/{parent}/subprojects', [ProjectController::class, 'subprojects'])
    ->name('projects.subprojects');


    // Users Module
    Route::resource('users', UserController::class);

    // Customers Module
   // Route::resource('customers', CustomerController::class);
    //Bank Module
    Route::resource('banks',BankController::class);
    // User Profile
    Route::get('/pages/profile-user', [UserProfile::class, 'index'])->name('pages-profile-user');
    Route::get('/pages/users', [UserProfile::class, 'usersList'])->name('pages-users');
    Route::get('/pages/update-password', [UserProfile::class, 'updatePassword'])->name('update-password');
    Route::post('/pages/password-update', [UserProfile::class, 'passwordUpdate'])->name('password-update');
    Route::post('/pages/profile-user-update', [UserProfile::class, 'profileUserUpdate'])->name('profile-user-update');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices/preview-data', [InvoiceController::class, 'previewData'])->name('invoices.previewData');
    Route::post('/invoices/preview-html', [InvoiceController::class, 'previewHtml'])->name('invoices.previewHtml');
    Route::post('/invoices/preview-html-category3', [InvoiceController::class, 'previewHtmlCategory3'])->name('invoices.previewHtmlCategory3');
    Route::post('/invoices/approve', [InvoiceController::class, 'approve'])->name('invoices.approve');
    Route::post('/invoices/reject', [InvoiceController::class, 'reject'])->name('invoices.reject');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/projects/pm-members', [ProjectController::class, 'pmMembers'])->name('projects.pmMembers');
    Route::get('/projects/{encryptedId}/general-view', [ProjectController::class, 'generalView'])
        ->name('projects.generalView');
    Route::post('/projects/{encryptedId}/assignment-dates', [ProjectController::class, 'storeAssignmentDates'])
     ->name('projects.storeAssignmentDates');
    Route::get('tasks/main', [MainTaskController::class, 'index'])->name('maintasks.index');
    Route::get('tasks/main/create', [MainTaskController::class, 'create'])->name('maintasks.create');
    Route::post('tasks/main', [MainTaskController::class, 'store'])->name('maintasks.store');
    Route::get('tasks/main/{id}/edit', [MainTaskController::class, 'edit'])->name('maintasks.edit');
    Route::put('tasks/main/{id}', [MainTaskController::class, 'update'])->name('maintasks.update');
    Route::delete('tasks/main/{id}', [MainTaskController::class, 'destroy'])->name('maintasks.destroy');

    Route::get('tasks/sub', [SubTaskController::class, 'index'])->name('subtasks.index');
    Route::get('tasks/sub/create', [SubTaskController::class, 'create'])->name('subtasks.create');
    Route::post('tasks/sub', [SubTaskController::class, 'store'])->name('subtasks.store');
    Route::get('tasks/sub/{id}/edit', [SubTaskController::class, 'edit'])->name('subtasks.edit');
    Route::put('tasks/sub/{id}', [SubTaskController::class, 'update'])->name('subtasks.update');
    Route::delete('tasks/sub/{id}', [SubTaskController::class, 'destroy'])->name('subtasks.destroy');
     // Screen
    Route::get('projects/{id}/tasks/track', [TaskTrackerController::class, 'generalView'])
        ->name('projects.tasks.track'); // {id} is encrypted

    // AJAX helpers
    Route::get('subtasks/by-main/{mainTask}', [TaskTrackerController::class, 'subtasksByMain'])
        ->name('subtasks.byMain');

    Route::get('projects/{id}/tasks/track', [TaskTrackerController::class, 'generalView'])->name('projects.tasks.track');
    Route::get('subtasks/by-main/{mainTask}', [TaskTrackerController::class, 'subtasksByMain'])->name('subtasks.byMain');

    Route::post('taskitems/start',  [TaskTrackerController::class, 'start'])->name('taskitems.start');
    Route::post('taskitems/pause',  [TaskTrackerController::class, 'pause'])->name('taskitems.pause');
    Route::post('taskitems/resume', [TaskTrackerController::class, 'resume'])->name('taskitems.resume');
    Route::post('taskitems/end',    [TaskTrackerController::class, 'end'])->name('taskitems.end');
    Route::get('projects/{id}/admin-tracking', [TaskTrackerController::class, 'adminView'])->name('projects.admin.tracking');
    Route::post('taskitems/count', [TaskTrackerController::class, 'count'])->name('taskitems.count');

    Route::get('/projects/{encryptedId}/conversation', [CollaborationController::class, 'showProjectConversation'])->name('collab.project.conversation');
    Route::get('/conversations/{id}/messages', [CollaborationController::class, 'listMessages'])->name('collab.messages.index');
    Route::post('/conversations/{id}/messages', [CollaborationController::class, 'sendMessage'])->name('collab.messages.store');
    Route::post('/conversations/{id}/read', [CollaborationController::class, 'markRead'])->name('collab.messages.read');
    Route::get('/collab', [CollaborationController::class, 'inbox'])->name('collab.inbox');
    Route::get('/conversations/{conversation}', [CollaborationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/read-all', [CollaborationController::class, 'markAllRead'])->name('conversations.readAll');
    Route::resource('po-numbers', PoNumberController::class);
    Route::get('/projects/{project}/sub-projects', [PoNumberController::class, 'subProjects'])
        ->name('projects.sub-projects');
    Route::get('/po-numbers/customer/{customerId}/projects', [PoNumberController::class, 'projectsByCustomer'])->name('po-numbers.projects-by-customer');
    Route::resource('intake-statuses',IntakeStatusController::class)->parameters(['intake-statuses' => 'intake_status']);
    Route::resource('intake-query-types', IntakeQueryTypeController::class);
    Route::resource('intake-lease-types', IntakeLeaseTypeController::class);
    Route::resource('intake-work-types',  IntakeWorkTypeController::class);
    Route::resource('intake-languages',   IntakeLanguageController::class);
    Route::post('/invoices/approve', [InvoiceController::class, 'approve'])->name('invoices.approve');
    Route::get('invoices-list', [InvoiceController::class, 'invoiceDatatable'])->name('invoices.list');
    Route::post('/invoices/{invoice}/finance-decision', [InvoiceController::class, 'financeDecision'])->name('invoices.financeDecision');
   // Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::get('/projects/import/template', [ProjectController::class, 'template'])
    ->name('projects.import.template');
    Route::post('/projects/import/{project?}', [ProjectController::class, 'import'])
    ->name('projects.import');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'viewPdf'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/email', [InvoiceController::class, 'emailToClient'])
    ->name('invoices.email');
    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.markPaid');
    Route::post('/invoices/{encryptedId}/complete-payment', [InvoiceController::class, 'completePayment'])->name('invoices.completePayment');
    Route::get('/invoices/table', [InvoiceController::class, 'tablePage'])->name('invoices.table');
    Route::get('/submited-invoices', [InvoiceController::class, 'generalInvoice'])->name('invoices.general');

    Route::get('/bahamas-dashborad', [BahamasController::class, 'index'])->name('bahamas');
    Route::get('/dashboard/company', [DashboardController::class, 'companyByYear'])->name('dashboard.company');
    Route::get('/dashboard/filetype', [DashboardController::class, 'fileTypeByYear'])->name('dashboard.filetype');
    Route::get('/dashboard/sla', [DashboardController::class, 'slaByYear'])->name('dashboard.sla');
    Route::get('dashboard/monthly-delivered', [DashboardController::class, 'monthlyDelivered'])
    ->name('dashboard.monthly_delivered');

    Route::get('/intake-queries',        [IntakeQueryController::class, 'index'])->name('intake-queries.index');
    Route::post('/intake-queries',       [IntakeQueryController::class, 'store'])->name('intake-queries.store');   // <-- fixes "Route not defined"
    Route::post('/intake-queries/bulk',  [IntakeQueryController::class, 'bulk'])->name('intake-queries.bulk');
    Route::delete('/intake-queries/{intakeQuery}', [IntakeQueryController::class, 'destroy'])->name('intake-queries.destroy');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{encryptedId}/task-tracker/today',[TaskTrackerController::class, 'todayFragment'])->name('taskitems.today');
    Route::get('invoice-formats',            [InvoiceFormatController::class, 'index'])->name('invoice-formats.index');
    Route::get('invoice-formats/create',     [InvoiceFormatController::class, 'create'])->name('invoice-formats.create');
    Route::post('invoice-formats',           [InvoiceFormatController::class, 'store'])->name('invoice-formats.store');
    Route::get('invoice-formats/{id}/edit',  [InvoiceFormatController::class, 'edit'])->name('invoice-formats.edit');
    Route::put('invoice-formats/{id}',       [InvoiceFormatController::class, 'update'])->name('invoice-formats.update');
    Route::delete('invoice-formats/delete',  [InvoiceFormatController::class, 'destroy'])->name('invoice-formats.destroy');
    Route::resource('query-statuses', QueryStatusController::class)->except(['show']);
    Route::get('companies/{encrypted}/projects', [CompanyController::class, 'projects'])->name('companies.projects');
    Route::resource('project-categories', ProjectCategoryController::class)->except(['show']);
    Route::get('/departments/by-industry/{industry}', [DepartmentController::class, 'byIndustry'])
    ->name('departments.byIndustry');

    Route::get('/service-offerings/by-department/{department}', [ServiceOfferingController::class, 'byDepartment'])
    ->name('serviceOfferings.byDepartment');
    Route::prefix('feedback-categories')->name('feedback-categories.')->group(function () {
        Route::get('/',            [FeedbackCategoryController::class, 'index'])->name('index');
        Route::get('/create',      [FeedbackCategoryController::class, 'create'])->name('create');
        Route::post('/',           [FeedbackCategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit',   [FeedbackCategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [FeedbackCategoryController::class, 'update'])->name('update');
        Route::delete('/',         [FeedbackCategoryController::class, 'destroy'])->name('destroy');
    });
    Route::get('/invoices/preview/{id}', [InvoiceController::class,'previewPdf'])
    ->name('invoices.preview');
    // routes/web.php
Route::get('/projects/{encryptedId}/tracking/export', [TaskTrackerController::class, 'adminExport'])
    ->name('projects.admin.tracking.export');

    // Document file download routes
    Route::get('/documents/{encryptedId}/file', [DocumentController::class, 'downloadFile'])
        ->name('documents.file');
    Route::get('/documents/{encryptedDocumentId}/alerts/{encryptedAlertId}/file', [DocumentController::class, 'downloadAlertFile'])
        ->name('documents.alert.file');

});


<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContactTagController;
use App\Http\Controllers\Api\ContactPositionController;
use App\Http\Controllers\Api\ProjectStatusController;
use App\Http\Controllers\Api\ProjectTypeController;
use App\Http\Controllers\Api\ProjectResultController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\UsedFormController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\AlarmController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->name('api.')->group(function() {
    Broadcast::routes();

    Route::post('/checkUserStatus', [UserController::class, 'checkUserStatus'])->name('checkUserStatus');

    Route::prefix('/contacts')->name('contacts.')->group(function () {
        Route::post('/getContactsList',    [ContactController::class, 'getContactsList'])->name('getContactsList');
        Route::post('/getContactsExtData', [ContactController::class, 'getContactsExtData'])->name('getContactsExtData');
        Route::post('/getSingleContact',   [ContactController::class, 'getSingleContact'])->name('getSingleContact');
        Route::post('/saveContact',        [ContactController::class, 'saveContact'])->name('saveContact');
        Route::post('/deleteContact',      [ContactController::class, 'deleteContact'])->name('deleteContact');
        Route::post('/setContactTag',      [ContactController::class, 'setContactTag'])->name('setContactTag');
    });

    Route::prefix('/contact-tags')->name('contact-tags.')->group(function () {
        Route::post('/getContactTagsList',    [ContactTagController::class, 'getContactTagsList'])->name('getContactTagsList');
        Route::post('/saveContactTag',        [ContactTagController::class, 'saveContactTag'])->name('saveContactTag');
        Route::post('/deleteContactTag',      [ContactTagController::class, 'deleteContactTag'])->name('deleteContactTag');
    });

    Route::prefix('/contact-positions')->name('contact-positions.')->group(function () {
        Route::post('/getPositionsList',    [ContactPositionController::class, 'getPositionsList'])->name('getPositionsList');
        Route::post('/savePosition',        [ContactPositionController::class, 'savePosition'])->name('savePosition');
        Route::post('/deletePosition',      [ContactPositionController::class, 'deletePosition'])->name('deletePosition');
    });

    Route::prefix('/project-statuses')->name('project-statuses.')->group(function () {
        Route::post('/getProjectStatusList',      [ProjectStatusController::class, 'getProjectStatusList'])->name('getProjectStatusList');
        Route::post('/saveProjectStatus',         [ProjectStatusController::class, 'saveProjectStatus'])->name('saveProjectStatus');
        Route::post('/deleteProjectStatus',       [ProjectStatusController::class, 'deleteProjectStatus'])->name('deleteProjectStatus');
        Route::post('/setProjectStatusAsDefault', [ProjectStatusController::class, 'setProjectStatusAsDefault'])->name('setProjectStatusAsDefault');
    });

    Route::prefix('/project-types')->name('project-types.')->group(function () {
        Route::post('/getProjectTypeList',    [ProjectTypeController::class, 'getProjectTypeList'])->name('getProjectTypeList');
        Route::post('/saveProjectType',       [ProjectTypeController::class, 'saveProjectType'])->name('saveProjectType');
        Route::post('/deleteProjectType',     [ProjectTypeController::class, 'deleteProjectType'])->name('deleteProjectType');
    });

    Route::prefix('/project-results')->name('project-results.')->group(function () {
        Route::post('/getProjectResultList',      [ProjectResultController::class, 'getProjectResultList'])->name('getProjectResultList');
        Route::post('/saveProjectResult',         [ProjectResultController::class, 'saveProjectResult'])->name('saveProjectResult');
        Route::post('/deleteProjectResult',       [ProjectResultController::class, 'deleteProjectResult'])->name('deleteProjectResult');
        Route::post('/setProjectResultAsDefault', [ProjectResultController::class, 'setProjectResultAsDefault'])->name('setProjectResultAsDefault');
    });

    Route::prefix('/projects')->name('projects.')->group(function () {
        Route::post('/getProjectsList',      [ProjectController::class, 'getProjectsList'])->name('getProjectsList');
        Route::post('/getSingleProject',     [ProjectController::class, 'getSingleProject'])->name('getSingleProject');
        Route::post('/getProjectsExtData',   [ProjectController::class, 'getProjectsExtData'])->name('getProjectsExtData');
        Route::post('/saveProject',          [ProjectController::class, 'saveProject'])->name('saveProject');
        Route::post('/deleteProject',        [ProjectController::class, 'deleteProject'])->name('deleteProject');
        Route::post('/addProjectComment',    [ProjectController::class, 'addProjectComment'])->name('addProjectComment');
        Route::post('/addProjectNote',       [ProjectController::class, 'addProjectNote'])->name('addProjectNote');
        Route::post('/addProjectAttachment', [ProjectController::class, 'addProjectAttachment'])->name('addProjectAttachment');
    });

    Route::prefix('/images')->name('images.')->group(function () {
        Route::post('/renameAlt',    [ImageController::class, 'renameAlt'])->name('renameAlt');
        Route::post('/deleteImage',  [ImageController::class, 'deleteImage'])->name('deleteImage');
        Route::get('/downloadFile',  [ImageController::class, 'downloadFile'])->name('downloadFile');
    });

    Route::prefix('/todos')->name('todos.')->group(function () {
        Route::post('/getTodoList', [TodoController::class, 'getTodoList'])->name('getTodoList');
        Route::post('/saveTodo',    [TodoController::class, 'saveTodo'])->name('saveTodo');
        Route::post('/deleteTodo',  [TodoController::class, 'deleteTodo'])->name('deleteTodo');
    });

    Route::prefix('/alarms')->name('alarms.')->group(function () {
        Route::post('/getAlarmList',      [AlarmController::class, 'getAlarmList'])->name('getAlarmList');
        Route::post('/saveAlarm',         [AlarmController::class, 'saveAlarm'])->name('saveAlarm');
        Route::post('/deleteAlarm',       [AlarmController::class, 'deleteAlarm'])->name('deleteAlarm');
        Route::post('/changeAlarmStatus', [AlarmController::class, 'changeAlarmStatus'])->name('changeAlarmStatus');
    });

    Route::prefix('/forms')->name('forms.')->group(function () {
        Route::get('/get-options',      [FormController::class, 'formOptions'])->name('getOptions');
        Route::get('/list',             [FormController::class, 'formsList'])->name('list');
        Route::post('/changeStatus',    [FormController::class, 'changeStatus'])->name('changeStatus');
        Route::post('/saveForm',        [FormController::class, 'saveForm'])->name('save');
        Route::post('/usedFormCount',   [FormController::class, 'usedFormCount'])->name('usedFormCount');
        Route::post('/deleteForm',      [FormController::class, 'deleteForm'])->name('delete');
        Route::post('/deleteField',     [FormController::class, 'deleteField'])->name('deleteField');

        Route::post('/getFormInbox',        [UsedFormController::class, 'getFormInbox'])->name('getFormInbox');
        Route::post('/getFormInboxMore',    [UsedFormController::class, 'getFormInboxMore'])->name('getFormInboxMore');
        Route::post('/editFormStatus',      [UsedFormController::class, 'editFormStatus'])->name('editFormStatus');
    });

    Route::prefix('/permissions')->name('permissions.')->group(function () {
        Route::post('/getMainList',  [PermissionController::class, 'getMainList'])->name('getMainList');
        Route::post('/save',         [PermissionController::class, 'saveRole'])->name('save');

    });

    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::post('/list',    [ProductCategoryController::class, 'list'])->name('list');
        Route::post('/save',    [ProductCategoryController::class, 'saveCategory'])->name('save');
    });

    Route::prefix('/settings')->name('settings.')->group(function () {
        Route::post('/getSettingValues', [SettingController::class, 'getSettingValues'])->name('getValues');
        Route::post('/saveSettings',     [SettingController::class, 'saveSettings'])->name('setValues');
    });

    Route::prefix('/users')->name('users.')->group(function () {
        Route::post('/list', [UserController::class, 'list'])->name('list');
        Route::post('/save', [UserController::class, 'saveUser'])->name('save');
    });

    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::post('/view',    [UserController::class, 'getUserProfile'])->name('view');
        Route::post('/update',  [UserController::class, 'updateUserProfile'])->name('update');
    });
});

Route::name('api.')->group(function() {
    Route::prefix('/forms')->name('forms.')->group(function () {
        Route::post('/getFormDetails',     [FormController::class, 'getFormDetails'])->name('getFormDetails');
        Route::post('/saveFormData',       [UsedFormController::class, 'saveFormData'])->name('saveFormData');
        Route::post('/trackForm',          [UsedFormController::class, 'trackForm'])->name('trackForm');
    });

    Route::prefix('/invoices')->name('invoices.')->group(function () {
        Route::post('/verify',  [InvoiceController::class, 'verifyInvoice'])->name('verify');
    });
});

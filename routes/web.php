<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::group( ['middleware' => 'auth'], function()
{
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/employees', 'EmployeeController@view_employee');
    Route::get('/users', 'AccountController@view_users');
    Route::post('/change-password','AccountController@change_password');
    Route::post('/add-account','AccountController@new_account');
    Route::get('/reset-account/{id}','AccountController@reset_password');
    Route::post('edit-account/{id}','AccountController@edit_account');
    Route::get('/manage-account','ManageUserController@view_list_accounts');
    Route::get('manage-account-edit/{account_id}','ManageUserController@manage_account');
    Route::post('manage-account-edit/new-employee/{account_id}','ManageUserController@new_employee');
    Route::get('manage-account-edit/delete-employee/{id}','ManageUserController@remove_employee');
    Route::get('resigned-employee/{user_id}/{name}','AccountController@resigned_employee');
    Route::get('uploaded-letter','LetterController@view_letters');
    Route::post('proceed/{user_id}','LetterController@proceed');
    Route::post('declined/{user_id}','LetterController@declined');
    Route::get('resigned-employee','LetterController@view_resigned');
    Route::get('cancel-resignations','CancelResignationController@view_cancel_request');
    Route::post('proceed-to-clearance/{id}','LetterController@proceed_to_clearance');
    Route::get('/approve-cancel/{id}','CancelResignationController@approve_cancel');
    Route::get('/disapprove-cancel/{id}','CancelResignationController@disapprove_cancel');
    Route::get('/to-clearance/{upload_pdf_id}','ClearanceController@to_clearance');
    Route::post('/to-clearance/new-clearance/{upload_id}','ClearanceController@create_clearance');
    Route::get('/view-clearance/{upload_pdf_id}','ClearanceController@view_clearance');
    Route::get('/edit-clearance-status/{upload_pdf_id}','ClearanceController@edit_view_clearance');
    Route::get('print-clearance-status/{pdf_id}','ClearanceController@view_clearance_pdf');
    Route::get('print-clearance','ClearanceController@print_clearance');
    Route::get('for-clearance','ClearanceController@clearance_list');
    Route::get('cleared','ClearanceController@cleared');
    Route::post('verify','ClearanceController@verify_clearance');
    Route::get('surveys','SurveyController@view_survey');
    Route::get('edit-clearance-status/remove-signatory/{clerance_id}','ClearanceController@remove_signatory');
    Route::post('edit-clearance-status/new-signatory/{clerance_id}','ClearanceController@add_signatory');
    Route::post('edit-clearance-status/edit-clearance/{clerance_id}','ClearanceController@save_edit_info');
    Route::get('surveys-report','SurveyController@survey_report');
    Route::get('export-survey','SurveyController@export_survey');
    Route::get('clearance-report','ClearanceController@clearance_report');
    Route::get('get-approver','AccountController@getApprover');
    Route::post('upload-resignation','AccountController@resignEmployee');
    Route::get('disable-ad/{employeeId}','AccountController@disableAD');
    Route::get('enable-ad/{employeeId}','AccountController@enableAD');
    Route::get('/reset-password-active-directory/{employeeId}','AccountController@resetPassword');
    Route::get('get-all-approver/{user_id}','ManageUserController@getallapprover');
    Route::get('manual-email','LetterController@manual_email');
    Route::get('complete-clearance','ClearanceController@completedclearance');
    Route::get('get-all-expired','ClearanceController@expired_employee');
}
);


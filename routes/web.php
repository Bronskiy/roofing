<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
View::share('documentheaders', App\DocumentHeaders::all());

Route::get('/', function () {
  return view('welcome');
});
Route::post('/admin/mailbox/list', ['uses' => 'MailReceiverController@getEmailData', 'as' => 'mailbox.list']);
Route::any('/admin/mailbox/export', ['uses' => 'MailReceiverController@getEmailExport', 'as' => 'mailbox.export']);
Route::any('/admin/mailbox/file-export', ['uses' => 'MailReceiverController@getEmailFileExport', 'as' => 'mailbox.file-export']);
Route::any('/admin/mailbox/xls-export', ['uses' => 'MailReceiverController@getEmailXLSExport', 'as' => 'mailbox.xls-export']);

Route::any('/admin/inbox/fetch', ['uses' => 'MailReceiverController@fetchEmails', 'as' => 'inbox.fetch']);
Route::any('/admin/inbox/count', ['uses' => 'MailReceiverController@leadsCount', 'as' => 'inbox.count']);
// Test
Route::any('/admin/leads/test', ['uses' => 'MailReceiverController@leadsTest', 'as' => 'leads.test']);

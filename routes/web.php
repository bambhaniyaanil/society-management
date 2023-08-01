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
//     return view('dashboard');
// });

// Route::group(['prefix' => 'email'], function(){
//     Route::get('inbox', function () { return view('pages.email.inbox'); });
//     Route::get('read', function () { return view('pages.email.read'); });
//     Route::get('compose', function () { return view('pages.email.compose'); });
// });

// Route::group(['prefix' => 'apps'], function(){
//     Route::get('chat', function () { return view('pages.apps.chat'); });
//     Route::get('calendar', function () { return view('pages.apps.calendar'); });
// });

// Route::group(['prefix' => 'ui-components'], function(){
//     Route::get('alerts', function () { return view('pages.ui-components.alerts'); });
//     Route::get('badges', function () { return view('pages.ui-components.badges'); });
//     Route::get('breadcrumbs', function () { return view('pages.ui-components.breadcrumbs'); });
//     Route::get('buttons', function () { return view('pages.ui-components.buttons'); });
//     Route::get('button-group', function () { return view('pages.ui-components.button-group'); });
//     Route::get('cards', function () { return view('pages.ui-components.cards'); });
//     Route::get('carousel', function () { return view('pages.ui-components.carousel'); });
//     Route::get('collapse', function () { return view('pages.ui-components.collapse'); });
//     Route::get('dropdowns', function () { return view('pages.ui-components.dropdowns'); });
//     Route::get('list-group', function () { return view('pages.ui-components.list-group'); });
//     Route::get('media-object', function () { return view('pages.ui-components.media-object'); });
//     Route::get('modal', function () { return view('pages.ui-components.modal'); });
//     Route::get('navs', function () { return view('pages.ui-components.navs'); });
//     Route::get('navbar', function () { return view('pages.ui-components.navbar'); });
//     Route::get('pagination', function () { return view('pages.ui-components.pagination'); });
//     Route::get('popovers', function () { return view('pages.ui-components.popovers'); });
//     Route::get('progress', function () { return view('pages.ui-components.progress'); });
//     Route::get('scrollbar', function () { return view('pages.ui-components.scrollbar'); });
//     Route::get('scrollspy', function () { return view('pages.ui-components.scrollspy'); });
//     Route::get('spinners', function () { return view('pages.ui-components.spinners'); });
//     Route::get('tabs', function () { return view('pages.ui-components.tabs'); });
//     Route::get('tooltips', function () { return view('pages.ui-components.tooltips'); });
// });

// Route::group(['prefix' => 'advanced-ui'], function(){
//     Route::get('cropper', function () { return view('pages.advanced-ui.cropper'); });
//     Route::get('owl-carousel', function () { return view('pages.advanced-ui.owl-carousel'); });
//     Route::get('sweet-alert', function () { return view('pages.advanced-ui.sweet-alert'); });
// });

// Route::group(['prefix' => 'forms'], function(){
//     Route::get('basic-elements', function () { return view('pages.forms.basic-elements'); });
//     Route::get('advanced-elements', function () { return view('pages.forms.advanced-elements'); });
//     Route::get('editors', function () { return view('pages.forms.editors'); });
//     Route::get('wizard', function () { return view('pages.forms.wizard'); });
// });

// Route::group(['prefix' => 'charts'], function(){
//     Route::get('apex', function () { return view('pages.charts.apex'); });
//     Route::get('chartjs', function () { return view('pages.charts.chartjs'); });
//     Route::get('flot', function () { return view('pages.charts.flot'); });
//     Route::get('morrisjs', function () { return view('pages.charts.morrisjs'); });
//     Route::get('peity', function () { return view('pages.charts.peity'); });
//     Route::get('sparkline', function () { return view('pages.charts.sparkline'); });
// });

// Route::group(['prefix' => 'tables'], function(){
//     Route::get('basic-tables', function () { return view('pages.tables.basic-tables'); });
//     Route::get('data-table', function () { return view('pages.tables.data-table'); });
// });

// Route::group(['prefix' => 'icons'], function(){
//     Route::get('feather-icons', function () { return view('pages.icons.feather-icons'); });
//     Route::get('flag-icons', function () { return view('pages.icons.flag-icons'); });
//     Route::get('mdi-icons', function () { return view('pages.icons.mdi-icons'); });
// });

// Route::group(['prefix' => 'general'], function(){
//     Route::get('blank-page', function () { return view('pages.general.blank-page'); });
//     Route::get('faq', function () { return view('pages.general.faq'); });
//     Route::get('invoice', function () { return view('pages.general.invoice'); });
//     Route::get('profile', function () { return view('pages.general.profile'); });
//     Route::get('pricing', function () { return view('pages.general.pricing'); });
//     Route::get('timeline', function () { return view('pages.general.timeline'); });
// });

// Route::group(['prefix' => 'auth'], function(){
//     Route::get('login', function () { return view('pages.auth.login'); });
//     Route::get('register', function () { return view('pages.auth.register'); });
// });

// Route::group(['prefix' => 'error'], function(){
//     Route::get('404', function () { return view('pages.error.404'); });
//     Route::get('500', function () { return view('pages.error.500'); });
// });

// Route::get('/clear-cache', function() {
//     Artisan::call('cache:clear');
//     return "Cache is cleared";
// });

// 404 for undefined routes
// Route::any('/{page?}',function(){
//     return View::make('pages.error.404');
// })->where('page','.*');

Auth::routes();

Route::get('/expired', function () {
    return view('package-message');
})->name('expired');

Route::get('/permission', function () {
    return view('permission-message');
})->name('permission');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

Route::post('/post-login', [App\Http\Controllers\Auth\LoginController::class, 'checkLogin']);

Route::get('/dashboard',  [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/profile',  [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');

Route::get('/edit_profile',  [App\Http\Controllers\HomeController::class, 'editProfile'])->name('edit_profile');

Route::post('/update_profile',  [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('update_profile');
Route::get('/search-invoice', [App\Http\Controllers\InvoiceDetailsController::class, 'index']);
Route::post('/search-invoice', [App\Http\Controllers\InvoiceDetailsController::class, 'searchInvoice'])->name('search-invoice');
Route::get('/search-invoice/download/{id}',  [App\Http\Controllers\InvoiceDetailsController::class, 'download']);
Route::get('/search-invoice/create-zip/{ledger}/{society}', [App\Http\Controllers\InvoiceDetailsController::class, 'allInvoceDownload'])->name('search-invoice-create-zip');
Route::get('/search-invoice/send/{id}',  [App\Http\Controllers\InvoiceDetailsController::class, 'invoiceSend']);
Route::get('/search-invoice/view/{id}',  [App\Http\Controllers\InvoiceDetailsController::class, 'view']);

Route::get('/search-receipts', [App\Http\Controllers\ReceiptsDetailsController::class, 'index']);
Route::post('/search-receipts', [App\Http\Controllers\ReceiptsDetailsController::class, 'searchReceipts'])->name('search-receipts');
Route::get('/search-receipts/download/{id}',  [App\Http\Controllers\ReceiptsDetailsController::class, 'download']);
Route::get('/search-receipts/send/{id}',  [App\Http\Controllers\ReceiptsDetailsController::class, 'send']);
Route::get('/search-receipts/view/{id}',  [App\Http\Controllers\ReceiptsDetailsController::class, 'view']);
Route::get('/search-receipts/create-zip/{ledger}/{society}', [App\Http\Controllers\ReceiptsDetailsController::class, 'allReceiptsDownload'])->name('search-receipts-create-zip');


Route::group(['prefix' => 'society'], function () {
    Route::get('/',  [App\Http\Controllers\SocietyController::class, 'index'])->name('society');
    // Route::get('create',  [App\Http\Controllers\TreatmentsController::class, 'create']);
    // Route::post('add',  [App\Http\Controllers\TreatmentsController::class, 'add']);
    // Route::get('edit/{id}',  [App\Http\Controllers\TreatmentsController::class, 'edit']);
    // Route::post('update/{id}',  [App\Http\Controllers\TreatmentsController::class, 'update']);
    // Route::get('delete/{id}',  [App\Http\Controllers\TreatmentsController::class, 'delete']);
});

Route::group(['prefix' => 'flat'], function () {
    Route::get('/',  [App\Http\Controllers\FlatController::class, 'index'])->name('society');
    // Route::get('create',  [App\Http\Controllers\TreatmentsController::class, 'create']);
    // Route::post('add',  [App\Http\Controllers\TreatmentsController::class, 'add']);
    // Route::get('edit/{id}',  [App\Http\Controllers\TreatmentsController::class, 'edit']);
    // Route::post('update/{id}',  [App\Http\Controllers\TreatmentsController::class, 'update']);
    // Route::get('delete/{id}',  [App\Http\Controllers\TreatmentsController::class, 'delete']);
});

Route::group(['prefix' => 'ledger'], function () {
    Route::get('/',  [App\Http\Controllers\LedgerController::class, 'index'])->name('ledger');
    Route::get('create',  [App\Http\Controllers\LedgerController::class, 'create']);
    Route::post('add',  [App\Http\Controllers\LedgerController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\LedgerController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\LedgerController::class, 'update'])->name('ledger.update');
    Route::get('delete/{id}',  [App\Http\Controllers\LedgerController::class, 'delete']);
    Route::get('/export', [App\Http\Controllers\LedgerController::class, 'export'])->name('ledger.export');
    Route::post('/import', [App\Http\Controllers\LedgerController::class, 'import'])->name('ledger.import');
    Route::post('/deleteall', [App\Http\Controllers\LedgerController::class, 'deleteAll'])->name('ledger.remove');
    Route::get('/demo-file',  [App\Http\Controllers\LedgerController::class, 'demoFile'])->name('ledger.demo-file');
});

Route::group(['prefix' => 'payment-voucher'], function () {
    Route::get('/',  [App\Http\Controllers\PaymentVoucherController::class, 'index'])->name('payment-voucher');
    Route::get('create',  [App\Http\Controllers\PaymentVoucherController::class, 'create']);
    Route::post('add',  [App\Http\Controllers\PaymentVoucherController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\PaymentVoucherController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\PaymentVoucherController::class, 'update'])->name('payment-voucher.update');
    Route::get('delete/{id}',  [App\Http\Controllers\PaymentVoucherController::class, 'delete']);
    Route::post('/', [App\Http\Controllers\PaymentVoucherController::class, 'search'])->name('payment-voucher.search');
    Route::get('/export', [App\Http\Controllers\PaymentVoucherController::class, 'export'])->name('payment-voucher.export');
    Route::post('/import', [App\Http\Controllers\PaymentVoucherController::class, 'import'])->name('payment-voucher.import');
    Route::post('/deleteall', [App\Http\Controllers\PaymentVoucherController::class, 'deleteAll'])->name('payment-voucher.remove');
    Route::get('/demo-file',  [App\Http\Controllers\PaymentVoucherController::class, 'demoFile'])->name('payment-voucher.demo-file');
    Route::get('view/{id}',  [App\Http\Controllers\PaymentVoucherController::class, 'view']);
    Route::get('download/{id}',  [App\Http\Controllers\PaymentVoucherController::class, 'download']);
    Route::post('/payment-form-accept',  [App\Http\Controllers\PaymentVoucherController::class, 'paymentFormAccept'])->name('payment-voucher.payment-form-accept');
    Route::get('/edit-form/{id}',  [App\Http\Controllers\PaymentVoucherController::class, 'paymentFormEdit'])->name('payment-voucher.payment-form-edit');
    Route::post('update-form/{id}',  [App\Http\Controllers\PaymentVoucherController::class, 'updateForm'])->name('payment-voucher.update-form');
    Route::get('/delete-form/{id}',  [App\Http\Controllers\PaymentVoucherController::class, 'paymentFormDelete'])->name('payment-voucher.payment-form-delete');
});

Route::group(['prefix' => 'receipts-voucher'], function () {
    Route::get('/',  [App\Http\Controllers\ReceiptsVoucherController::class, 'index'])->name('receipts-voucher');
    Route::get('create',  [App\Http\Controllers\ReceiptsVoucherController::class, 'create']);
    Route::post('add',  [App\Http\Controllers\ReceiptsVoucherController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'update'])->name('receipts-voucher.update');
    Route::get('delete/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'delete']);
    Route::post('/', [App\Http\Controllers\ReceiptsVoucherController::class, 'search'])->name('receipts-voucher.search');
    Route::get('view/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'view']);
    Route::get('send/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'send']);
    Route::get('download/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'download']);
    Route::get('/export', [App\Http\Controllers\ReceiptsVoucherController::class, 'export'])->name('receipts-voucher.export');
    Route::post('/import', [App\Http\Controllers\ReceiptsVoucherController::class, 'import'])->name('receipts-voucher.import');
    Route::post('create-zip', [App\Http\Controllers\ReceiptsVoucherController::class, 'allReceiptsDownload'])->name('receipts-voucher.create-zip');
    Route::get('whatsapp/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'whatsapp'])->name('receipts-voucher.whatsapp');
    Route::post('/deleteall', [App\Http\Controllers\ReceiptsVoucherController::class, 'deleteAll'])->name('receipts-voucher.remove');
    Route::get('/demo-file',  [App\Http\Controllers\ReceiptsVoucherController::class, 'demoFile'])->name('receipts-voucher.demo-file');
    Route::post('/receipt-form-accept',  [App\Http\Controllers\ReceiptsVoucherController::class, 'receiptFormAccept'])->name('receipts-voucher.receipt-form-accept');
    Route::get('/edit-form/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'receiptFormEdit'])->name('receipts-voucher.receipt-form-edit');
    Route::post('update-form/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'updateForm'])->name('receipts-voucher.update-form');
    Route::get('/delete-form/{id}',  [App\Http\Controllers\ReceiptsVoucherController::class, 'receiptFormDelete'])->name('receipts-voucher.receipt-form-delete');
    Route::post('/send-mail', [App\Http\Controllers\ReceiptsVoucherController::class, 'bulkSendMail'])->name('receipts-voucher.bulk-send-mail');
});

Route::group(['prefix' => 'journal-voucher'], function () {
    Route::get('/',  [App\Http\Controllers\JournalVoucherController::class, 'index'])->name('journal-voucher');
    Route::get('create',  [App\Http\Controllers\JournalVoucherController::class, 'create']);
    Route::post('add',  [App\Http\Controllers\JournalVoucherController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\JournalVoucherController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\JournalVoucherController::class, 'update'])->name('journal-voucher.update');
    Route::get('delete/{id}',  [App\Http\Controllers\JournalVoucherController::class, 'delete']);
    Route::post('/',  [App\Http\Controllers\JournalVoucherController::class, 'search'])->name('journal-voucher.search');
    Route::get('/export', [App\Http\Controllers\JournalVoucherController::class, 'export'])->name('journal-voucher.export');
    Route::post('/import', [App\Http\Controllers\JournalVoucherController::class, 'import'])->name('journal-voucher.import');
    Route::post('/deleteall', [App\Http\Controllers\JournalVoucherController::class, 'deleteAll'])->name('journal-voucher.remove');
    Route::get('/demo-file',  [App\Http\Controllers\JournalVoucherController::class, 'demoFile'])->name('journal-voucher.demo-file');
});

Route::group(['prefix' => 'group-type'], function () {
    Route::get('/',  [App\Http\Controllers\GroupTypeController::class, 'index'])->name('group-type');
    Route::get('create',  [App\Http\Controllers\GroupTypeController::class, 'create'])->name('group-type.create');
    Route::post('add',  [App\Http\Controllers\GroupTypeController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\GroupTypeController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\GroupTypeController::class, 'update'])->name('group-type.update');
    Route::get('delete/{id}',  [App\Http\Controllers\GroupTypeController::class, 'delete']);
});

Route::group(['prefix' => 'group-category'], function () {
    Route::get('/',  [App\Http\Controllers\GroupCategoryController::class, 'index'])->name('group-category');
    Route::get('create',  [App\Http\Controllers\GroupCategoryController::class, 'create']);
    Route::post('add',  [App\Http\Controllers\GroupCategoryController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\GroupCategoryController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\GroupCategoryController::class, 'update'])->name('group-category.update');
    Route::get('delete/{id}',  [App\Http\Controllers\GroupCategoryController::class, 'delete']);
});

Route::group(['prefix' => 'group-creations'], function () {
    Route::get('/',  [App\Http\Controllers\GroupCreationsController::class, 'index'])->name('group-creations');
    Route::get('create',  [App\Http\Controllers\GroupCreationsController::class, 'create']);
    Route::post('add',  [App\Http\Controllers\GroupCreationsController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\GroupCreationsController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\GroupCreationsController::class, 'update'])->name('group-creations.update');
    Route::get('delete/{id}',  [App\Http\Controllers\GroupCreationsController::class, 'delete']);
});

// Route::group(['prefix' => 'user'], function(){
//     Route::get('/',  [App\Http\Controllers\UserController::class, 'index'])->name('user');
//     Route::get('create',  [App\Http\Controllers\UserController::class, 'create']);
//     Route::post('add',  [App\Http\Controllers\UserController::class, 'add']);
//     Route::get('edit/{id}',  [App\Http\Controllers\UserController::class, 'edit']);
//     Route::post('update/{id}',  [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
//     Route::get('delete/{id}',  [App\Http\Controllers\UserController::class, 'delete']);
// });

Route::group(['prefix' => 'users'], function () {
    Route::get('/',  [App\Http\Controllers\UsersController::class, 'index'])->name('users');
    Route::get('create',  [App\Http\Controllers\UsersController::class, 'create'])->name('users.create');
    Route::post('add',  [App\Http\Controllers\UsersController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\UsersController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\UsersController::class, 'update'])->name('users.update');
    Route::get('delete/{id}',  [App\Http\Controllers\UsersController::class, 'delete']);
});

Route::group(['prefix' => 'invoice'], function () {
    Route::get('/',  [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice');
    Route::get('create',  [App\Http\Controllers\InvoiceController::class, 'create']);
    Route::post('add',  [App\Http\Controllers\InvoiceController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\InvoiceController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\InvoiceController::class, 'update'])->name('invoice.update');
    Route::get('delete/{id}',  [App\Http\Controllers\InvoiceController::class, 'delete']);
    Route::get('view/{id}',  [App\Http\Controllers\InvoiceController::class, 'view']);
    Route::get('send/{id}',  [App\Http\Controllers\InvoiceController::class, 'send']);
    Route::get('download/{id}',  [App\Http\Controllers\InvoiceController::class, 'download']);
    Route::post('/', [App\Http\Controllers\InvoiceController::class, 'search'])->name('invoice.search');
    Route::get('/export', [App\Http\Controllers\InvoiceController::class, 'export'])->name('invoice.export');
    Route::post('/import', [App\Http\Controllers\InvoiceController::class, 'import'])->name('invoice.import');
    Route::post('create-zip', [App\Http\Controllers\InvoiceController::class, 'allInvoceDownload'])->name('create-zip');
    Route::get('whatsapp/{id}',  [App\Http\Controllers\InvoiceController::class, 'whatsapp']);
    Route::post('/deleteall', [App\Http\Controllers\InvoiceController::class, 'deleteAll'])->name('invoice.remove');
    Route::get('demo-file',  [App\Http\Controllers\InvoiceController::class, 'demoFile'])->name('invoice.demo-file');
    Route::post('/send-mail', [App\Http\Controllers\InvoiceController::class, 'bulkSendMail'])->name('invoice.bulk-send-mail');
});

Route::group(['prefix' => 'reports'], function () {
    Route::get('/ledger', [App\Http\Controllers\ReportsController::class, 'ledger'])->name('report.ledger');
    Route::post('/ledger',  [App\Http\Controllers\ReportsController::class, 'ledgerReport'])->name('reports.ledger-report');
    Route::post('submit-date',  [App\Http\Controllers\ReportsController::class, 'submitDate']);
    Route::get('reset', [App\Http\Controllers\ReportsController::class, 'reset']);
    Route::get('profit-loss', [App\Http\Controllers\ReportsController::class, 'profitLoss'])->name('report.profit-loss');
    Route::post('profit-loss',  [App\Http\Controllers\ReportsController::class, 'profitLossReport'])->name('reports.profit-loss-report');
    Route::get('closing-balance', [App\Http\Controllers\ReportsController::class, 'closingBalance'])->name('report.closing-balance');
    Route::post('closing-balance',  [App\Http\Controllers\ReportsController::class, 'closingBalanceReport'])->name('reports.closing-balance-report');
    Route::get('balance-sheet', [App\Http\Controllers\ReportsController::class, 'balanceSheet'])->name('report.balance-sheet');
    Route::post('balance-sheet',  [App\Http\Controllers\ReportsController::class, 'balanceSheetReport'])->name('reports.balance-sheet-report');
    Route::get('/ledger/export',  [App\Http\Controllers\ReportsController::class, 'ledgerExport'])->name('reports.ledger-report.export');
    Route::get('/ledger/export-all',  [App\Http\Controllers\ReportsController::class, 'ledgerExportAll'])->name('reports.ledger-report.export-all');
    Route::get('/profit-loss/export',  [App\Http\Controllers\ReportsController::class, 'profitlossExport'])->name('reports.profit-loss.export');
    Route::get('/balance-sheet/export',  [App\Http\Controllers\ReportsController::class, 'balancesheetExport'])->name('reports.balance-sheet.export');
    Route::get('/closing-balance/export',  [App\Http\Controllers\ReportsController::class, 'closingbalanceExport'])->name('reports.closing-balance.export');
});

Route::group(['prefix' => 'role-permission'], function () {
    Route::get('/',  [App\Http\Controllers\RolePermissionController::class, 'index'])->name('role-permission');
    Route::get('create',  [App\Http\Controllers\RolePermissionController::class, 'create'])->name('role-permission.create');
    Route::post('add',  [App\Http\Controllers\RolePermissionController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\RolePermissionController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\RolePermissionController::class, 'update'])->name('role-permission.update');
    Route::get('delete/{id}',  [App\Http\Controllers\RolePermissionController::class, 'delete']);
});

Route::group(['prefix' => 'mail-integrations'], function () {
    Route::get('/',  [App\Http\Controllers\MailIntegrationsController::class, 'index'])->name('mail-integrations');
    Route::get('create',  [App\Http\Controllers\MailIntegrationsController::class, 'create'])->name('mail-integrations.create');
    Route::post('add',  [App\Http\Controllers\MailIntegrationsController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\MailIntegrationsController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\MailIntegrationsController::class, 'update'])->name('mail-integrations.update');
    Route::get('delete/{id}',  [App\Http\Controllers\MailIntegrationsController::class, 'delete']);
    Route::get('delete/{id}/{status}/{socity_id}',  [App\Http\Controllers\MailIntegrationsController::class, 'status'])->name('mail-integrations.status');
});

Route::group(['prefix' => 'parking-owner'], function () {
    Route::get('/',  [App\Http\Controllers\ParkingOwnerDetailsController::class, 'index'])->name('parking-owner');
    Route::get('create',  [App\Http\Controllers\ParkingOwnerDetailsController::class, 'create'])->name('parking-owner.create');
    Route::post('add',  [App\Http\Controllers\ParkingOwnerDetailsController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\ParkingOwnerDetailsController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\ParkingOwnerDetailsController::class, 'update'])->name('parking-owner.update');
    Route::get('delete/{id}',  [App\Http\Controllers\ParkingOwnerDetailsController::class, 'delete']);
    Route::get('/export', [App\Http\Controllers\ParkingOwnerDetailsController::class, 'export'])->name('parking-owner.export');
    Route::post('/', [App\Http\Controllers\ParkingOwnerDetailsController::class, 'search'])->name('parking-owner.search');
    Route::get('reset', [App\Http\Controllers\ParkingOwnerDetailsController::class, 'reset']);
});

Route::group(['prefix' => 'parking-register'], function () {
    Route::get('/',  [App\Http\Controllers\ParkingRegisterController::class, 'index'])->name('parking-register');
    Route::get('create',  [App\Http\Controllers\ParkingRegisterController::class, 'create'])->name('parking-register.create');
    Route::post('add',  [App\Http\Controllers\ParkingRegisterController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\ParkingRegisterController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\ParkingRegisterController::class, 'update'])->name('parking-register.update');
    Route::get('delete/{id}',  [App\Http\Controllers\ParkingRegisterController::class, 'delete']);
    Route::get('/export', [App\Http\Controllers\ParkingRegisterController::class, 'export'])->name('parking-register.export');
    Route::post('/', [App\Http\Controllers\ParkingRegisterController::class, 'search'])->name('parking-register.search');
    Route::get('reset', [App\Http\Controllers\ParkingRegisterController::class, 'reset']);
});

Route::group(['prefix' => 'tenant-register'], function () {
    Route::get('/',  [App\Http\Controllers\TenantRegisterController::class, 'index'])->name('tenant-register');
    Route::get('create',  [App\Http\Controllers\TenantRegisterController::class, 'create'])->name('tenant-register.create');
    Route::post('add',  [App\Http\Controllers\TenantRegisterController::class, 'add']);
    Route::get('edit/{id}',  [App\Http\Controllers\TenantRegisterController::class, 'edit']);
    Route::post('update/{id}',  [App\Http\Controllers\TenantRegisterController::class, 'update'])->name('tenant-register.update');
    Route::get('delete/{id}',  [App\Http\Controllers\TenantRegisterController::class, 'delete']);
    Route::post('/', [App\Http\Controllers\TenantRegisterController::class, 'search'])->name('tenant-register.search');
    Route::get('/export', [App\Http\Controllers\TenantRegisterController::class, 'export'])->name('tenant-register.export');
    Route::get('reset', [App\Http\Controllers\TenantRegisterController::class, 'reset']);
});

Route::group(['prefix' => 'email-notification'], function () {
    Route::get('/',  [App\Http\Controllers\EmailNotificationController::class, 'index'])->name('email-notification');
    Route::post('send',  [App\Http\Controllers\EmailNotificationController::class, 'send'])->name('email-notification.send');
    // Route::post('add',  [App\Http\Controllers\RolePermissionController::class, 'add']);
    // Route::get('edit/{id}',  [App\Http\Controllers\RolePermissionController::class, 'edit']);
    // Route::post('update/{id}',  [App\Http\Controllers\RolePermissionController::class, 'update'])->name('role_permission.update');
    // Route::get('delete/{id}',  [App\Http\Controllers\RolePermissionController::class, 'delete']);
});
// Route::get('payment-voucher-export-csv', function () {
//     return Excel::download(new ExportPaymentVoucher, 'paymet-coucher.csv');
// });

Route::get('/receipt-form',  [App\Http\Controllers\ReceiptsFormController::class, 'receiptForm'])->name('receipt-form');
Route::get('/receipt-form-list',  [App\Http\Controllers\ReceiptsVoucherController::class, 'receiptForm'])->name('receipt-form-list');
Route::post('/receipts-form-save',  [App\Http\Controllers\ReceiptsFormController::class, 'receiptFormSave'])->name('receipts-form-save');
Route::get('/receipt-form/ledger/{id}',  [App\Http\Controllers\ReceiptsFormController::class, 'receiptFormLedger']);

Route::get('/payment-form',  [App\Http\Controllers\PaymentFormController::class, 'paymentForm'])->name('payment-form');
Route::post('/payment-form-save',  [App\Http\Controllers\PaymentFormController::class, 'paymentFormSave'])->name('payment-form-save');
Route::get('/payment-form-list',  [App\Http\Controllers\PaymentVoucherController::class, 'paymentForm'])->name('payment-form-list');

Route::get('/send-mail', [App\Http\Controllers\CronJobController::class, 'sendMail']);

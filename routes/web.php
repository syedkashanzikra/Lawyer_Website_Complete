<?php

use App\Http\Controllers\AamarpayController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdvocateController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\BenchController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CauseController;
use App\Http\Controllers\CountryStateCityController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\HighCourtController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DoctypeController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaystackPaymentController;
use App\Http\Controllers\FlutterwavePaymentController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\MercadoPaymentController;
use App\Http\Controllers\PaytmPaymentController;
use App\Http\Controllers\MolliePaymentController;
use App\Http\Controllers\SkrillPaymentController;
use App\Http\Controllers\CoingatePaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BankTransferController;
use App\Http\Controllers\BenefitPaymentController;
use App\Http\Controllers\CashfreeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\DealStageController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\HearingTypeController;
use App\Http\Controllers\IyziPayController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PayfastController;
use App\Http\Controllers\PaymentWallController;
use App\Http\Controllers\PaytabController;
use App\Http\Controllers\PaytrController;
use App\Http\Controllers\SspayController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\ToyyibpayController;
use App\Http\Controllers\UserlogController;
use App\Http\Controllers\XenditPaymentController;
use App\Http\Controllers\YooKassaController;
use App\Http\Controllers\DocSubTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KnowledgebaseCategoryController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadStageController;
use App\Http\Controllers\MotionController;
use App\Http\Controllers\OperatinghoursController;
use App\Http\Controllers\PayHereController;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\SLAPoliciyController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCustomFieldController;
use App\Models\User;


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


require __DIR__.'/auth.php';

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('optimize:clear');

    return redirect()->back()->with('success', __('Clear Cache successfully.'));
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['middleware'=>['auth','XSS','verified']], function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class);

    Route::resource('employee', EmployeeController::class);

    Route::resource('pipeline', PipelineController::class);

    Route::get('users-list', [UserController::class, 'userList'])->name('users.list');
    Route::post('users/{id}/change-password',[UserController::class,'changeMemberPassword'])->name('member.change.password');
    Route::get('user/{id}/plan', [UserController::class, 'upgradePlan'])->name('plan.upgrade')->middleware(['auth', 'XSS']);
    Route::get('user/{id}/plan/{pid}', [UserController::class, 'activePlan'])->name('plan.active')->middleware(['auth', 'XSS']);
    Route::any('company-reset-password/{id}', [UserController::class, 'companyPassword'])->name('company.reset');
    Route::any('users/verify/{id}', [UserController::class, 'verify'])->name('users.verify')->middleware(['auth', 'XSS']);
    Route::get('users/detail/{id}', [UserController::class, 'detail'])->name('users.detail')->middleware(['auth', 'XSS']);

    Route::resource('teams', TeamController::class);

    Route::resource('groups', GroupController::class);

    Route::resource('advocate', AdvocateController::class);
    Route::get('/advocate/contacts/{id}', [AdvocateController::class, 'contacts'])->name('advocate.contacts');
    Route::get('/advocate/bills/{id}', [AdvocateController::class, 'bills'])->name('advocate.bill');
    Route::get('/get-country', [CountryStateCityController::class, 'getCountry'])->name('get.country');
    Route::post('/get-state', [CountryStateCityController::class, 'getState'])->name('get.state');
    Route::post('/get-city', [CountryStateCityController::class, 'getCity'])->name('get.city');
    Route::get('/get-all-city', [CountryStateCityController::class, 'getAllState'])->name('get.all.state');

    Route::get('advocates/export', [AdvocateController::class, 'exportFile'])->name('advocates.export');
    Route::get('bills/export', [BillController::class, 'exportFile'])->name('bills.export');

    Route::get('timesheets/export', [TimeSheetController::class, 'exportFile'])->name('timesheets.export');

    Route::get('expenses/export', [ExpenseController::class, 'exportFile'])->name('expenses.export');

    Route::get('feereceive/export', [FeeController::class, 'exportFile'])->name('feereceive.export');

    Route::resource('courts', CourtController::class);

    Route::resource('highcourts', HighCourtController::class);

    Route::resource('bench', BenchController::class);

    Route::resource('cause', CauseController::class);
    Route::post('/cause/get-highcourts', [CauseController::class, 'getHighCourt'])->name('get.highcourt');
    Route::post('/cause/get-bench', [CauseController::class, 'getBench'])->name('get.bench');

    Route::resource('cases', CaseController::class);
    Route::get('cases/journey/{id}', [CaseController::class, 'journey'])->name('cases.journey');
    Route::post('cases/journey-update/{id}', [CaseController::class, 'updateJourney'])->name('update.journey');
    Route::get('/cases/docs-delete/{id}/{key}', [CaseController::class, 'casesDocsDestroy'])->name('cases.docs.destroy');
    Route::get('import/case/file', [CaseController::class, 'importFile'])->name('case.file.import');
    Route::post('import/case', [CaseController::class, 'import'])->name('case.import');

    Route::get('export/case/file', [CaseController::class, 'exportFile'])->name('cases.export');


    Route::resource('to-do', ToDoController::class);
    Route::get('to-do/status/{id}', [ToDoController::class, 'status'])->name('to-do.status');
    Route::PUT('to-do/status-update/{id}', [ToDoController::class, 'statusUpdate'])->name('to-do.status.update');

    Route::get('bills/addpayment/{bill_id}', [BillController::class,'paymentcreate'])->name('create.payment');
    Route::POST('bills/storepayment/{bill_id}', [BillController::class,'paymentstore'])->name('payment.store');


    Route::resource('taxs', TaxController::class);
    Route::post('taxs/get-tax/', [TaxController::class, 'getTax'])->name('get.tax');

    Route::resource('casediary', DiaryController::class);

    Route::resource('timesheet', TimeSheetController::class);

    Route::resource('expenses', ExpenseController::class);

    Route::resource('fee-receive', FeeController::class);

    Route::resource('calendar', CalendarController::class);

    Route::resource('documents', DocumentController::class);

    Route::resource('doctype', DoctypeController::class);

    Route::resource('doctsubype', DocSubTypeController::class);
    Route::post('doctsubype/getDocSubType', [DocSubTypeController::class, 'getDocSubType'])->name('get.docSubType');

    Route::resource('motions', MotionController::class);

    Route::resource('settings', SettingController::class);

    Route::post('storage-settings',[SettingController::class,'storageSettingStore'])->name('storage.setting.store');


    Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
    Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
    Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
    Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
    Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
    Route::delete('destroy-language/{lang}', [LanguageController::class, 'destroyLang'])->name('destroy.language');
    Route::post('disable-language',[LanguageController::class,'disableLang'])->name('disablelanguage')->middleware(['auth','XSS']);

    Route::post('cookie-setting', [SettingController::class, 'saveCookieSettings'])->name('cookie.setting');
    Route::post('email-settings', [SettingController::class,'saveCompanyEmailSettings'])->name('email.settings');
    Route::post('company-email-settings', [SettingController::class,'saveCompanyEmailSettings'])->name('company.email.settings');
    Route::any('test', [SettingController::class,'testMail'])->name('test.mail');
    Route::post('test-mail', [SettingController::class,'testSendMail'])->name('test.send.mail');
    Route::post('setting/seo', [SettingController::class, 'SeoSettings'])->name('seo.settings');




    Route::post('recaptcha-settings', [SettingController::class, 'recaptchaSettingStore'])->name('recaptcha.settings.store');

    Route::resource('plans', PlanController::class);

    Route::get('system-settings', [SettingController::class, 'adminSettings'])->name('admin.settings');
    Route::post('business-setting', [SettingController::class,'saveBusinessSettings'])->name('business.setting');

    Route::get('plan_request',[PlanRequestController::class,'index'])->name('plan_request.index');
    Route::get('/payment/{code}', [PlanController::class, 'payment'])->name('payment');
    Route::get('request_send/{id}', [PlanRequestController::class,'userRequest'])->name('send.request');
    Route::get('request_cancel/{id}', [PlanRequestController::class,'cancelRequest'])->name('request.cancel');
    Route::get('request_response/{id}/{response}', [PlanRequestController::class,'acceptRequest'])->name('response.request');

    Route::get('user/{id}/plans/{planid}', [UserController::class, 'deactivatePlan'])->name('plan.deactivate')->middleware(['auth', 'XSS']);

    Route::resource('coupons', CouponController::class);

    Route::get('/orders', [StripePaymentController::class, 'index'])->name('order.index');
    Route::get('/apply-coupon', [CouponController::class,'applyCoupon'])->name('apply.coupon');

    Route::post('/stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');

    Route::post('plan-pay-with-paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal');
    Route::get('{id}/{amount}/plan-get-payment-status', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status');

    Route::post('/plan-pay-with-paystack', [PaystackPaymentController::class, 'planPayWithPaystack'])->name('plan.pay.with.paystack');
    Route::get('/plan/paystack/{pay_id}/{plan_id}/', [PaystackPaymentController::class, 'getPaymentStatus'])->name('plan.paystack');

    Route::post('/plan-pay-with-flaterwave', [FlutterwavePaymentController::class, 'planPayWithFlutterwave'])->name('plan.pay.with.flaterwave');
    Route::get('/plan/flaterwave/{txref}/{plan_id}', [FlutterwavePaymentController::class, 'getPaymentStatus'])->name('plan.flaterwave');

    Route::post('/plan-pay-with-razorpay', [RazorpayPaymentController::class, 'planPayWithRazorpay'])->name('plan.pay.with.razorpay');
    Route::get('/plan/razorpay/{txref}/{plan_id}', [RazorpayPaymentController::class, 'getPaymentStatus'])->name('plan.razorpay');

    Route::post('/plan-pay-with-paytm', 'App\Http\Controllers\PaytmPaymentController@planPayWithPaytm')->name('plan.pay.with.paytm');
    Route::post('/plan/paytm/{plan_id}', [PaytmPaymentController::class, 'getPaymentStatus'])->name('plan.paytm');

    Route::post('/plan-pay-with-mercado', [MercadoPaymentController::class, 'planPayWithMercado'])->name('plan.pay.with.mercado');
    Route::get('/plan/mercado/{plan}/{amount}', [MercadoPaymentController::class, 'getPaymentStatus'])->name('plan.mercado');

    Route::post('/plan-pay-with-mollie', [MolliePaymentController::class, 'planPayWithMollie'])->name('plan.pay.with.mollie');
    Route::get('/plan/mollie/{plan}', [MolliePaymentController::class, 'getPaymentStatus'])->name('plan.mollie');

    Route::post('/plan-pay-with-skrill', [SkrillPaymentController::class, 'planPayWithSkrill'])->name('plan.pay.with.skrill');
    Route::get('/plan/skrill/{plan_id}', [SkrillPaymentController::class, 'getPaymentStatus'])->name('plan.skrill');

    Route::post('/plan-pay-with-coingate', [CoingatePaymentController::class, 'planPayWithCoingate'])->name('plan.pay.with.coingate');
    Route::get('/plan/coingate/{plan}', [CoingatePaymentController::class, 'getPaymentStatus'])->name('plan.coingate');

    Route::post('/planpayment', [PaymentWallController::class, 'planpay'])->name('paymentwall');
    Route::post('/paymentwall-payment/{plan}', [PaymentWallController::class, 'planPayWithPaymentWall'])->name('paymentwall.payment');
    Route::get('/plan/error/{flag}', [PaymentWallController::class, 'planerror'])->name('error.plan.show');

    Route::post('/plan-pay-with-toyyibpay', [ToyyibpayController::class, 'planPayWithToyyibpay'])->name('plan.pay.with.toyyibpay');
    Route::get('/plan-pay-with-toyyibpay/{id}/{amount}/{couponCode?}', [ToyyibpayController::class, 'planGetPaymentStatus'])->name('plan.toyyibpay');

    Route::post('payfast-plan', [PayfastController::class, 'index'])->name('payfast.payment')->middleware(['auth']);
    Route::get('payfast-plan/{success}', [PayfastController::class, 'success'])->name('payfast.payment.success')->middleware(['auth']);

    Route::post('plan-pay-with-bank', [BankTransferController::class, 'planPayWithbank'])->name('plan.pay.with.bank');
    Route::get('orders/show/{id}', [BankTransferController::class, 'show'])->name('order.show');
    Route::delete('/bank_transfer/{order}/', [BankTransferController::class, 'destroy'])->name('bank_transfer.destroy');
    Route::any('order_approve/{id}', [BankTransferController::class, 'orderapprove'])->name('order.approve');
    Route::any('order_reject/{id}', [BankTransferController::class, 'orderreject'])->name('order.reject');

    Route::post('pusher-setting', [SettingController::class, 'savePusherSettings'])->name('pusher.setting');
    Route::get('/advocate/view/{id}', [AdvocateController::class, 'view'])->name('advocate.view');

    Route::post('setting/google-calender', [SettingController::class, 'saveGoogleCalenderSettings'])->name('google.calender.settings');
    Route::post('data/get_all_data', [CalendarController::class, 'get_call_data'])->name('call.get_call_data');

    Route::resource('userlog',UserlogController::class);
    Route::delete('/userlog/{id}/', [UserlogController::class, 'destroy'])->name('userlog.destroy')->middleware(['auth','XSS']);
    Route::get('userlog-view/{id}/', [UserlogController::class, 'view'])->name('userlog.view')->middleware(['auth','XSS']);


    //iyzipay
    Route::post('iyzipay/prepare', [IyziPayController::class, 'initiatePayment'])->name('iyzipay.payment.init');
    Route::post('iyzipay/callback/plan/{id}/{amount}/{coupan_code?}', [IyzipayController::class, 'iyzipayCallback'])->name('iyzipay.payment.callback');

    Route::post('/sspay', [SspayController::class,'SspayPaymentPrepare'])->name('plan.sspaypayment');
    Route::get('sspay-payment-plan/{plan_id}/{amount}/{couponCode}', [SspayController::class, 'SspayPlanGetPayment'])->name('plan.sspay.callback');

    Route::post('plan-pay-with-paytab', [PaytabController::class, 'planPayWithpaytab'])->name('plan.pay.with.paytab');
    Route::any('paytab-success/plan', [PaytabController::class, 'PaytabGetPayment'])->name('plan.paytab.success');

    // Benefit
    Route::any('/payment/initiate', [BenefitPaymentController::class, 'initiatePayment'])->name('benefit.initiate');
    Route::any('call_back', [BenefitPaymentController::class, 'call_back'])->name('benefit.call_back');

    // cashfree
    Route::post('cashfree/payments/', [CashfreeController::class, 'planPayWithcashfree'])->name('plan.pay.with.cashfree');
    Route::any('cashfree/payments/success', [CashfreeController::class, 'getPaymentStatus'])->name('plan.cashfree');

    // Aamarpay
    Route::post('/aamarpay/payment', [AamarpayController::class, 'planPayWithpay'])->name('plan.pay.with.aamarpay');
    Route::any('/aamarpay/success/{data}', [AamarpayController::class, 'getPaymentStatus'])->name('plan.aamarpay');

    // PayTR
    Route::post('/paytr/payment', [PaytrController::class, 'PlanpayWithPaytr'])->name('plan.pay.with.paytr');
    Route::any('/paytr/success/', [PaytrController::class, 'paytrsuccessCallback'])->name('pay.paytr.success');

    Route::resource('country',CountryController::class);
    Route::resource('state',StateController::class);
    Route::resource('city',CityController::class);

    Route::resource('hearingType',HearingTypeController::class);
    Route::get('/hearing/{case_id}', [HearingController::class, 'create'])->name('hearings.create');
    Route::resource('hearing',HearingController::class);
    Route::get('import/hearing/file/{case_id}', [HearingController::class, 'importFile'])->name('hearing.file.import');
    Route::post('import/hearing', [HearingController::class, 'import'])->name('hearing.import');

    Route::resource('appointments',AppointmentsController::class);

    Route::get('/plan/yookassa/payment', [YooKassaController::class,'planPayWithYooKassa'])->name('plan.pay.with.yookassa');
    Route::get('/plan/yookassa/{plan}', [YooKassaController::class,'planGetYooKassaStatus'])->name('plan.get.yookassa.status');

    Route::any('/midtrans', [MidtransController::class, 'planPayWithMidtrans'])->name('plan.get.midtrans');
    Route::any('/midtrans/callback', [MidtransController::class, 'planGetMidtransStatus'])->name('plan.get.midtrans.status');

    Route::any('/xendit/payment', [XenditPaymentController::class, 'planPayWithXendit'])->name('plan.xendit.payment');
    Route::any('/xendit/payment/status', [XenditPaymentController::class, 'planGetXenditStatus'])->name('plan.xendit.status');

    Route::post('plan-payhere-payment', [PayHereController::class, 'planPayWithPayHere'])->name('plan.payhere.payment');
    Route::get('/plan-payhere-status', [PayHereController::class, 'planGetPayHereStatus'])->name('plan.payhere.status');

    Route::resource('client', ClientController::class);
    Route::get('client-list', [ClientController::class, 'userList'])->name('client.list');

    Route::post('leadStage/order', [LeadStageController::class, 'order'])->name('leadStage.order');
    Route::resource('leadStage', LeadStageController::class);

    Route::post('dealStage/order', [DealStageController::class, 'order'])->name('dealStage.order');
    Route::post('dealStage/json', [DealStageController::class, 'json'])->name('dealStage.json');
    Route::resource('dealStage', DealStageController::class);

    Route::resource('dealStage', DealStageController::class);
    Route::resource('source', SourceController::class);
    Route::resource('label', LabelController::class);
    Route::get('lead/grid', [LeadController::class, 'grid'])->name('lead.grid');
    Route::post('lead/json', [LeadController::class, 'json'])->name('lead.json');
    Route::post('lead/order', [LeadController::class, 'order'])->name('lead.order');
    Route::get('lead/{id}/users', [LeadController::class, 'userEdit'])->name('lead.users.edit');
    Route::post('lead/{id}/users', [LeadController::class, 'userUpdate'])->name('lead.users.update');
    Route::delete('lead/{id}/users/{uid}', [LeadController::class, 'userDestroy'])->name('lead.users.destroy');

    Route::get('lead/{id}/items', [LeadController::class, 'productEdit'])->name('lead.items.edit');
    Route::post('lead/{id}/items', [LeadController::class, 'productUpdate'])->name('lead.items.update');
    Route::delete('lead/{id}/items/{uid}', [LeadController::class, 'productDestroy'])->name('lead.items.destroy');

    Route::post('lead/{id}/file', [LeadController::class, 'fileUpload'])->name('lead.file.upload');
    Route::get('lead/{id}/file/{fid}', [LeadController::class, 'fileUpload'])->name('lead.file.download');
    Route::delete('lead/{id}/file/delete/{fid}', [LeadController::class, 'fileDelete'])->name('lead.file.delete');

    Route::get('lead/{id}/sources', [LeadController::class, 'sourceEdit'])->name('lead.sources.edit');
    Route::post('lead/{id}/sources', [LeadController::class, 'sourceUpdate'])->name('lead.sources.update');
    Route::delete('lead/{id}/sources/{uid}', [LeadController::class, 'sourceDestroy'])->name('lead.sources.destroy');

    Route::get('lead/{id}/discussions', [LeadController::class, 'discussionCreate'])->name('lead.discussions.create');
    Route::post('lead/{id}/discussions', [LeadController::class, 'discussionStore'])->name('lead.discussion.store');

    Route::get('lead/{id}/call', [LeadController::class, 'callCreate'])->name('lead.call.create');
    Route::post('lead/{id}/call', [LeadController::class, 'callStore'])->name('lead.call.store');
    Route::get('lead/{id}/call/{cid}/edit', [LeadController::class, 'callEdit'])->name('lead.call.edit');
    Route::post('lead/{id}/call/{cid}', [LeadController::class, 'callUpdate'])->name('lead.call.update');
    Route::delete('lead/{id}/call/{cid}', [LeadController::class, 'callDestroy'])->name('lead.call.destroy');

    Route::get('lead/{id}/email', [LeadController::class, 'emailCreate'])->name('lead.email.create');
    Route::post('lead/{id}/email', [LeadController::class, 'emailStore'])->name('lead.email.store');

    Route::get('lead/{id}/label', [LeadController::class, 'labels'])->name('lead.label');
    Route::post('lead/{id}/label', [LeadController::class, 'labelStore'])->name('lead.label.store');

    Route::get('lead/{id}/show_convert', [LeadController::class, 'showConvertToDeal'])->name('lead.convert.deal');
    Route::post('lead/{id}/convert', [LeadController::class, 'convertToDeal'])->name('lead.convert.to.deal');

    Route::get('lead/{id}/show_convert', [LeadController::class, 'showConvertToDeal'])->name('lead.convert.deal');
    Route::post('lead/{id}/convert', [LeadController::class, 'convertToDeal'])->name('lead.convert.to.deal');

    Route::post('lead/change-pipeline', [LeadController::class, 'changePipeline'])->name('lead.change.pipeline');
    Route::resource('lead', LeadController::class);
    Route::post('lead/{id}/note', [LeadController::class, 'noteStore'])->name('lead.note.store');
    Route::post('deal/order', [DealController::class, 'order'])->name('deal.order');
    Route::get('deal/{id}/users', [DealController::class, 'userEdit'])->name('deal.users.edit');
    Route::post('deal/{id}/users', [DealController::class, 'userUpdate'])->name('deal.users.update');
    Route::delete('deal/{id}/users/{uid}', [DealController::class, 'userDestroy'])->name('deal.users.destroy');

    Route::post('deal/{id}/update', [DealController::class, 'Update'])->name('deal.update');


    Route::get('deal/{id}/items', [DealController::class, 'productEdit'])->name('deal.items.edit');
    Route::post('deal/{id}/items', [DealController::class, 'productUpdate'])->name('deal.items.update');
    Route::delete('deal/{id}/items/{uid}', [DealController::class, 'productDestroy'])->name('deal.items.destroy');

    Route::post('deal/{id}/file', [DealController::class, 'fileUpload'])->name('deal.file.upload');
    Route::get('deal/{id}/file/{fid}', [DealController::class, 'fileDownload'])->name('deal.file.download');
    Route::delete('deal/{id}/file/delete/{fid}', [DealController::class, 'fileDelete'])->name('deal.file.delete');



    Route::get('deal/{id}/task', [DealController::class, 'taskCreate'])->name('deal.tasks.create');
    Route::post('deal/{id}/task', [DealController::class, 'taskStore'])->name('deal.tasks.store');
    Route::get('deal/{id}/task/{tid}/show', [DealController::class, 'taskShow'])->name('deal.tasks.show');
    Route::get('deal/{id}/task/{tid}/edit', [DealController::class, 'taskEdit'])->name('deal.tasks.edit');
    Route::post('deal/{id}/task/{tid}', [DealController::class, 'taskUpdate'])->name('deal.tasks.update');
    Route::post('deal/{id}/task_status/{tid}', [DealController::class, 'taskUpdateStatus'])->name('deal.tasks.update_status');
    Route::delete('deal/{id}/task/{tid}', [DealController::class, 'taskDestroy'])->name('deal.tasks.destroy');

    Route::get('deal/{id}/products', [DealController::class, 'productEdit'])->name('deal.products.edit');
    Route::post('deal/{id}/products', [DealController::class, 'productUpdate'])->name('deal.products.update');
    Route::delete('deal/{id}/products/{uid}', [DealController::class, 'productDestroy'])->name('deal.products.destroy');

    Route::get('deal/{id}/sources', [DealController::class, 'sourceEdit'])->name('deal.sources.edit');
    Route::post('deal/{id}/sources', [DealController::class, 'sourceUpdate'])->name('deal.sources.update');
    Route::delete('deal/{id}/sources/{uid}', [DealController::class, 'sourceDestroy'])->name('deal.sources.destroy');



    Route::get('deal/{id}/discussions', [DealController::class, 'discussionCreate'])->name('deal.discussions.create');
    Route::post('deal/{id}/discussions', [DealController::class, 'discussionStore'])->name('deal.discussion.store');


    Route::get('deal/{id}/call', [DealController::class, 'callCreate'])->name('deal.call.create');
    Route::post('deal/{id}/call', [DealController::class, 'callStore'])->name('deal.call.store');
    Route::get('deal/{id}/call/{cid}/edit', [DealController::class, 'callEdit'])->name('deal.call.edit');
    Route::post('deal/{id}/call/{cid}', [DealController::class, 'callUpdate'])->name('deal.call.update');
    Route::delete('deal/{id}/call/{cid}', [DealController::class, 'callDestroy'])->name('deal.call.destroy');

    Route::get('deal/{id}/email', [DealController::class, 'emailCreate'])->name('deal.email.create');
    Route::post('deal/{id}/email', [DealController::class, 'emailStore'])->name('deal.email.store');

    Route::get('deal/{id}/clients', [DealController::class, 'clientEdit'])->name('deal.clients.edit');
    Route::post('deal/{id}/clients', [DealController::class, 'clientUpdate'])->name('deal.clients.update');
    Route::delete('deal/{id}/clients/{uid}', [DealController::class, 'clientDestroy'])->name('deal.clients.destroy');

    Route::get('deal/{id}/labels', [DealController::class, 'labels'])->name('deal.labels');
    Route::post('deal/{id}/labels', [DealController::class, 'labelStore'])->name('deal.labels.store');


    Route::get('deal/list', [DealController::class, 'deal_list'])->name('deal.list');
    Route::post('deal/change-pipeline', [DealController::class, 'changePipeline'])->name('deal.change.pipeline');


    Route::post('deal/change-deal-status/{id}', [DealController::class, 'changeStatus'])->name('deal.change.status')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );

    Route::resource('deal', DealController::class);
    Route::post('deal/{id}/note', [DealController::class, 'noteStore'])->name('deal.note.store')->middleware(['auth']);
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::delete('category/{id}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::put('category/{id}/update', [CategoryController::class, 'update'])->name('category.update');
    Route::resource('operating_hours', OperatinghoursController::class)->middleware('auth','XSS');
    Route::resource('priority', PriorityController::class)->middleware('auth','XSS');
    Route::resource('policiy', SLAPoliciyController::class)->middleware('auth','XSS');
    Route::get('ticket/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('ticket', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('ticket', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('ticket/{id}/edit', [TicketController::class, 'editTicket'])->name('tickets.edit');
    Route::delete('ticket/{id}/destroy', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::delete('ticket-attachment/{tid}/destroy/{id}', [TicketController::class, 'attachmentDestroy'])->name('tickets.attachment.destroy');
    Route::put('ticket/{id}/update', [TicketController::class, 'updateTicket'])->name('tickets.update');
    Route::post('ticket/{id}/note', [TicketController::class, 'storeNote'])->name('note.store');

    Route::get('home/{id}/login-with-admin',[UserController::class,'LoginWithAdmin'])->name('login.with.admin')->middleware('auth','XSS');
    Route::get('login-with-admin/exit',[UserController::class,'ExitAdmin'])->name('exit.admin')->middleware('auth','XSS');

    Route::get('/fileexport', [LeadController::class, 'fileExports'])->name('leads.export');
    Route::get('/import', [LeadController::class, 'fileImportExport'])->name('leads.file.import');
    Route::post('leads/import', [LeadController::class, 'fileImport'])->name('leads.import');

    Route::get('/filesexport', [DealController::class, 'fileExports'])->name('deals.export');
    Route::get('/fileimport', [DealController::class, 'fileImportExport'])->name('deals.file.import');
    Route::post('deals/import', [DealController::class, 'fileImport'])->name('deals.import');

    Route::get('company-info/{id}', [UserController::class, 'companyInfo'])->name('company.info');
    Route::post('user-unable', [UserController::class, 'userUnable'])->name('user.unable');
});

Route::get('user/ticket/create', [HomeController::class, 'index'])->name('user.ticket.create');

Route::controller(HomeController::class)->group(function(){

    Route::get('home', 'index')->name('home');
    Route::post('home', 'store')->name('home.store');
    Route::get('user/ticket/search', 'search')->name('user.ticket.search');
    Route::post('search', 'ticketSearch')->name('ticket.search');
    Route::get('tickets/{id}', 'view')->name('home.view');
    Route::post('ticket/{id}', 'reply')->name('user.ticket.reply');
    Route::get('user/faq', 'faq')->name('user.faq');
    Route::get('user/knowledge', 'knowledge')->name('user.knowledge');
    Route::get('knowledgedesc', 'knowledgeDescription')->name('knowledgedesc');

});
Route::resource('bills', BillController::class);
Route::post('ticket/{id}/conversion', [ConversionController::class, 'store'])->name('conversion.store');

Route::get('faq/index', [FaqController::class, 'index'])->name('faqs.index');
Route::get('faq/create', [FaqController::class, 'create'])->name('faq.create');
Route::post('faq', [FaqController::class, 'store'])->name('faq.store');
Route::get('faq/{id}/edit', [FaqController::class, 'edit'])->name('faq.edit');
Route::delete('faq/{id}/destroy', [FaqController::class, 'destroy'])->name('faq.destroy');
Route::put('faq/{id}/update', [FaqController::class, 'update'])->name('faq.update');

Route::get('knowledge', [KnowledgeController::class, 'index'])->name('knowledge');
Route::get('knowledge/create', [KnowledgeController::class, 'create'])->name('knowledge.create');
Route::post('knowledge', [KnowledgeController::class, 'store'])->name('knowledge.store');
Route::get('knowledge/{id}/edit', [KnowledgeController::class, 'edit'])->name('knowledge.edit');
Route::delete('knowledge/{id}/destroy', [KnowledgeController::class, 'destroy'])->name('knowledge.destroy');
Route::put('knowledge/{id}/update', [KnowledgeController::class, 'update'])->name('knowledge.update');
Route::get('knowledgecategory', [KnowledgebaseCategoryController::class, 'index'])->name('knowledgecategory');
Route::get('knowledgecategory/create', [KnowledgebaseCategoryController::class, 'create'])->name('knowledgecategory.create');
Route::post('knowledgecategory', [KnowledgebaseCategoryController::class, 'store'])->name('knowledgecategory.store');
Route::get('knowledgecategory/{id}/edit', [KnowledgebaseCategoryController::class, 'edit'])->name('knowledgecategory.edit');
Route::delete('knowledgecategory/{id}/destroy', [KnowledgebaseCategoryController::class, 'destroy'])->name('knowledgecategory.destroy');
Route::put('knowledgecategory/{id}/update', [KnowledgebaseCategoryController::class, 'update'])->name('knowledgecategory.update');
Route::any('ticket/custom/field', [TicketCustomFieldController::class, 'index'])->name('ticket.custom.field.index');
Route::any('/custom-fields', [TicketCustomFieldController::class, 'storeCustomFields'])->name('custom-fields.store');
Route::get('export/tickets', [TicketController::class, 'export'])->name('tickets.export');

Route::any('/cookie-consent', [SettingController::class, 'CookieConsent'])->name('cookie-consent');

Route::post('payment-setting', [SettingController::class, 'savePaymentSettings'])->name('payment.settings')->middleware(['auth','verified']);
Route::post('admin-payment-setting', [SettingController::class, 'saveAdminPaymentSettings'])->name('admin.payment.settings')->middleware(['auth','verified']);


Route::get('/bills/pay/{bill_id}', [BillController::class, 'payinvoice'])->name('pay.invoice')->middleware(['XSS']);

Route::post('bills/{id}/payment', [StripePaymentController::class, 'addpayment'])->name('invoice.payment')->middleware(['XSS']);

Route::post('bills/{id}/bill-with-paypal', [PaypalController::class,'PayWithPaypal'])->name('bill.with.paypal')->middleware(['XSS']);
Route::get('{id}/get-payment-status/{amount}', [PaypalController::class,'GetPaymentStatus'])->name('get.payment.status')->middleware(['XSS']);

Route::POST('bills/getclientdetail', [BillController::class,'getClientDetail'])->name('get.client.detail');
Route::POST('bills/getadvocatedetail', [BillController::class,'getadvocateDetail'])->name('get.advocate.detail');

Route::post('/invoice-pay-with-paystack', [PaystackPaymentController::class, 'invoicePayWithPaystack'])->name('invoice.pay.with.paystack')->middleware(['XSS']);
Route::get('/invoice/paystack/{invoice_id}/{amount}/{pay_id}', [PaystackPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.paystack')->middleware(['XSS']);

Route::post('/invoice-pay-with-flaterwave', [FlutterwavePaymentController::class, 'invoicePayWithFlutterwave'])->name('invoice.pay.with.flaterwave')->middleware(['XSS']);
Route::get('/invoice/flaterwave/{txref}/{invoice_id}', [FlutterwavePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.flaterwave')->middleware(['XSS']);

Route::post('/invoice-pay-with-razorpay', [RazorpayPaymentController::class, 'invoicePayWithRazorpay'])->name('invoice.pay.with.razorpay')->middleware(['XSS']);
Route::get('/invoice/razorpay/{txref}/{invoice_id}', [RazorpayPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.razorpay');

Route::post('/invoice-pay-with-mercado', [MercadoPaymentController::class, 'invoicePayWithMercado'])->middleware(['XSS'])->name('invoice.pay.with.mercado');
Route::any('/invoice/mercado/{invoice}', [MercadoPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.mercado')->middleware(['XSS']);

Route::post('/invoice-pay-with-paytm', [PaytmPaymentController::class, 'invoicePayWithPaytm'])->middleware(['XSS'])->name('invoice.pay.with.paytm');
Route::post('/invoice/paytm/{invoice}', [PaytmPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.paytm')->middleware(['XSS']);

Route::post('/invoice-pay-with-mollie', [MolliePaymentController::class, 'invoicePayWithMollie'])->middleware(['XSS'])->name('invoice.pay.with.mollie');
Route::get('/invoice/mollie/{invoice}', [MolliePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.mollie')->middleware(['XSS']);

Route::post('/invoice-pay-with-skrill', [SkrillPaymentController::class, 'invoicePayWithSkrill'])->middleware(['XSS'])->name('invoice.pay.with.skrill');
Route::get('/invoice/skrill/{invoice}', [SkrillPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.skrill')->middleware(['XSS']);

Route::post('/invoice-pay-with-coingate', [CoingatePaymentController::class, 'invoicePayWithCoingate'])->middleware(['XSS'])->name('invoice.pay.with.coingate');
Route::get('/invoice/coingate/{invoice}', [CoingatePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.coingate')->middleware(['XSS']);

Route::post('/invoicepayment', [PaymentWallController::class, 'invoicePayWithPaymentwall'])->name('paymentwall.invoice');
Route::post('/invoice-pay-with-paymentwall/{invoice}', [PaymentWallController::class, 'getInvoicePaymentStatus'])->name('invoice-pay-with-paymentwall');
Route::any('/invoice/error/{flag}/{invoice_id}', [PaymentWallController::class, 'invoiceerror'])->name('error.invoice.show');

Route::post('/invoice-with-toyyibpay', [ToyyibpayController::class, 'invoicepaywithtoyyibpay'])->name('invoice.with.toyyibpay');
Route::get('/invoice-toyyibpay-status/{amount}/{invoice_id}', [ToyyibpayController::class, 'invoicetoyyibpaystatus'])->name('invoice.toyyibpay.status');

Route::post('/invoice-with-payfast', [PayfastController::class, 'invoicepaywithpayfast'])->name('invoice.with.payfast');
Route::get('/invoice-payfast-status/{invoice_id}', [PayfastController::class, 'invoicepayfaststatus'])->name('invoice.payfast.status');

Route::any('/pay-with-bank', [BankTransferController::class, 'invoicePayWithbank'])->name('invoice.pay.with.bank');
Route::get('bankpayment/show/{id}', [BankTransferController::class, 'bankpaymentshow'])->name('bankpayment.show');
Route::delete('invoice/bankpayment/{id}/delete', [BankTransferController::class, 'invoicebankPaymentDestroy'])->name('invoice.bankpayment.delete');
Route::post('/invoice/status/{id}', [BankTransferController::class, 'invoicebankstatus'])->name('invoice.status');

Route::post('/invoice-with-iyzipay', [IyziPayController::class, 'invoicepaywithiyzipay'])->name('invoice.with.iyzipay');
Route::post('/invoice-iyzipay-status/{invoice_id}/{amount}', [IyziPayController::class, 'invoiceiyzipaystatus'])->name('invoice.iyzipay.status');

Route::post('/customer-pay-with-sspay', [SspayController::class,'invoicepaywithsspaypay'])->name('customer.pay.with.sspay');
Route::get('/customer/sspay/{invoice}/{amount}', [SspayController::class,'getInvoicePaymentStatus'])->name('customer.sspay');

Route::post('invoice-with-paytab/', [PaytabController::class, 'invoicePayWithpaytab'])->name('pay.with.paytab');
Route::any('invoice-paytab-status/{invoice}/{amount}', [PaytabController::class, 'PaytabGetPaymentCallback'])->name('invoice.paytab.status');

Route::post('invoice-with-benefit/', [BenefitPaymentController::class, 'invoicePayWithbenefit'])->name('pay.with.paytab');
Route::any('invoice-benefit-status/{invoice_id}/{amount}', [BenefitPaymentController::class, 'getInvociePaymentStatus'])->name('invoice.benefit.status');


// cashfree
Route::post('invoice-with-cashfree/', [CashfreeController::class, 'invoicePayWithcashfree'])->name('pay.with.cashfree');
Route::any('invoice-cashfree-status/', [CashfreeController::class, 'getInvociePaymentStatus'])->name('invoice.cashfree.status');


Route::post('invoice-with-aamarpay/', [AamarpayController::class, 'invoicePayWithaamarpay'])->name('pay.with.aamarpay');
Route::any('invoice-aamarpay-status/{data}', [AamarpayController::class, 'getInvociePaymentStatus'])->name('invoice.aamarpay.status');


Route::post('invoice-with-paytr/', [PaytrController::class, 'invoicePayWithpaytr'])->name('invoice.with.paytr');
Route::any('invoice-paytr-status/', [PaytrController::class, 'getInvociePaymentStatus'])->name('invoice.paytr.status');

Route::post('invoice-with-yookassa/', [YooKassaController::class, 'invoicePayWithYookassa'])->name('invoice.with.yookassa');
Route::any('invoice-yookassa-status/', [YooKassaController::class, 'getInvociePaymentStatus'])->name('invoice.yookassa.status');

Route::any('invoice-with-midtrans/', [MidtransController::class, 'invoicePayWithMidtrans'])->name('invoice.with.midtrans');
Route::any('invoice-midtrans-status/', [MidtransController::class, 'getInvociePaymentStatus'])->name('invoice.midtrans.status');

Route::any('/invoice-with-xendit', [XenditPaymentController::class, 'invoicePayWithXendit'])->name('invoice.with.xendit');
Route::any('/invoice-xendit-status', [XenditPaymentController::class, 'getInvociePaymentStatus'])->name('invoice.xendit.status');

Route::post('invoice-payhere-payment', [PayHereController::class, 'invoicePayWithPayHere'])->name('invoice.payhere.payment');
Route::get('/invoice-payhere-status', [PayHereController::class, 'invoiceGetPayHereStatus'])->name('invoice.payhere.status');


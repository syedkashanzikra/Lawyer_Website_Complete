<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Mail\testMail;
use App\Models\State;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage setting')) {
            $settings = Utility::settings();

            $company_payment_setting = Utility::getCompanyPaymentSetting(Auth::user()->id);

            return view('settings.index', compact('settings', 'company_payment_setting'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('manage setting')) {
            $user = Auth::user();
            if ($request->company_logo_dark) {
                $image_size = $request->file('company_logo_dark')->getSize();
                $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);
                if ($result == 1) {
                    $request->validate(
                        [
                            'company_logo_dark' => 'image',
                        ]
                    );

                    $logoName = $user->id . '-logo-dark.png';
                    $dir = 'uploads/logo/';

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $path = Utility::upload_file($request, 'company_logo_dark', $logoName, $dir, $validation);

                    if ($path['flag'] == 1) {
                        $company_logo_dark = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                    $company_logo = !empty($request->company_logo_dark) ? $logoName : 'logo-dark.png';

                    DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $logoName,
                            'company_logo_dark',
                            Auth::user()->creatorId(),
                        ]
                    );
                }
            }

            if ($request->company_logo_light) {
                $image_size = $request->file('company_logo_light')->getSize();
                $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);
                if ($result == 1) {
                    $request->validate(
                        [
                            'company_logo_light' => 'image',
                        ]
                    );

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];

                    $logoName = $user->id . '-logo-light.png';
                    $dir = 'uploads/logo/';

                    $path = Utility::upload_file($request, 'company_logo_light', $logoName, $dir, $validation);
                    if ($path['flag'] == 1) {
                        $company_logo_light = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                    $company_logo = !empty($request->company_logo_light) ? $logoName : 'logo-light.png';

                    DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $logoName,
                            'company_logo_light',
                            Auth::user()->creatorId(),
                        ]
                    );
                }
            }

            if ($request->company_favicon) {
                $image_size = $request->file('company_favicon')->getSize();
                $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);
                if ($result == 1) {
                    $request->validate(
                        [
                            'company_favicon' => 'image',
                        ]
                    );
                    $favicon = $user->id . '_favicon.png';
                    $dir = 'uploads/logo/';
                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];

                    $path = Utility::upload_file($request, 'company_favicon', $favicon, $dir, $validation);
                    $company_favicon = !empty($request->favicon) ? $favicon : 'favicon.png';

                    if ($path['flag'] == 1) {
                        $company_favicon = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                    DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $favicon,
                            'company_favicon',
                            Auth::user()->creatorId(),
                        ]
                    );
                }
            }

            $user = Auth::user();
            $arrEnv = [
                'SITE_RTL' => !isset($request->SITE_RTL) ? 'off' : 'on',
            ];
            Utility::setEnvironmentValue($arrEnv);

            $settings = Utility::settings();
            if (!empty($request->title_text) || !empty($request->footer_text) || !empty($request->default_language) || isset($request->display_landing_page) || isset($request->color) || isset($request->cust_theme_bg) || isset($request->cust_darklayout)) {
                $post = $request->all();
                if (!isset($request->display_landing_page)) {
                    $post['display_landing_page'] = 'off';
                }
                if (!isset($request->color)) {
                    $color = $request->has('color') ? $request->color : 'theme-1';
                    $post['color'] = $color;
                }
                if (!isset($request->cust_theme_bg)) {
                    $cust_theme_bg = (isset($request->cust_theme_bg)) ? 'on' : 'off';
                    $post['cust_theme_bg'] = $cust_theme_bg;
                }
                if (!isset($request->cust_darklayout)) {

                    $cust_darklayout = isset($request->cust_darklayout) ? 'on' : 'off';
                    $post['cust_darklayout'] = $cust_darklayout;
                }
                if (!isset($request->SITE_RTL)) {
                    $SITE_RTL = isset($request->SITE_RTL) ? 'on' : 'off';
                    $post['SITE_RTL'] = $SITE_RTL;
                }

                unset($post['_token'], $post['company_logo_dark'], $post['company_logo_light'], $post['company_favicon']);
                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings))) {
                        DB::insert('insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                            $data,
                            $key,
                            Auth::user()->creatorId(),
                        ]);
                    }
                }
            }

            return redirect()->route('settings.index')->with('success', __('Brand setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function storageSettingStore(Request $request)
    {

        if (isset($request->storage_setting) && $request->storage_setting == 'local') {

            $request->validate(
                [

                    'local_storage_validation' => 'required',
                    'local_storage_max_upload_size' => 'required',
                ]
            );

            $post['storage_setting'] = $request->storage_setting;
            $local_storage_validation = implode(',', $request->local_storage_validation);
            $post['local_storage_validation'] = $local_storage_validation;
            $post['local_storage_max_upload_size'] = $request->local_storage_max_upload_size;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 's3') {
            $request->validate(
                [
                    's3_key' => 'required',
                    's3_secret' => 'required',
                    's3_region' => 'required',
                    's3_bucket' => 'required',
                    's3_url' => 'required',
                    's3_endpoint' => 'required',
                    's3_max_upload_size' => 'required',
                    's3_storage_validation' => 'required',
                ]
            );
            $post['storage_setting'] = $request->storage_setting;
            $post['s3_key'] = $request->s3_key;
            $post['s3_secret'] = $request->s3_secret;
            $post['s3_region'] = $request->s3_region;
            $post['s3_bucket'] = $request->s3_bucket;
            $post['s3_url'] = $request->s3_url;
            $post['s3_endpoint'] = $request->s3_endpoint;
            $post['s3_max_upload_size'] = $request->s3_max_upload_size;
            $s3_storage_validation = implode(',', $request->s3_storage_validation);
            $post['s3_storage_validation'] = $s3_storage_validation;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 'wasabi') {
            $request->validate(
                [
                    'wasabi_key' => 'required',
                    'wasabi_secret' => 'required',
                    'wasabi_region' => 'required',
                    'wasabi_bucket' => 'required',
                    'wasabi_url' => 'required',
                    'wasabi_root' => 'required',
                    'wasabi_max_upload_size' => 'required',
                    'wasabi_storage_validation' => 'required',
                ]
            );
            $post['storage_setting'] = $request->storage_setting;
            $post['wasabi_key'] = $request->wasabi_key;
            $post['wasabi_secret'] = $request->wasabi_secret;
            $post['wasabi_region'] = $request->wasabi_region;
            $post['wasabi_bucket'] = $request->wasabi_bucket;
            $post['wasabi_url'] = $request->wasabi_url;
            $post['wasabi_root'] = $request->wasabi_root;
            $post['wasabi_max_upload_size'] = $request->wasabi_max_upload_size;
            $wasabi_storage_validation = implode(',', $request->wasabi_storage_validation);
            $post['wasabi_storage_validation'] = $wasabi_storage_validation;
        }

        foreach ($post as $key => $data) {

            $arr = [
                $data,
                $key,
                Auth::user()->id,
            ];

            DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success', 'Storage setting successfully updated.');
    }

    public function CookieConsent(Request $request)
    {

        $settings = Utility::settings();

        if ($settings['enable_cookie'] == "on" && $settings['cookie_logging'] == "on") {
            $allowed_levels = ['necessary', 'analytics', 'targeting'];
            $levels = array_filter($request['cookie'], function ($level) use ($allowed_levels) {
                return in_array($level, $allowed_levels);
            });
            $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
            // Generate new CSV line
            $browser_name = $whichbrowser->browser->name ?? null;
            $os_name = $whichbrowser->os->name ?? null;
            $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
            $device_type = Utility::get_device_type($_SERVER['HTTP_USER_AGENT']);


            $ip = '49.36.83.154';
            $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));

            $date = (new \DateTime())->format('Y-m-d');
            $time = (new \DateTime())->format('H:i:s') . ' UTC';

            $new_line = implode(',', [
                $ip, $date, $time, json_encode($request['cookie']), $device_type, $browser_language, $browser_name, $os_name,
                isset($query) ? $query['country'] : '', isset($query) ? $query['region'] : '', isset($query) ? $query['regionName'] : '', isset($query) ? $query['city'] : '', isset($query) ? $query['zip'] : '', isset($query) ? $query['lat'] : '', isset($query) ? $query['lon'] : ''
            ]);

            if (!file_exists(storage_path() . '/uploads/sample/data.csv')) {

                $first_line = 'IP,Date,Time,Accepted cookies,Device type,Browser language,Browser name,OS Name,Country,Region,RegionName,City,Zipcode,Lat,Lon';
                file_put_contents(storage_path() . '/uploads/sample/data.csv', $first_line . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
            file_put_contents(storage_path() . '/uploads/sample/data.csv', $new_line . PHP_EOL, FILE_APPEND | LOCK_EX);

            return response()->json('success');
        }
        return response()->json('error');
    }

    public function saveCookieSettings(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cookie_title' => 'required',
                'cookie_description' => 'required',
                'strictly_cookie_title' => 'required',
                'strictly_cookie_description' => 'required',
                'more_information_title' => 'required',
                'contactus_url' => 'required',
            ]
        );

        $post = $request->all();

        unset($post['_token']);

        if ($request->enable_cookie) {
            $post['enable_cookie'] = 'on';
        } else {
            $post['enable_cookie'] = 'off';
        }
        if ($request->cookie_logging) {
            $post['cookie_logging'] = 'on';
        } else {

            $post['cookie_logging'] = 'off';
        }

        if ($post['enable_cookie'] == 'on') {

            $post['cookie_title'] = $request->cookie_title;
            $post['cookie_description'] = $request->cookie_description;
            $post['strictly_cookie_title'] = $request->strictly_cookie_title;
            $post['strictly_cookie_description'] = $request->strictly_cookie_description;
            $post['more_information_title'] = $request->more_information_title;
            $post['contactus_url'] = $request->contactus_url;
        }
        $settings = Utility::cookies();

        foreach ($post as $key => $data) {

            if (in_array($key, array_keys($settings))) {

                DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $data,
                        $key,
                        Auth::user()->creatorId(),
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Cookie setting successfully saved.');
    }

    public function saveEmailSettings(Request $request)
    {
        if (Auth::user()->can('manage system settings')) {
            $request->validate([
                'mail_driver' => 'required|string|max:255',
                'mail_host' => 'required|string|max:255',
                'mail_port' => 'required|string|max:255',
                'mail_username' => 'required|string|max:255',
                'mail_password' => 'required|string|max:255',
                'mail_encryption' => 'required|string|max:255',
                'mail_from_address' => 'required|string|max:255',
                'mail_from_name' => 'required|string|max:255',
            ]);

            $arrEnv = [
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_NAME' => $request->mail_from_name,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            ];
            Utility::setEnvironmentValue($arrEnv);

            Artisan::call('config:cache');
            Artisan::call('config:clear');

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function saveCompanyEmailSettings(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'mail_driver' => 'required|string|max:255',
                'mail_host' => 'required|string|max:255',
                'mail_port' => 'required|string|max:255',
                'mail_username' => 'required|string|max:255',
                'mail_password' => 'required|string|max:255',
                'mail_encryption' => 'required|string|max:255',
                'mail_from_address' => 'required|string|max:255',
                'mail_from_name' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $post = $request->all();
        unset($post['_token']);
        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                Auth::user()->id,
            ];

            DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success', __('Setting successfully updated.'));
    }

    public function testMail(Request $request)
    {

        $user = Auth::user();

        $data = [];
        $data['mail_driver'] = $request->mail_driver;
        $data['mail_host'] = $request->mail_host;
        $data['mail_port'] = $request->mail_port;
        $data['mail_username'] = $request->mail_username;
        $data['mail_password'] = $request->mail_password;
        $data['mail_encryption'] = $request->mail_encryption;
        $data['mail_from_address'] = $request->mail_from_address;
        $data['mail_from_name'] = $request->mail_from_name;

        return view('settings.test_mail', compact('data'));
    }

    public function testSendMail(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'mail_driver' => 'required',
                'mail_host' => 'required',
                'mail_port' => 'required',
                'mail_username' => 'required',
                'mail_password' => 'required',
                'mail_from_address' => 'required',
                'mail_from_name' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        try {
            config(
                [
                    'mail.driver' => $request->mail_driver,
                    'mail.host' => $request->mail_host,
                    'mail.port' => $request->mail_port,
                    'mail.encryption' => $request->mail_encryption,
                    'mail.username' => $request->mail_username,
                    'mail.password' => $request->mail_password,
                    'mail.from.address' => $request->mail_from_address,
                    'mail.from.name' => $request->mail_from_name,
                ]
            );
            Mail::to($request->email)->send(new testMail());

            return response()->json(
                [
                    'is_success' => true,
                    'message' => __('Email send Successfully'),
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }

    public function SeoSettings(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'meta_keywords' => 'required',
                'meta_description' => 'required',
                'meta_image' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        if (!empty($request->meta_image)) {
            if ($request->meta_image) {

                $path = storage_path('uploads/metaevent/' . Utility::getSeoSetting()['meta_image']);

                if (!empty($path)) {
                    if (file_exists($path)) {
                        File::delete($path);
                    }
                }
            }

            $img_name = time() . '_' . 'meta_image.png';
            $dir = 'uploads/metaevent';
            $validation = [
                'max:' . '20480',
            ];

            $path = Utility::upload_file($request, 'meta_image', $img_name, $dir, $validation);

            if ($path['flag'] == 1) {
                $logo_dark = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }

            $post['meta_image'] = $img_name;
        }

        $post['meta_keywords'] = $request->meta_keywords;
        $post['meta_description'] = $request->meta_description;

        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                Auth::user()->id,
            ];

            DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success', 'SEO setting successfully updated.');
    }

    public function savePaymentSettings(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'site_currency' => 'required',
                'site_currency_symbol' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $post['site_currency'] = $request->site_currency;
        $post['site_currency_symbol'] = $request->site_currency_symbol;

        if (isset($request->is_stripe_enabled) && $request->is_stripe_enabled == 'on') {

            $request->validate([
                'stripe_key' => 'required|string|max:255',
                'stripe_secret' => 'required|string|max:255',
            ]);

            $post['is_stripe_enabled'] = $request->is_stripe_enabled;
            $post['stripe_secret'] = $request->stripe_secret;
            $post['stripe_key'] = $request->stripe_key;
        } else {
            $post['is_stripe_enabled'] = 'off';
        }

        if (isset($request->is_paypal_enabled) && $request->is_paypal_enabled == 'on') {
            $request->validate([
                'paypal_mode' => 'required',
                'paypal_client_id' => 'required',
                'paypal_secret_key' => 'required',
            ]);

            $post['is_paypal_enabled'] = $request->is_paypal_enabled;
            $post['paypal_mode'] = $request->paypal_mode;
            $post['paypal_client_id'] = $request->paypal_client_id;
            $post['paypal_secret_key'] = $request->paypal_secret_key;
        } else {
            $post['is_paypal_enabled'] = 'off';
        }

        if (isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on') {
            $request->validate([
                'paystack_public_key' => 'required|string',
                'paystack_secret_key' => 'required|string',
            ]);
            $post['is_paystack_enabled'] = $request->is_paystack_enabled;
            $post['paystack_public_key'] = $request->paystack_public_key;
            $post['paystack_secret_key'] = $request->paystack_secret_key;
        } else {
            $post['is_paystack_enabled'] = 'off';
        }

        if (isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on') {
            $request->validate([
                'flutterwave_public_key' => 'required|string',
                'flutterwave_secret_key' => 'required|string',
            ]);
            $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
            $post['flutterwave_public_key'] = $request->flutterwave_public_key;
            $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
        } else {
            $post['is_flutterwave_enabled'] = 'off';
        }
        if (isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on') {
            $request->validate([
                'razorpay_public_key' => 'required|string',
                'razorpay_secret_key' => 'required|string',
            ]);
            $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
            $post['razorpay_public_key'] = $request->razorpay_public_key;
            $post['razorpay_secret_key'] = $request->razorpay_secret_key;
        } else {
            $post['is_razorpay_enabled'] = 'off';
        }

        if (isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on') {
            $request->validate(
                [
                    'mercado_mode' => 'required',
                    'mercado_access_token' => 'required|string',
                ]
            );

            $post['is_mercado_enabled'] = $request->is_mercado_enabled;
            $post['mercado_mode'] = $request->mercado_mode;
            $post['mercado_access_token'] = $request->mercado_access_token;
        } else {
            $post['is_mercado_enabled'] = 'off';
        }

        if (isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on') {
            $request->validate([
                'paytm_mode' => 'required',
                'paytm_merchant_id' => 'required|string',
                'paytm_merchant_key' => 'required|string',
                'paytm_industry_type' => 'required|string',
            ]);
            $post['is_paytm_enabled'] = $request->is_paytm_enabled;
            $post['paytm_mode'] = $request->paytm_mode;
            $post['paytm_merchant_id'] = $request->paytm_merchant_id;
            $post['paytm_merchant_key'] = $request->paytm_merchant_key;
            $post['paytm_industry_type'] = $request->paytm_industry_type;
        } else {
            $post['is_paytm_enabled'] = 'off';
        }
        if (isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on') {
            $request->validate([
                'mollie_api_key' => 'required|string',
                'mollie_profile_id' => 'required|string',
                'mollie_partner_id' => 'required',
            ]);
            $post['is_mollie_enabled'] = $request->is_mollie_enabled;
            $post['mollie_api_key'] = $request->mollie_api_key;
            $post['mollie_profile_id'] = $request->mollie_profile_id;
            $post['mollie_partner_id'] = $request->mollie_partner_id;
        } else {
            $post['is_mollie_enabled'] = 'off';
        }

        if (isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on') {
            $request->validate([
                'skrill_email' => 'required|email',
            ]);
            $post['is_skrill_enabled'] = $request->is_skrill_enabled;
            $post['skrill_email'] = $request->skrill_email;
        } else {
            $post['is_skrill_enabled'] = 'off';
        }

        if (isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on') {
            $request->validate([
                'coingate_mode' => 'required|string',
                'coingate_auth_token' => 'required|string',
            ]);

            $post['is_coingate_enabled'] = $request->is_coingate_enabled;
            $post['coingate_mode'] = $request->coingate_mode;
            $post['coingate_auth_token'] = $request->coingate_auth_token;
        } else {
            $post['is_coingate_enabled'] = 'off';
        }

        if (isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on') {
            $request->validate([
                'is_paymentwall_enabled' => 'required|string',
                'paymentwall_public_key' => 'required|string',
                'paymentwall_private_key' => 'required|string',
            ]);

            $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
            $post['paymentwall_public_key'] = $request->paymentwall_public_key;
            $post['paymentwall_private_key'] = $request->paymentwall_private_key;
        } else {
            $post['is_paymentwall_enabled'] = 'off';
        }

        if (isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on') {
            $request->validate([
                'is_toyyibpay_enabled' => 'required|string',
                'toyyibpay_secret_key' => 'required|string',
                'category_code' => 'required|string',
            ]);

            $post['is_toyyibpay_enabled'] = $request->is_toyyibpay_enabled;
            $post['toyyibpay_secret_key'] = $request->toyyibpay_secret_key;
            $post['category_code'] = $request->category_code;
        } else {
            $post['is_toyyibpay_enabled'] = 'off';
        }
        if (isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'payfast_mode' => 'required',
                    'payfast_merchant_id' => 'required|string',
                    'payfast_merchant_key' => 'required|string',
                    'payfast_signature' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_payfast_enabled'] = $request->is_payfast_enabled;
            $post['payfast_mode'] = $request->payfast_mode;
            $post['payfast_merchant_id'] = $request->payfast_merchant_id;
            $post['payfast_merchant_key'] = $request->payfast_merchant_key;
            $post['payfast_signature'] = $request->payfast_signature;
        } else {
            $post['is_payfast_enabled'] = 'off';
        }

        if (isset($request->is_bank_enabled) && $request->is_bank_enabled == 'on') {
            $post['is_bank_enabled'] = $request->is_bank_enabled;
            $post['bank_details'] = $request->bank_details;
        } else {
            $post['is_bank_enabled'] = 'off';
        }

        if (isset($request->is_iyzipay_enabled) && $request->is_iyzipay_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'iyzipay_mode' => 'required',
                    'iyzipay_key' => 'required|string',
                    'iyzipay_secret' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_iyzipay_enabled'] = $request->is_iyzipay_enabled;
            $post['iyzipay_mode'] = $request->iyzipay_mode;
            $post['iyzipay_key']       = $request->iyzipay_key;
            $post['iyzipay_secret'] = $request->iyzipay_secret;
        } else {
            $post['is_iyzipay_enabled'] = 'off';
        }

        if (isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'sspay_secret_key' => 'required|string',
                    'sspay_category_code' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_sspay_enabled'] = $request->is_sspay_enabled;
            $post['sspay_secret_key'] = $request->sspay_secret_key;
            $post['sspay_category_code']       = $request->sspay_category_code;
        } else {
            $post['is_sspay_enabled'] = 'off';
        }
        if (isset($request->is_paytab_enabled) && $request->is_paytab_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_paytab_enabled' => 'required',
                    'paytab_profile_id' => 'required|string',
                    'paytab_server_key' => 'required|string',
                    'paytab_region' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $post['is_paytab_enabled'] = $request->is_paytab_enabled;
            $post['paytab_profile_id'] = $request->paytab_profile_id;
            $post['paytab_server_key'] = $request->paytab_server_key;
            $post['paytab_region'] = $request->paytab_region;
        } else {
            $post['is_paytab_enabled'] = 'off';
        }

        if (isset($request->is_cashfree_enabled) && $request->is_cashfree_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_cashfree_enabled' => 'required',
                    'cashfree_api_key' => 'required|string',
                    'cashfree_secret_key' => 'required|string',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_cashfree_enabled'] = $request->is_cashfree_enabled;
            $post['cashfree_api_key'] = $request->cashfree_api_key;
            $post['cashfree_secret_key'] = $request->cashfree_secret_key;
        } else {
            $post['is_cashfree_enabled'] = 'off';
        }

        if (isset($request->is_benefit_enabled) && $request->is_benefit_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'is_benefit_enabled' => 'required',
                    'benefit_api_key' => 'required|string',
                    'benefit_secret_key' => 'required|string',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_benefit_enabled'] = $request->is_benefit_enabled;
            $post['benefit_api_key'] = $request->benefit_api_key;
            $post['benefit_secret_key'] = $request->benefit_secret_key;
        } else {
            $post['is_benefit_enabled'] = 'off';
        }
        if (isset($request->is_aamarpay_enabled) && $request->is_aamarpay_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_aamarpay_enabled' => 'required',
                    'aamarpay_store_id' => 'required|string',
                    'aamarpay_signature_key' => 'required|string',
                    'aamarpay_description' => 'required|string',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_aamarpay_enabled'] = $request->is_aamarpay_enabled;
            $post['aamarpay_store_id'] = $request->aamarpay_store_id;
            $post['aamarpay_signature_key'] = $request->aamarpay_signature_key;
            $post['aamarpay_description'] = $request->aamarpay_description;
        } else {
            $post['is_aamarpay_enabled'] = 'off';
        }

        if (isset($request->is_paytr_enabled) && $request->is_paytr_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_paytr_enabled' => 'required',
                    'paytr_merchant_id' => 'required',
                    'paytr_merchant_key' => 'required',
                    'paytr_merchant_salt' => 'required',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paytr_enabled'] = $request->is_paytr_enabled;
            $post['paytr_merchant_id'] = $request->paytr_merchant_id;
            $post['paytr_merchant_key'] = $request->paytr_merchant_key;
            $post['paytr_merchant_salt'] = $request->paytr_merchant_salt;
        } else {
            $post['is_paytr_enabled'] = 'off';
        }

        if (isset($request->is_yookassa_enabled) && $request->is_yookassa_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_yookassa_enabled' => 'required',
                    'yookassa_shop_id' => 'required',
                    'yookassa_secret' => 'required',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_yookassa_enabled'] = $request->is_yookassa_enabled;
            $post['yookassa_shop_id'] = $request->yookassa_shop_id;
            $post['yookassa_secret'] = $request->yookassa_secret;
        } else {
            $post['is_yookassa_enabled'] = 'off';
        }

        if (isset($request->is_midtrans_enabled) && $request->is_midtrans_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_midtrans_enabled' => 'required',
                    'midtrans_secret' => 'required',
                    'midtrans_mode' => 'required',


                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_midtrans_enabled'] = $request->is_midtrans_enabled;
            $post['midtrans_mode'] = $request->midtrans_mode;
            $post['midtrans_secret'] = $request->midtrans_secret;
        } else {
            $post['is_midtrans_enabled'] = 'off';
        }

        if (isset($request->is_xendit_enabled) && $request->is_xendit_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_xendit_enabled' => 'required',
                    'xendit_api' => 'required',
                    'xendit_token' => 'required',

                ]
            );
            // 'midtrans_mode' => 'required',

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_xendit_enabled'] = $request->is_xendit_enabled;
            // $post['midtrans_mode'] = $request->midtrans_mode;
            $post['xendit_token'] = $request->xendit_token;
            $post['xendit_api'] = $request->xendit_api;
        } else {
            $post['is_xendit_enabled'] = 'off';
        }

        if (isset($request->is_payhere_enabled) && $request->is_payhere_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'payhere_mode' => 'required',
                    'merchant_id' => 'required',
                    'merchant_secret' => 'required',
                    'payhere_app_id' => 'required',
                    'payhere_app_secret' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_payhere_enabled'] = $request->is_payhere_enabled;
            $post['payhere_mode'] = $request->payhere_mode;
            $post['merchant_id']       = $request->merchant_id;
            $post['merchant_secret'] = $request->merchant_secret;
            $post['payhere_app_id'] = $request->payhere_app_id;
            $post['payhere_app_secret'] = $request->payhere_app_secret;
        } else {
            $post['is_payhere_enabled'] = 'off';
        }

        foreach ($post as $key => $data) {

            $arr = [
                $data,
                $key,
                Auth::user()->id,
            ];
            DB::insert(
                'insert into payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success', __('Payment setting successfully updated.'));
    }

    public function recaptchaSettingStore(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'google_recaptcha_key' => 'required',
                'google_recaptcha_secret' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $post['recaptcha_module']        = $request->recaptcha_module;
        $post['google_recaptcha_key']    = $request->google_recaptcha_key;
        $post['google_recaptcha_secret'] = $request->google_recaptcha_secret;
        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));

    }

    public function adminSettings(Request $request)
    {
        if (Auth::user()->can('manage system settings')) {
            $settings = Utility::settings();
            $payment = Utility::set_payment_settings();

            $file_size = 0;

            foreach (\File::allFiles(storage_path('/framework')) as $file) {
                $file_size += $file->getSize();
            }
            $file_size = number_format($file_size / 1000000, 6);

            $countries = Country::get();

            $country_id = !empty($request->country) ? $request->country : 1;
            $states = State::where('country_id', $country_id)->get()->toArray();

            $state_id = !empty($request->state_id) ? $request->state_id : 1;
            $cities = City::where('region_id', $state_id)->get()->toArray();

            if (!empty($request->state_id) || !empty($request->country)) {
                $filter_data = 'filtered';
            } else {
                $filter_data = null;
            }

            return view('settings.admin', compact('settings', 'file_size', 'payment', 'countries', 'states', 'country_id', 'cities', 'filter_data'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveBusinessSettings(Request $request)
    {
        if (Auth::user()->can('manage system settings')) {
            $user = Auth::user();
            if ($request->company_logo_dark) {

                $request->validate(
                    [
                        'company_logo_dark' => 'image',
                    ]
                );

                $logoName = $user->id . '-logo-dark.png';
                $dir = 'uploads/logo/';

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];

                $path = Utility::upload_file($request, 'company_logo_dark', $logoName, $dir, $validation);

                if ($path['flag'] == 1) {
                    $company_logo_dark = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }

                $company_logo = !empty($request->company_logo_dark) ? $logoName : 'logo-dark.png';

                DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $logoName,
                        'company_logo_dark',
                        Auth::user()->creatorId(),
                    ]
                );
            }

            if ($request->company_logo_light) {

                $request->validate(
                    [
                        'company_logo_light' => 'image',
                    ]
                );

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];

                $logoName = $user->id . '-logo-light.png';
                $dir = 'uploads/logo/';

                $path = Utility::upload_file($request, 'company_logo_light', $logoName, $dir, $validation);
                if ($path['flag'] == 1) {
                    $company_logo_light = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }

                $company_logo = !empty($request->company_logo_light) ? $logoName : 'logo-light.png';

                DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $logoName,
                        'company_logo_light',
                        \Auth::user()->creatorId(),
                    ]
                );
            }

            if ($request->company_favicon) {
                $request->validate(
                    [
                        'company_favicon' => 'image',
                    ]
                );
                $favicon = $user->id . '_favicon.png';
                $dir = 'uploads/logo/';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];

                $path = Utility::upload_file($request, 'company_favicon', $favicon, $dir, $validation);
                $company_favicon = !empty($request->favicon) ? $favicon : 'favicon.png';

                if ($path['flag'] == 1) {
                    $company_favicon = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }

                DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $favicon,
                        'company_favicon',
                        Auth::user()->creatorId(),
                    ]
                );
            }

            $user = Auth::user();

            $settings = Utility::settings();
            if (!empty($request->title_text) || !empty($request->footer_text) || !empty($request->default_language) || isset($request->display_landing_page) || isset($request->color) || isset($request->cust_theme_bg) || isset($request->cust_darklayout) || isset($request->SITE_RTL)  || isset($request->email_verification) || isset($request->signup_button)) {

                $post = $request->all();
                if (!isset($request->display_landing_page)) {
                    $post['display_landing_page'] = 'off';
                }

                if (!isset($request->cust_theme_bg)) {
                    $cust_theme_bg = (isset($request->cust_theme_bg)) ? 'on' : 'off';
                    $post['cust_theme_bg'] = $cust_theme_bg;
                }

                if (!isset($request->SITE_RTL)) {
                    $post['SITE_RTL'] = 'off';
                }

                if (!isset($request->cust_darklayout)) {

                    $cust_darklayout = isset($request->cust_darklayout) ? 'on' : 'off';
                    $post['cust_darklayout'] = $cust_darklayout;
                }

                if (!isset($request->email_verification)) {
                    $post['email_verification'] = 'off';
                }

                if (!isset($request->signup_button)) {
                    $post['signup_button'] = 'off';
                }

                unset($post['_token'], $post['company_logo_dark'], $post['company_logo_light'], $post['company_favicon']);

                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings))) {
                        DB::insert('insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                            $data,
                            $key,
                            Auth::user()->creatorId(),
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Brand setting successfully updated.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function saveAdminPaymentSettings(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make(
            $request->all(),
            [
                'currency' => 'required|string|max:255',
                'currency_symbol' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        } else {

            $post['currency_symbol'] = $request->currency_symbol;
            $post['currency'] = $request->currency;
        }

        if (isset($request->is_stripe_enabled) && $request->is_stripe_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'stripe_key' => 'required|string',
                    'stripe_secret' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_stripe_enabled'] = $request->is_stripe_enabled;
            $post['stripe_secret'] = $request->stripe_secret;
            $post['stripe_key'] = $request->stripe_key;
        } else {
            $post['is_stripe_enabled'] = 'off';
        }
        if (isset($request->is_paypal_enabled) && $request->is_paypal_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'paypal_mode' => 'required|string',
                    'paypal_client_id' => 'required|string',
                    'paypal_secret_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paypal_enabled'] = $request->is_paypal_enabled;
            $post['paypal_mode'] = $request->paypal_mode;
            $post['paypal_client_id'] = $request->paypal_client_id;
            $post['paypal_secret_key'] = $request->paypal_secret_key;
        } else {
            $post['is_paypal_enabled'] = 'off';
        }


        if (isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'paystack_public_key' => 'required|string',
                    'paystack_secret_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paystack_enabled'] = $request->is_paystack_enabled;
            $post['paystack_public_key'] = $request->paystack_public_key;
            $post['paystack_secret_key'] = $request->paystack_secret_key;
        } else {
            $post['is_paystack_enabled'] = 'off';
        }

        if (isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'flutterwave_public_key' => 'required|string',
                    'flutterwave_secret_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
            $post['flutterwave_public_key'] = $request->flutterwave_public_key;
            $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
        } else {
            $post['is_flutterwave_enabled'] = 'off';
        }

        if (isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'razorpay_public_key' => 'required|string',
                    'razorpay_secret_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
            $post['razorpay_public_key'] = $request->razorpay_public_key;
            $post['razorpay_secret_key'] = $request->razorpay_secret_key;
        } else {
            $post['is_razorpay_enabled'] = 'off';
        }

        if (isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on') {
            $request->validate(
                [
                    'mercado_access_token' => 'required|string',
                ]
            );
            $post['is_mercado_enabled'] = $request->is_mercado_enabled;
            $post['mercado_access_token'] = $request->mercado_access_token;
            $post['mercado_mode'] = $request->mercado_mode;
        } else {
            $post['is_mercado_enabled'] = 'off';
        }

        if (isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'paytm_mode' => 'required',
                    'paytm_merchant_id' => 'required|string',
                    'paytm_merchant_key' => 'required|string',
                    'paytm_industry_type' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paytm_enabled'] = $request->is_paytm_enabled;
            $post['paytm_mode'] = $request->paytm_mode;
            $post['paytm_merchant_id'] = $request->paytm_merchant_id;
            $post['paytm_merchant_key'] = $request->paytm_merchant_key;
            $post['paytm_industry_type'] = $request->paytm_industry_type;
        } else {
            $post['is_paytm_enabled'] = 'off';
        }

        if (isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'mollie_api_key' => 'required|string',
                    'mollie_profile_id' => 'required|string',
                    'mollie_partner_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_mollie_enabled'] = $request->is_mollie_enabled;
            $post['mollie_api_key'] = $request->mollie_api_key;
            $post['mollie_profile_id'] = $request->mollie_profile_id;
            $post['mollie_partner_id'] = $request->mollie_partner_id;
        } else {
            $post['is_mollie_enabled'] = 'off';
        }

        if (isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'skrill_email' => 'required|email',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_skrill_enabled'] = $request->is_skrill_enabled;
            $post['skrill_email'] = $request->skrill_email;
        } else {
            $post['is_skrill_enabled'] = 'off';
        }

        if (isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'coingate_mode' => 'required|string',
                    'coingate_auth_token' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_coingate_enabled'] = $request->is_coingate_enabled;
            $post['coingate_mode'] = $request->coingate_mode;
            $post['coingate_auth_token'] = $request->coingate_auth_token;
        } else {
            $post['is_coingate_enabled'] = 'off';
        }

        if (isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'paymentwall_public_key' => 'required|string',
                    'paymentwall_private_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
            $post['paymentwall_public_key'] = $request->paymentwall_public_key;
            $post['paymentwall_private_key'] = $request->paymentwall_private_key;
        } else {
            $post['is_paymentwall_enabled'] = 'off';
        }

        if (isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'toyyibpay_secret_key' => 'required|string',
                    'category_code' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_toyyibpay_enabled'] = $request->is_toyyibpay_enabled;
            $post['toyyibpay_secret_key'] = $request->toyyibpay_secret_key;
            $post['category_code'] = $request->category_code;
        } else {
            $post['is_toyyibpay_enabled'] = 'off';
        }

        if (isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'payfast_mode' => 'required',
                    'payfast_merchant_id' => 'required|string',
                    'payfast_merchant_key' => 'required|string',
                    'payfast_signature' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_payfast_enabled'] = $request->is_payfast_enabled;
            $post['payfast_mode'] = $request->payfast_mode;
            $post['payfast_merchant_id'] = $request->payfast_merchant_id;
            $post['payfast_merchant_key'] = $request->payfast_merchant_key;
            $post['payfast_signature'] = $request->payfast_signature;
        } else {
            $post['is_payfast_enabled'] = 'off';
        }

        if (isset($request->is_manually_enabled) && $request->is_manually_enabled == 'on') {
            $post['is_manually_enabled'] = $request->is_manually_enabled;
        } else {
            $post['is_manually_enabled'] = 'off';
        }
        if (isset($request->is_bank_enabled) && $request->is_bank_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'bank_details' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_bank_enabled'] = $request->is_bank_enabled;
            $post['bank_details'] = $request->bank_details;
        } else {
            $post['is_bank_enabled'] = 'off';
        }
        if (isset($request->is_iyzipay_enabled) && $request->is_iyzipay_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'iyzipay_mode' => 'required',
                    'iyzipay_key' => 'required|string',
                    'iyzipay_secret' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_iyzipay_enabled'] = $request->is_iyzipay_enabled;
            $post['iyzipay_mode'] = $request->iyzipay_mode;
            $post['iyzipay_key']       = $request->iyzipay_key;
            $post['iyzipay_secret'] = $request->iyzipay_secret;
        } else {
            $post['is_iyzipay_enabled'] = 'off';
        }

        if (isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'sspay_secret_key' => 'required|string',
                    'sspay_category_code' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_sspay_enabled'] = $request->is_sspay_enabled;
            $post['sspay_secret_key'] = $request->sspay_secret_key;
            $post['sspay_category_code']       = $request->sspay_category_code;
        } else {
            $post['is_sspay_enabled'] = 'off';
        }
        if (isset($request->is_paytab_enabled) && $request->is_paytab_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_paytab_enabled' => 'required',
                    'paytab_profile_id' => 'required|string',
                    'paytab_server_key' => 'required|string',
                    'paytab_region' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $post['is_paytab_enabled'] = $request->is_paytab_enabled;
            $post['paytab_profile_id'] = $request->paytab_profile_id;
            $post['paytab_server_key'] = $request->paytab_server_key;
            $post['paytab_region'] = $request->paytab_region;
        } else {
            $post['is_paytab_enabled'] = 'off';
        }

        if (isset($request->is_benefit_enabled) && $request->is_benefit_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'is_benefit_enabled' => 'required',
                    'benefit_api_key' => 'required|string',
                    'benefit_secret_key' => 'required|string',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_benefit_enabled'] = $request->is_benefit_enabled;
            $post['benefit_api_key'] = $request->benefit_api_key;
            $post['benefit_secret_key'] = $request->benefit_secret_key;
        } else {
            $post['is_benefit_enabled'] = 'off';
        }
        if (isset($request->is_cashfree_enabled) && $request->is_cashfree_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_cashfree_enabled' => 'required',
                    'cashfree_api_key' => 'required|string',
                    'cashfree_secret_key' => 'required|string',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_cashfree_enabled'] = $request->is_cashfree_enabled;
            $post['cashfree_api_key'] = $request->cashfree_api_key;
            $post['cashfree_secret_key'] = $request->cashfree_secret_key;
        } else {
            $post['is_cashfree_enabled'] = 'off';
        }

        if (isset($request->is_aamarpay_enabled) && $request->is_aamarpay_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_aamarpay_enabled' => 'required',
                    'aamarpay_store_id' => 'required|string',
                    'aamarpay_signature_key' => 'required|string',
                    'aamarpay_description' => 'required|string',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_aamarpay_enabled'] = $request->is_aamarpay_enabled;
            $post['aamarpay_store_id'] = $request->aamarpay_store_id;
            $post['aamarpay_signature_key'] = $request->aamarpay_signature_key;
            $post['aamarpay_description'] = $request->aamarpay_description;
        } else {
            $post['is_aamarpay_enabled'] = 'off';
        }

        if (isset($request->is_paytr_enabled) && $request->is_paytr_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_paytr_enabled' => 'required',
                    'paytr_merchant_id' => 'required',
                    'paytr_merchant_key' => 'required',
                    'paytr_merchant_salt' => 'required',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paytr_enabled'] = $request->is_paytr_enabled;
            $post['paytr_merchant_id'] = $request->paytr_merchant_id;
            $post['paytr_merchant_key'] = $request->paytr_merchant_key;
            $post['paytr_merchant_salt'] = $request->paytr_merchant_salt;
        } else {
            $post['is_paytr_enabled'] = 'off';
        }

        if (isset($request->is_yookassa_enabled) && $request->is_yookassa_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_yookassa_enabled' => 'required',
                    'yookassa_shop_id' => 'required',
                    'yookassa_secret' => 'required',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_yookassa_enabled'] = $request->is_yookassa_enabled;
            $post['yookassa_shop_id'] = $request->yookassa_shop_id;
            $post['yookassa_secret'] = $request->yookassa_secret;
        } else {
            $post['is_yookassa_enabled'] = 'off';
        }
        if (isset($request->is_midtrans_enabled) && $request->is_midtrans_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_midtrans_enabled' => 'required',
                    'midtrans_secret' => 'required',
                    'midtrans_mode' => 'required',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_midtrans_enabled'] = $request->is_midtrans_enabled;
            $post['midtrans_mode'] = $request->midtrans_mode;
            $post['midtrans_secret'] = $request->midtrans_secret;
        } else {
            $post['is_midtrans_enabled'] = 'off';
        }
        if (isset($request->is_xendit_enabled) && $request->is_xendit_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_xendit_enabled' => 'required',
                    'xendit_api' => 'required',
                    'xendit_token' => 'required',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_xendit_enabled'] = $request->is_xendit_enabled;
            $post['xendit_token'] = $request->xendit_token;
            $post['xendit_api'] = $request->xendit_api;
        } else {
            $post['is_xendit_enabled'] = 'off';
        }

        if (isset($request->is_payhere_enabled) && $request->is_payhere_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'payhere_mode' => 'required',
                    'merchant_id' => 'required',
                    'merchant_secret' => 'required',
                    'payhere_app_id' => 'required',
                    'payhere_app_secret' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_payhere_enabled'] = $request->is_payhere_enabled;
            $post['payhere_mode'] = $request->payhere_mode;
            $post['merchant_id']       = $request->merchant_id;
            $post['merchant_secret'] = $request->merchant_secret;
            $post['payhere_app_id'] = $request->payhere_app_id;
            $post['payhere_app_secret'] = $request->payhere_app_secret;
        } else {
            $post['is_payhere_enabled'] = 'off';
        }

        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                Auth::user()->id,
            ];

            $bdfuv = DB::insert(
                'insert into admin_payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }

    public function savePusherSettings(Request $request)
    {
        if (\Auth::user()->type == 'super admin') {

            $validator = Validator::make(
                $request->all(),
                [
                    'pusher_app_id' => 'required',
                    'pusher_app_key' => 'required',
                    'pusher_app_secret' => 'required',
                    'pusher_app_cluster' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['pusher_app_id']        = $request->pusher_app_id;
            $post['pusher_app_key']    = $request->pusher_app_key;
            $post['pusher_app_secret'] = $request->pusher_app_secret;
            $post['pusher_app_cluster'] = $request->pusher_app_cluster;

            foreach ($post as $key => $data) {
                $arr = [
                    $data,
                    $key,
                    \Auth::user()->id,
                ];
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    $arr
                );
            }

            return redirect()->back()->with('success', __('Pusher Settings updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveGoogleCalenderSettings(Request $request)
    {

        if (isset($request->is_enabled) && $request->is_enabled == 'on') {

            $validator = Validator::make(
                $request->all(),
                [
                    'google_clender_id' => 'required',
                    'google_calender_json_file' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_enabled'] = $request->is_enabled;
        } else {
            $post['is_enabled'] = 'off';
        }


        if ($request->google_calender_json_file) {
            $dir       = storage_path() . '/' . md5(time());
            if (!is_dir($dir)) {
                File::makeDirectory($dir, $mode = 0777, true, true);
            }
            $file_name = $request->google_calender_json_file->getClientOriginalName();
            $file_path =  md5(time()) . "/" . md5(time()) . "." . $request->google_calender_json_file->getClientOriginalExtension();

            $file = $request->file('google_calender_json_file');
            $file->move($dir, $file_path);
            $post['google_calender_json_file']            = $file_path;
        }

        if ($request->google_clender_id) {
            $post['google_clender_id']            = $request->google_clender_id;

            foreach ($post as $key => $data) {
                DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $data,
                        $key,
                        Auth::user()->id,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Google Calendar setting successfully updated.');
    }
}

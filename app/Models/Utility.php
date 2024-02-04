<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\GoogleCalendar\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class Utility extends Model
{
    use HasFactory;
    private static $settings = null;
    private static $languages = null;

    public static function settings($user_id = null)
    {
        if (self::$settings === null) {
            self::$settings = self::fetchSettings($user_id);
        }

        return self::$settings;
    }

    private static function fetchSettings($user_id = null){
        if (Auth::check()) {
            $user_id = Auth::user()->creatorId();
        }

        $data = DB::table('settings')
            ->where('created_by', $user_id)
            ->orWhere('created_by', 1)
            ->get();

        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "footer_text" => "",
            "title_text" => "",
            "SITE_RTL" => "",
            "display_landing_page" => "on",
            "color" => "theme-1",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "default_language" => "en",
            "decimal_number" => "2",
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png",
            "local_storage_max_upload_size" => "",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url" => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
            "company_logo_light" => "logo-light.png",
            "company_logo_dark" => "logo-dark.png",
            "company_favicon" => "",
            "employee_prefix" => "#EMP00",
            'enable_cookie' => 'on',
            'necessary_cookies' => 'on',
            'cookie_logging' => 'on',
            'cookie_title' => 'We use cookies!',
            'cookie_description' => 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
            'strictly_cookie_title' => 'Strictly necessary cookies',
            'strictly_cookie_description' => 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
            'more_information_description' => 'For any queries in relation to our policy on cookies and your choices, please contact us',
            "more_information_title" => "",
            'contactus_url' => '#',
            "signup_button" => "on",
            "email_verification" => "on",
            "disable_lang" => "",
            "meta_keywords" => "",
            "meta_image" => "",
            "meta_description" => "",
            "google_recaptcha_secret" => "",
            "google_recaptcha_key" => "",
            "recaptcha_module" => "off",
            'pusher_app_id' => '',
            'pusher_app_key' => '',
            'pusher_app_secret' => '',
            'pusher_app_cluster' => '',
            'resolve_status' => '',
            'FAQ' => 'on',
            'Knowlwdge_Base' => 'on',
            'mail_driver' => '',
            'mail_host' => '',
            'mail_port' => '',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => '',
            'mail_from_address' => '',
            'mail_from_name' => '',
            'display_landing_page' => 'on',
        ];

        // Convert the retrieved settings into an associative array
        $settingsFromDB = $data->pluck('value', 'name')->all();

        // Merge the settings from the database with the default settings
        $settings = array_merge($settings, $settingsFromDB);

        // recaptch
        config(
                [
                    'captcha.secret' => $settings['google_recaptcha_secret'],
                    'captcha.sitekey' => $settings['google_recaptcha_key'],
                    'options' => [
                        'timeout' => 30,
                    ],
                ]
        );
        return $settings;

    }

    public static function getValByName($key)
    {
        $setting = Utility::settings();
        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }

        return $setting[$key];
    }

    public static function get_file($path)
    {
        $settings = Utility::settings();

        try {
            if ($settings['storage_setting'] == 'wasabi') {
                config(
                    [
                        'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                        'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                        'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                        'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                        'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com',
                    ]
                );
            } elseif ($settings['storage_setting'] == 's3') {
                config(
                    [
                        'filesystems.disks.s3.key' => $settings['s3_key'],
                        'filesystems.disks.s3.secret' => $settings['s3_secret'],
                        'filesystems.disks.s3.region' => $settings['s3_region'],
                        'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                        'filesystems.disks.s3.use_path_style_endpoint' => false,
                    ]
                );
            }

            return Storage::disk($settings['storage_setting'])->url($path);
        } catch (\Throwable $th) {
            return '';
        }
    }



    // cache module
    public static function GetCacheSize()
    {
        $file_size = 0;
        foreach (File::allFiles(storage_path('/framework')) as $file) {
            $file_size += $file->getSize();
        }
        $file_size = number_format($file_size / 1000000, 4);
        return $file_size;
    }

    public static function mode_layout()
    {
        $data = DB::table('settings')->where('created_by', Auth::check() ? Auth::user()->creatorId() : 1)->get();

        $settings = [
            "cust_darklayout" => "off",
            "cust_theme_bg" => "on",
            "color" => '',
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;


    }

    public static function get_company_logo()
    {
        $is_dark_mode = self::getValByName('cust_darklayout');

        if ($is_dark_mode == 'on') {

            return Utility::getValByName('company_logo_light');
        } else {
            return Utility::getValByName('company_logo_dark');
        }
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }
    public static function colorset()
    {
        if (Auth::user()) {
            $setting = DB::table('settings')->where('created_by', Auth::user()->creatorId())->pluck('value', 'name')->toArray();
        }

        if (!isset($setting['color'])) {
            $setting = Utility::settings();
        }
        return $setting;
    }
    public static function getSeoSetting()
    {
        $data = DB::table('settings')
            ->where('created_by', 1)
            ->get();

        $settings = [
            "meta_keywords" => "",
            "meta_image" => "",
            "meta_description" => "",
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;

    }
    public static function upload_file($request, $key_name, $name, $path, $custom_validation = [])
    {
        try {
            $settings = Utility::getStorageSetting();

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com',
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes = !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';

                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes = !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';

                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes = !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }

                $file = $request->$key_name;

                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];

                }
                $validator = Validator::make($request->all(), [
                    $key_name => $validation,
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {

                        Storage::disk()->putFileAs(
                            $path,
                            $request->file($key_name),
                            $name
                        );
                        $path = $path . $name;
                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                    } else if ($settings['storage_setting'] == 's3') {

                        $path = Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );
                    }

                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $path,
                    ];
                    return $res;
                }

            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }

        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }
    public static function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

    public static function cookies()
    {
        $data = DB::table('settings');
        if (Auth::check()) {
            $userId = Auth::user()->creatorId();
            $data = $data->where('created_by', '=', $userId);
        } else {
            $data = $data->where('created_by', '=', 1);
        }
        $data = $data->get();
        $cookies = [
            'enable_cookie' => 'on',
            'necessary_cookies' => 'on',
            'cookie_logging' => 'on',
            'cookie_title' => 'We use cookies!',
            'cookie_description' => 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
            'strictly_cookie_title' => 'Strictly necessary cookies',
            'strictly_cookie_description' => 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
            'more_information_description' => 'For any queries in relation to our policy on cookies and your choices, please contact us',
            "more_information_title" => "",
            'contactus_url' => '#',
        ];
        foreach ($data as $key => $row) {
            if (in_array($row->name, $cookies)) {
                $cookies[$row->name] = $row->value;
            }
        }
        return $cookies;
    }
    public static function getStorageSetting()
    {

        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1);
        $data = $data->get();

        $settings = [
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png",
            "local_storage_max_upload_size" => "",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url" => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }
    public static function getYears()
    {

        $years = [];
        $years['1950'] = '1950';
        $years['1951'] = '1951';
        $years['1952'] = '1952';
        $years['1953'] = '1953';
        $years['1954'] = '1954';
        $years['1955'] = '1955';
        $years['1956'] = '1956';
        $years['1957'] = '1957';
        $years['1958'] = '1958';
        $years['1959'] = '1959';
        $years['1960'] = '1960';
        $years['1961'] = '1961';
        $years['1962'] = '1962';
        $years['1963'] = '1963';
        $years['1964'] = '1964';
        $years['1965'] = '1965';
        $years['1966'] = '1966';
        $years['1967'] = '1967';
        $years['1968'] = '1968';
        $years['1969'] = '1969';
        $years['1970'] = '1970';
        $years['1971'] = '1971';
        $years['1972'] = '1972';
        $years['1973'] = '1973';
        $years['1974'] = '1974';
        $years['1975'] = '1975';
        $years['1976'] = '1976';
        $years['1977'] = '1977';
        $years['1978'] = '1978';
        $years['1979'] = '1979';
        $years['1980'] = '1980';
        $years['1981'] = '1981';
        $years['1982'] = '1982';
        $years['1983'] = '1983';
        $years['1984'] = '1984';
        $years['1985'] = '1985';
        $years['1986'] = '1986';
        $years['1987'] = '1987';
        $years['1988'] = '1988';
        $years['1989'] = '1989';
        $years['1990'] = '1990';
        $years['1991'] = '1991';
        $years['1992'] = '1992';
        $years['1993'] = '1993';
        $years['1994'] = '1994';
        $years['1995'] = '1995';
        $years['1996'] = '1996';
        $years['1997'] = '1997';
        $years['1998'] = '1998';
        $years['1999'] = '1999';
        $years['2001'] = '2001';
        $years['2002'] = '2002';
        $years['2003'] = '2003';
        $years['2004'] = '2004';
        $years['2005'] = '2005';
        $years['2006'] = '2006';
        $years['2007'] = '2007';
        $years['2008'] = '2008';
        $years['2009'] = '2009';
        $years['2010'] = '2010';
        $years['2011'] = '2011';
        $years['2012'] = '2012';
        $years['2013'] = '2013';
        $years['2014'] = '2014';
        $years['2015'] = '2015';
        $years['2017'] = '2017';
        $years['2018'] = '2018';
        $years['2019'] = '2019';
        $years['2020'] = '2020';
        $years['2021'] = '2021';
        $years['2022'] = '2022';
        $years['2023'] = '2023';
        $years['2024'] = '2024';
        $years['2025'] = '2025';
        $years['2026'] = '2026';
        $years['2027'] = '2027';
        $years['2028'] = '2028';
        $years['2029'] = '2029';
        $years['2030'] = '2030';

        return $years;
    }

    public static function getCompanyPaymentSetting($user_id)
    {

        $data = DB::table('payment_settings');
        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
        ];
        $data = $data->where('created_by', '=', $user_id)->get();

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function invoiceNumberFormat($settings, $number)
    {
        $settings = Utility::settings();
        return '#' . sprintf("%05d", $number);
    }

    public static function get_superadmin_logo()
    {
        $is_dark_mode = self::getValByName('cust_darklayout');
        $setting = DB::table('settings')->where('created_by', '1')->pluck('value', 'name')->toArray();
        $is_dark_mode = isset($setting['cust_darklayout']) ? $setting['cust_darklayout'] : $is_dark_mode;

        if (Auth::user() && Auth::user()->type != 'super admin') {
            if ($is_dark_mode == 'on') {
                return Utility::getValByName('company_logo_light');
            } else {
                return Utility::getValByName('company_logo_dark');
            }
        } else {
            if ($is_dark_mode == 'on') {
                return 'logo-light.png';
            } else {
                return 'logo-dark.png';
            }
        }
    }

    public static function set_payment_settings()
    {
        $data = DB::table('admin_payment_settings');

        if (Auth::check()) {
            $data->where('created_by', '=', Auth::user()->creatorId());
        } else {
            $data->where('created_by', '=', 1);
        }
        $data = $data->get();

        $res = [];
        foreach ($data as $key => $value) {
            $res[$value->name] = $value->value;
        }

        return $res;
    }

    public static function payment_settings()
    {
        $data = DB::table('admin_payment_settings');

        $data->where('created_by', '=', 1);

        $data = $data->get();

        $res = [];
        foreach ($data as $key => $value) {
            $res[$value->name] = $value->value;
        }
        return $res;
    }

    public static function getPaymentIsOn()
    {
        $payments = self::set_payment_settings();
        if (isset($payments['is_stripe_enabled']) && $payments['is_stripe_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_paypal_enabled']) && $payments['is_paypal_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_flutterwave_enabled']) && $payments['is_flutterwave_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_razorpay_enabled']) && $payments['is_razorpay_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_mercado_enabled']) && $payments['is_mercado_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_paytm_enabled']) && $payments['is_paytm_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_mollie_enabled']) && $payments['is_mollie_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_skrill_enabled']) && $payments['is_skrill_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_coingate_enabled']) && $payments['is_coingate_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_paystack_enabled']) && $payments['is_paystack_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_paymentwall_enabled']) && $payments['is_paymentwall_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_toyyibpay_enabled']) && $payments['is_toyyibpay_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_payfast_enabled']) && $payments['is_payfast_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_manually_enabled']) && $payments['is_manually_enabled'] == 'on') {
            return true;
        } elseif (isset($payments['is_bank_enabled']) && $payments['is_bank_enabled'] == 'on') {
            return true;
        } else {
            return false;
        }
    }

    public static function getCompanyOnPyaments($payments_data){

        $payTypes = [];
        if ((array_key_exists('is_stripe_enabled', $payments_data)) && ($payments_data['is_stripe_enabled'] == 'on')) {
            $payTypes['Stripe'] = 'Stripe';
        }
        if ((array_key_exists('is_paypal_enabled', $payments_data)) && ($payments_data['is_paypal_enabled'] == 'on')) {
            $payTypes['Paypal'] = 'Paypal';
        }
        if ((array_key_exists('is_paystack_enabled', $payments_data)) && ($payments_data['is_paystack_enabled'] == 'on')) {
            $payTypes['Paystack'] = 'Paystack';
        }
        if ((array_key_exists('is_flutterwave_enabled', $payments_data)) && ($payments_data['is_flutterwave_enabled'] == 'on')) {
            $payTypes['Flutterwave'] = 'Flutterwave';
        }
        if ((array_key_exists('is_razorpay_enabled', $payments_data)) && ($payments_data['is_razorpay_enabled'] == 'on')) {
            $payTypes['Razorpay'] = 'Razorpay';
        }

        if ((array_key_exists('is_mercado_enabled', $payments_data)) && ($payments_data['is_mercado_enabled'] == 'on')) {
            $payTypes['Mercado'] = 'Mercado';
        }

        if ((array_key_exists('is_mollie_enabled', $payments_data)) && ($payments_data['is_mollie_enabled'] == 'on')) {
            $payTypes['Mollie'] = 'Mollie';
        }
        if ((array_key_exists('is_skrill_enabled', $payments_data)) && ($payments_data['is_skrill_enabled'] == 'on')) {
            $payTypes['Skrill'] = 'Skrill';
        }

        if ((array_key_exists('is_coingate_enabled', $payments_data)) && ($payments_data['is_coingate_enabled'] == 'on')) {
            $payTypes['Coingate'] = 'Coingate';
        }

        if ((array_key_exists('is_paytm_enabled', $payments_data)) && ($payments_data['is_paytm_enabled'] == 'on')) {
            $payTypes['Paytm'] = 'Paytm';
        }
        if ((array_key_exists('is_paymentwall_enabled', $payments_data)) && ($payments_data['is_paymentwall_enabled'] == 'on')) {
            $payTypes['Paytm'] = 'Paytm';
        }
        if ((array_key_exists('is_toyyibpay_enabled', $payments_data)) && ($payments_data['is_toyyibpay_enabled'] == 'on')) {
            $payTypes['Paytm'] = 'Paytm';
        }
        if ((array_key_exists('is_payfast_enabled', $payments_data)) && ($payments_data['is_payfast_enabled'] == 'on')) {
            $payTypes['Paytm'] = 'Paytm';
        }
        if ((array_key_exists('is_iyzipay_enabled', $payments_data)) && ($payments_data['is_iyzipay_enabled'] == 'on')) {
            $payTypes['Paytm'] = 'Paytm';
        }
        if ((array_key_exists('is_sspay_enabled', $payments_data)) && ($payments_data['is_sspay_enabled'] == 'on')) {
            $payTypes['Paytm'] = 'Paytm';
        }

        return $payTypes;

    }

    public static function get_device_type($user_agent)
    {
        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
        if(preg_match_all($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {
            if(preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
    }

    public static function addCalendarData($request, $type)
    {


        Self::googleCalendarConfig();

        $event = new Event();
        $event->name = $request->title;

        $event->startDateTime = Carbon::parse($request->start_date);
        $event->endDateTime = Carbon::parse($request->end_date);
        $event->colorId = Self::colorCodeData($type);

        $event->save();
    }

    public static function colorCodeData($type)
    {
        if ($type == 'event') {
            return 1;
        } elseif ($type == 'zoom_meeting') {
            return 2;
        } elseif ($type == 'task') {
            return 3;
        } elseif ($type == 'appointment') {
            return 11;
        } elseif ($type == 'rotas') {
            return 3;
        } elseif ($type == 'holiday') {
            return 4;
        } elseif ($type == 'call') {
            return 10;
        } elseif ($type == 'meeting') {
            return 5;
        } elseif ($type == 'leave') {
            return 6;
        } elseif ($type == 'work_order') {
            return 7;
        } elseif ($type == 'lead') {
            return 7;
        } elseif ($type == 'deal') {
            return 8;
        } elseif ($type == 'interview_schedule') {
            return 9;
        } else {
            return 11;
        }
    }

     public static $colorCode = [
        1 => 'event-warning',
        2 => 'event-secondary',
        3 => 'event-success',
        4 => 'event-warning',
        5 => 'event-danger',
        6 => 'event-dark',
        7 => 'event-black',
        8 => 'event-info',
        9 => 'event-secondary',
        10 => 'event-success',
        11 => 'event-warning',
    ];

    public static function googleCalendarConfig()
    {
        $setting = Utility::settings();
        $path = storage_path($setting['google_calender_json_file']);
        config([
            'google-calendar.default_auth_profile' => 'service_account',
            'google-calendar.auth_profiles.service_account.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.token_json' => $path,
            'google-calendar.calendar_id' => isset($setting['google_clender_id']) ? $setting['google_clender_id'] : '',
            'google-calendar.user_to_impersonate' => '',

        ]);
    }

    public static function getCalendarData($type)
    {
        Self::googleCalendarConfig();
        $data = Event::get();

        $type = Self::colorCodeData($type);

        $arrayJson = [];

        foreach ($data as $val) {

            $end_date = date_create($val->endDateTime);
            date_add($end_date, date_interval_create_from_date_string("1 days"));


            if ($val->colorId == "$type") {

                $arrayJson[] = [
                    "id" => $val->id,
                    "title" => $val->summary,
                    "start" => $val->startDateTime,
                    "end" => date_format($end_date, "Y-m-d H:i:s"),
                    "className" => Self::$colorCode[$type],
                    "allDay" => true,
                ];

            }

        }

        return $arrayJson;
    }
    public static function flagOfCountry(){
        $arr = [
            'ar' => '🇦🇪 ar',
            "zh" => "🇨🇳 zh",
            'da' => '🇩🇰 ad',
           'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
           'it'	=>  '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'he' => '🇮🇱 he',
            'nl' => '🇳🇱 nl',
            'pl'=> '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇧🇷 pt-br',
        ];
        return $arr;
    }
    public static function langList(){
        $languages = [
            "ar" => "Arabic",
            "zh" => "Chinese",
            "da" => "Danish",
            "de" => "German",
            "en" => "English",
            "es" => "Spanish",
            "fr" => "French",
            "he" => "Hebrew",
            "it" => "Italian",
            "ja" => "Japanese",
            "nl" => "Dutch",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ru" => "Russian",
            "tr" => "Turkish",
            "pt-br"=>"Portuguese(Brazil)"
        ];
        return $languages;
    }
    public static function languagecreate(){
        $languages=Utility::langList();
        foreach($languages as $key => $lang)
        {
            $languageExist = Languages::where('code',$key)->first();
            if(empty($languageExist))
            {
                $language = new Languages();
                $language->code = $key;
                $language->fullName = $lang;
                $language->save();
            }
        }
    }
    public static function langSetting(){
        $data = DB::table('settings')->where('created_by', 1)->get();

        $settings = [];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }
    public static function languages()
    {
        if (self::$languages === null) {
            self::$languages = self::fetchLanguages();
        }

        return self::$languages;

    }
    public static function fetchLanguages(){
        $languages = Utility::langList();

        if(Schema::hasTable('languages')){
            $settings = self::settings();

            if(!empty($settings['disable_lang'])){
                $disabledlang = explode(',', $settings['disable_lang']);

                $languages = Languages::whereNotIn('code',$disabledlang)->pluck('fullName','code');

            }
            else{
                $languages = Languages::pluck('fullName','code');
            }
        }

        return $languages;
    }

        // start for (plans) storage limit - for file upload size
        public static function updateStorageLimit($company_id, $image_size)
        {
            $user   = User::find($company_id);

            if($user->super_admin_employee == 1){
                return 1;
            }

            $image_size = number_format($image_size / 1048576, 2);

            $plan   = Plan::find($user->plan);

            $total_storage = $user->storage_limit + $image_size;

            if($plan->storage_limit <= $total_storage && $plan->storage_limit != -1)
            {
                $error= __('Plan storage limit is over so please upgrade the plan.');
                return $error;
            }
            else{
                $user->storage_limit = $total_storage;
            }

            $user->save();
            return 1;

        }

        public static function changeStorageLimit($company_id, $file_path)
        {
            $files =  File::glob(storage_path($file_path));
            $fileSize = 0;
            foreach($files as $file){
                $fileSize += \File::size($file);
            }

            $image_size = number_format($fileSize / 1048576, 2);
            $user   = User::find($company_id);
            $plan   = Plan::find($user->plan);
            $total_storage = $user->storage_limit - $image_size;

            $user->storage_limit = $total_storage;
            $user->save();

            $status = false;
            foreach($files as $key => $file)
            {
                if(\File::exists($file))
                {
                    $status = \File::delete($file);
                }
            }

            return true;

        }

    public static function keyWiseUpload_file($request, $key_name, $name, $path, $data_key, $custom_validation = [])
    {

        $multifile = [
            $key_name => $request->file($key_name)[$data_key],
        ];

        try {
            $settings = Utility::getStorageSetting();

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com',
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes = !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';

                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes = !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';

                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes = !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }

                $file = $request->$key_name;

                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];

                }

                $validator = \Validator::make($multifile, [
                    $key_name => $validation,
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {


                        $multifile[$key_name]->move(storage_path($path), $name);
                        $path = $path . $name;

                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );


                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                    }

                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $path,
                    ];
                    return $res;
                }

            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }

        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function file_upload_validation()
    {
        $settings = Utility::getStorageSetting();
        $mimes ='';
        $max_size='';
        if ($settings['storage_setting'] == 'wasabi') {
            $mimes = !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
            $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';

        }
        if ($settings['storage_setting'] == 's3') {
            $mimes = !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
            $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';

        }
        if ($settings['storage_setting'] == 'local') {

            $mimes = !empty($settings['local_storage_validation']) ?  str_replace(",",",.", $settings['local_storage_validation']): '';
            $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

        }
        return $arr=['mimes'=>$mimes,'max_size'=>$max_size];
    }

    public static function getcompanyValByName($key)
    {
        $userId = Auth::user()->creatorId();
        $user=User::where('id',$userId)->first();
        $val='';
        if($user && isset($user->$key))
        {

            $val=$user->$key;
        }

        return $val;

    }
    public static function getcompanydetailValByName($key)
    {
        $userId = Auth::user()->creatorId();
        $user=Advocate::where('user_id',$userId)->first();

        $val='';
        if($user && isset($user->$key))
        {

            $val=$user->$key;
        }

        return $val;

    }

    public static function getSMTPDetails($user_id = null)
    {
        try {
            $settings = Utility::settings($user_id);
            config([
                'mail.default'                   => isset($settings['mail_driver'])       ? $settings['mail_driver']       : '',
                'mail.mailers.smtp.host'         => isset($settings['mail_host'])         ? $settings['mail_host']         : '',
                'mail.mailers.smtp.port'         => isset($settings['mail_port'])         ? $settings['mail_port']         : '',
                'mail.mailers.smtp.encryption'   => isset($settings['mail_encryption'])   ? $settings['mail_encryption']   : '',
                'mail.mailers.smtp.username'     => isset($settings['mail_username'])     ? $settings['mail_username']     : '',
                'mail.mailers.smtp.password'     => isset($settings['mail_password'])     ? $settings['mail_password']     : '',
                'mail.from.address'              => isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
                'mail.from.name'                 => isset($settings['mail_from_name'])    ? $settings['mail_from_name']    : '',
            ]);
            return $settings;
        } catch (\Exception $e) {
            return redirect()->back()->with('Email SMTP settings does not configured so please contact to your site admin.');
        }
    }

    public static function notification()
    {
        $endDate=$y_date=Carbon::now()->addDay(7)->format('Y-m-d');
        $startDate=$y_date=Carbon::now()->format('Y-m-d');
        if(Auth::user()->type=='client')
        {
            $notification = Notification::where('bill_to',Auth::user()->id)->whereBetween('due_date',[$startDate, $endDate])->get();
        }
        else{

            $notification = Notification::where('bill_from',Auth::user()->id)->whereBetween('due_date',[$startDate, $endDate])->get();
        }
        return $notification;
    }
}

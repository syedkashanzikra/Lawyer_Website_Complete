<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Utility;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\LoginDetail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create($lang = 'en')
    {
        $langList = Utility::langList();

        $lang = array_key_exists($lang, $langList) ? $lang : 'en';

        if (empty($lang))
        {
            $lang = Utility::getValByName('default_language');
        }

        if($lang == 'ar' || $lang =='he'){
            $value = 'on';
        }
        else{
            $value = 'off';
        }
        DB::insert(
            'insert into settings (`value`, `name`,`created_by`) values ( ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                $value,
                'SITE_RTL',
                1,

            ]
        );

        App::setLocale($lang);
        return view('auth.login',compact('lang'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user && $user->is_disable == 0)
        {
            return redirect()->back()->with('status', __('Your Account is disable,please contact your Administrator.'));
        }

        $settings = Utility::settings();

        if ($settings['recaptcha_module'] == 'on') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }

        $this->validate($request, $validation);

        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        // $ip = '49.36.83.154'; // This is static ip address

        $ip = $_SERVER['REMOTE_ADDR']; // your ip address here
        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
        if ($whichbrowser->device->type == 'bot') {
            return;
        }
        $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;
        /* Detect extra details about the user */
        $query['browser_name'] = $whichbrowser->browser->name ?? null;
        $query['os_name'] = $whichbrowser->os->name ?? null;
        $query['browser_language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $query['device_type'] = self::get_device_type($_SERVER['HTTP_USER_AGENT']);
        $query['referrer_host'] = !empty($referrer['host']);
        $query['referrer_path'] = !empty($referrer['path']);

        isset($query['timezone'])?date_default_timezone_set($query['timezone']):'';

        $json = json_encode($query);

        $user = Auth::user();

        if ($user->type != 'company' && $user->type != 'super admin') {
            $login_detail = LoginDetail::create([
                'user_id' =>  $user->id,
                'ip' => $ip,
                'date' => date('Y-m-d H:i:s'),
                'details' => $json,
                'created_by' => Auth::user()->creatorId(),
            ]);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    function get_device_type($user_agent)
    {
        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
        if (preg_match_all($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {
            if (preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
    }
}

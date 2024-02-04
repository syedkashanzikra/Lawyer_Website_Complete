<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Utility;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create($lang = '')
    {
        $settings = Utility::settings();

        if ($settings['signup_button'] == 'on') {
            if ($lang == '') {
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

            return view('auth.register', compact('lang'));
        } else {
            return \Redirect::to('login');
        }

    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $settings = Utility::settings();

        if ($settings['recaptcha_module'] == 'on') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }

        $this->validate($request, $validation);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string',
            'min:8', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'company',
            'plan' => 1,
            'lang' => Utility::getValByName('default_language'),
            'avatar' => '',
            'created_by' => 1,
        ]);

        $detail = new UserDetail();
        $detail->user_id = $user->id;
        $detail->save();

        Auth::login($user);

        $settings = Utility::settings();

        if ($settings['email_verification'] == 'on') {
            try {
                Utility::getSMTPDetails(1);
                
                event(new Registered($user));

                $role_r = Role::findByName('company');
                $user->assignRole($role_r);
                $user->MakeRole($user->id);

            } catch (\Exception $e) {

                $user->delete();
                return redirect('/register/lang?')->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
            }
            return view('auth.verify');
        } else {

            $user->email_verified_at = date('h:i:s');
            $user->save();

            $role_r = Role::findByName('company');
            $user->assignRole($role_r);
            $user->MakeRole($user->id);

            return redirect(RouteServiceProvider::HOME);
        }

    }
}

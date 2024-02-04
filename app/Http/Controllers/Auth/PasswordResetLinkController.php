<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create($lang = '')
    {
        if (empty($lang)) {
            $lang = Utility::getValByName('default_language');
        }

        if ($lang == 'ar' || $lang == 'he') {
            $value = 'on';
        } else {
            $value = 'off';
        }
        DB::insert(
            'insert into settings (`value`, `name`,`created_by`) values ( ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
            [
                $value,
                'SITE_RTL',
                1,

            ]
        );

        App::setLocale($lang);
        return view('auth.forgot-password', compact('lang'));
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        try {
            Utility::getSMTPDetails();

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status == Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (\Throwable $th) {
            
            return redirect()->back()->with('Email SMTP settings does not configured so please contact to your site admin.');
        }
    }
}

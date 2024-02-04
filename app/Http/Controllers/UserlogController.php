<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginDetail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserlogController extends Controller
{
    public function  index(Request $request)
    {
        if (Auth::user()->type == 'company') {
            $objUser = Auth::user();
            $time = date_create($request->month);
            $firstDayofMOnth = (date_format($time, 'Y-m-d'));
            $lastDayofMonth =    \Carbon\Carbon::parse($request->month)->endOfMonth()->toDateString();

            $usersList = User::where('created_by', '=', $objUser->creatorId())->whereNotIn('type', ['admin', 'company'])->get()->pluck('name', 'id');
            $usersList->prepend('All', '');

            if ($request->month == null) {
                $users = DB::table('login_details')
                    ->join('users', 'login_details.user_id', '=', 'users.id')
                    ->select(DB::raw('login_details.*, users.name as user_name , users.email as user_email'))
                    ->where(['login_details.created_by' => $objUser->id]);

            } else {
                $users = DB::table('login_details')
                    ->join('users', 'login_details.user_id', '=', 'users.id')
                    ->select(DB::raw('login_details.*, users.name as user_name , users.email as user_email'))
                    ->where(['login_details.created_by' => $objUser->id]);
            }

            if (!empty($request->month)) {
                $users->where('date', '>=', $firstDayofMOnth);
                $users->where('date', '<=', $lastDayofMonth);
            }
            if (!empty($request->user)) {
                $users->where(['user_id'  => $request->user]);
            }

            $users = $users->get();

            return view('userlog.index', compact('users', 'usersList'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }
    }

    public function destroy($id)
    {
        $user = LoginDetail::find($id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', __('User Logs successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }
    public function view($id)
    {
        $user = LoginDetail::find($id);

        $userType = User::find($id);

        $json = json_decode($user->details);
        return view('userlog.view', compact('user','userType'));
    }
}

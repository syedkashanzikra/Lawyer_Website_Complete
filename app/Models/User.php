<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'plan',
        'lang',
        'avatar',
        'created_by',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function creatorId()
    {
        if ($this->type == 'company' || $this->type == 'super admin') {
            return $this->id;
        } else {
            return $this->created_by;
        }

    }

    public static function getTeams($id)
    {
        $advName = User::whereIn('id', explode(',', $id))->pluck('name')->toArray();
        return implode(', ', $advName);
    }

    public static function getUser($id)
    {
        $advName = User::find($id);
        return $advName;
    }
    public function currentLanguage()
    {
        return $this->lang;
    }

    public function invoiceNumberFormat($number)
    {
        $settings = Utility::settings();

        return '#' . sprintf("%05d", $number);
    }

    public static function dateFormat($date)
    {
        $settings = Utility::settings();
        return date($settings['site_date_format'], strtotime($date));
    }

    public function assignPlan($planID)
    {
        $plan = Plan::find($planID);
        if ($plan) {
            $this->plan = $plan->id;
            if ($plan->duration == 'month') {
                $this->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            } elseif ($plan->duration == 'year') {
                $this->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            } else {
                $this->plan_expire_date = null;
            }
            $this->save();

            $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'employee')->get();
            $employees = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get();

            $userCount = 0;
            foreach ($users as $user) {
                $userCount++;
                if ($userCount <= $plan->max_users) {
                    $user->is_active = 1;
                    $user->save();
                } else {
                    $user->is_active = 0;
                    $user->save();
                }
            }
            $employeeCount = 0;
            foreach ($employees as $employee) {
                $employeeCount++;
                if ($employeeCount <= $plan->max_employees) {
                    $employee->is_active = 1;
                    $employee->save();
                } else {
                    $employee->is_active = 0;
                    $employee->save();
                }
            }

            return ['is_success' => true];
        } else {
            return [
                'is_success' => false,
                'error' => 'Plan is deleted.',
            ];
        }
    }

    public function countPaidCompany()
    {
        return User::where('type', '=', 'company')->whereNotIn(
            'plan', [
                      0,
                      1,
                  ]
        )->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function getPlan()
    {
        $user = User::find($this->creatorId());

        return Plan::find($user->plan);
    }

    public static function MakeRole($company_id)
    {
        $data = [];
        $advocate_role_permission = [
             "show dashboard",

             "show group",
             "manage group",

             "manage cause",
             "create cause",
             "delete cause",
             "edit cause",

             "manage case",
             "create case",
             "edit case",
             "view case",
             "delete case",

             "create todo",
             "edit todo",
             "view todo",
             "delete todo",
             "manage todo",

             "manage bill",
             "create bill",
             "edit bill",
             "delete bill",
             "view bill",

             "manage diary",

             "manage timesheet",
             "create timesheet",
             "edit timesheet",
             "delete timesheet",
             "view timesheet",

             "manage expense",
             "create expense",
             "edit expense",
             "delete expense",
             "view expense",

             "manage feereceived",
             "create feereceived",
             "edit feereceived",
             "delete feereceived",
             "view feereceived",

             "view calendar",

             "manage document",
             "create document",
             "edit document",
             "delete document",
             "view document",
        ];

        $advocate_role = Role::where('name','advocate')->where('created_by',$company_id)->where('guard_name','web')->first();

        if(empty($advocate_role))
        {
            $advocate_role                   = new Role();
            $advocate_role->name             = 'advocate';
            $advocate_role->guard_name       = 'web';
            $advocate_role->created_by       = $company_id;
            $advocate_role->save();

            foreach($advocate_role_permission as $permission_s){
                $permission = Permission::where('name',$permission_s)->first();
                $advocate_role->givePermissionTo($permission);
            }
        }

        $data['advocate_role'] = $advocate_role;

        return $data;
    }

    private static $getDefualtViewRouteByModule = null;

    public static function getDefualtViewRouteByModule($module = null)
    {
        if (self::$getDefualtViewRouteByModule === null) {
            self::$getDefualtViewRouteByModule = self::fetchGetDefualtViewRouteByModule($module);
        }

        return self::$getDefualtViewRouteByModule;
    }

    public static function fetchGetDefualtViewRouteByModule($module)
    {
        $userId      = \Auth::user()->id;
        $defaultView = UserDefualtView::select('route')->where('module', $module)->where('user_id', $userId)->first();

        return !empty($defaultView) ? $defaultView->route : '';
    }

    public static function priceFormat($price)
    {
        $settings = Utility::settings();
        return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, 2) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
    }
    public function timeFormat($time)
    {
        $settings = Utility::settings();

        return date($settings['site_time_format'], strtotime($time));
    }

    public function crmcreatorId()
    {
        if ($this->type == 'super admin') {
            return $this->id;
        } else {
            if($this->type == 'advocate')
            {
                $company=User::where('id',$this->created_by)->first();
                return $company->created_by;
            }
            else
            {
                return $this->created_by;
            }

        }


    }
    public static function userDefualtView($request)
    {
        $userId      = \Auth::user()->id;
        $defaultView = UserDefualtView::where('module', $request->module)->where('user_id', $userId)->first();

        if(empty($defaultView))
        {
            $userView = new UserDefualtView();
        }
        else
        {
            $userView = $defaultView;
        }

        $userView->module  = $request->module;
        $userView->route   = $request->route;
        $userView->view    = $request->view;
        $userView->user_id = $userId;
        $userView->save();
    }

    public function supportTicketCreatorId()
    {
        if ($this->type == 'super admin') {
            return $this->id;
        } else {
            if($this->type == 'advocate')
            {
                $company=User::where('id',$this->created_by)->first();
                return $company->created_by;
            }
            else
            {
                return $this->created_by;
            }
        }
    }
  
}

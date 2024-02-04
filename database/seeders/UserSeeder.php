<?php

namespace Database\Seeders;

use App\Models\Advocate;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // all permissions insert
        $permissions = [

            ['name' => 'view contact', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage role', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage staff', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage team', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create group', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create member', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit member', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete member', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage member', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage group', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'show member', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'show group', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit group', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete group', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage advocate', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create advocate', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view advocate', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit advocate', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete advocate', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage court', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create court', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit court', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete court', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage highcourt', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create highcourt', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit highcourt', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete highcourt', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create bench', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage bench', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit bench', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete bench', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage cause', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create cause', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete cause', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit cause', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage case', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create case', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit case', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view case', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete case', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create todo', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit todo', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view todo', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete todo', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage todo', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage bill', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create bill', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit bill', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete bill', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage tax', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create tax', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit tax', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete tax', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view bill', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage diary', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage timesheet', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create timesheet', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit timesheet', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete timesheet', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage expense', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create expense', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit expense', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete expense', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view timesheet', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view expense', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage feereceived', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create feereceived', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit feereceived', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete feereceived', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view feereceived', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view calendar', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create role', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit role', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete role', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage permission', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create permission', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit permission', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete permission', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage setting', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage document', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create document', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit document', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete document', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage doctype', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create doctype', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit doctype', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete doctype', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'view document', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'show dashboard', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage super admin dashboard', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage user', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create user', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit user', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete user', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create language', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage system settings', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage stripe settings', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage plan', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create plan', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit plan', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete plan', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage order', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage coupon', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create coupon', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit coupon', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete coupon', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'buy plan', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'manage appointment', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create appointment', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit appointment', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete appointment', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            ['name' => 'manage client', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create client', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit client', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete client', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            ['name' => 'manage motions', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'create motions', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit motions', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete motions', 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

        ];

        Permission::insert($permissions);


        $companyRole = Role::create(
            [
                'name' => 'company',
                'created_by' => 0,
            ]
        );

        $companyPermissions = [


            ["name" => "view contact"],
            ["name" => "manage role"],
            ["name" => "manage staff"],
            ["name" => "manage team"],
            ["name" => "create group"],
            ["name" => "create member"],
            ["name" => "edit member"],
            ["name" => "delete member"],
            ["name" => "manage member"],
            ["name" => "manage group"],
            ["name" => "show member"],
            ["name" => "show group"],
            ["name" => "edit group"],
            ["name" => "delete group"],
            ["name" => "manage advocate"],
            ["name" => "create advocate"],
            ["name" => "view advocate"],
            ["name" => "edit advocate"],
            ["name" => "delete advocate"],
            ["name" => "manage court"],
            ["name" => "create court"],
            ["name" => "edit court"],
            ["name" => "delete court"],
            ["name" => "manage highcourt"],
            ["name" => "create highcourt"],
            ["name" => "edit highcourt"],
            ["name" => "delete highcourt"],
            ["name" => "create bench"],
            ["name" => "manage bench"],
            ["name" => "edit bench"],
            ["name" => "delete bench"],
            ["name" => "manage cause"],
            ["name" => "create cause"],
            ["name" => "delete cause"],
            ["name" => "edit cause"],
            ["name" => "manage case"],
            ["name" => "create case"],
            ["name" => "edit case"],
            ["name" => "view case"],
            ["name" => "delete case"],
            ["name" => "create todo"],
            ["name" => "edit todo"],
            ["name" => "view todo"],
            ["name" => "delete todo"],
            ["name" => "manage todo"],
            ["name" => "manage bill"],
            ["name" => "create bill"],
            ["name" => "edit bill"],
            ["name" => "delete bill"],
            ["name" => "manage tax"],
            ["name" => "create tax"],
            ["name" => "edit tax"],
            ["name" => "delete tax"],
            ["name" => "view bill"],
            ["name" => "manage diary"],
            ["name" => "manage timesheet"],
            ["name" => "create timesheet"],
            ["name" => "edit timesheet"],
            ["name" => "delete timesheet"],
            ["name" => "manage expense"],
            ["name" => "create expense"],
            ["name" => "edit expense"],
            ["name" => "delete expense"],
            ["name" => "view timesheet"],
            ["name" => "view expense"],
            ["name" => "manage feereceived"],
            ["name" => "create feereceived"],
            ["name" => "edit feereceived"],
            ["name" => "delete feereceived"],
            ["name" => "view feereceived"],
            ["name" => "view calendar"],
            ["name" => "create role"],
            ["name" => "edit role"],
            ["name" => "delete role"],
            ["name" => "manage permission"],
            ["name" => "create permission"],
            ["name" => "edit permission"],
            ["name" => "delete permission"],
            ["name" => "manage setting"],
            ["name" => "manage document"],
            ["name" => "create document"],
            ["name" => "edit document"],
            ["name" => "delete document"],
            ["name" => "manage doctype"],
            ["name" => "create doctype"],
            ["name" => "edit doctype"],
            ["name" => "delete doctype"],
            ["name" => "view document"],
            ["name" => "show dashboard"],
            ["name" => "buy plan"],

            ["name" => "manage appointment"],
            ["name" => "create appointment"],
            ["name" => "edit appointment"],
            ["name" => "delete appointment"],

            ["name" => "manage client"],
            ["name" => "create client"],
            ["name" => "edit client"],
            ["name" => "delete client"],

            ["name" => "manage motions"],
            ["name" => "create motions"],
            ["name" => "edit motions"],
            ["name" => "delete motions"],

        ];

        $companyRole->givePermissionTo($companyPermissions);

        // admin role
        $superAdminRole = Role::create(
            [
                'name' => 'super admin',
                'created_by' => 0,
            ]
        );

        $superAdminPermissions = [
            ['name' => 'manage super admin dashboard'],
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'create language'],
            ['name' => 'manage system settings'],
            ['name' => 'manage stripe settings'],
            ['name' => 'manage plan'],
            ['name' => 'create plan'],
            ['name' => 'edit plan'],
            ['name' => 'delete plan'],
            ['name' => 'manage order'],
            ['name' => 'manage coupon'],
            ['name' => 'create coupon'],
            ['name' => 'edit coupon'],
            ['name' => 'delete coupon'],
        ];

        $superAdminRole->givePermissionTo($superAdminPermissions);

        $superAdmin = User::create(
            [
                'name'              => 'Super Admin',
                'email'             => 'superadmin@example.com',
                'password'          => Hash::make('1234'),
                'type'              => 'super admin',
                'lang'              => 'en',
                'avatar'            => '',
                'created_by'        => 0,
                'email_verified_at' => now(),
            ]
        );

        $detail = new UserDetail();
        $detail->user_id = $superAdmin->id;
        $detail->save();

        $superAdmin->assignRole($superAdminRole);

        $company = User::create(
            [
                'name' => 'Company',
                'email' => 'company@example.com',
                'password' => Hash::make('1234'),
                'type' => 'company',
                'lang' => 'en',
                'plan' => 1,
                'avatar' => '',
                'email_verified_at' => date("Y-m-d H:i:s"),
                'created_by' => 1,
            ]
        );
        $detail = new UserDetail();
        $detail->user_id = $company->id;
        $detail->save();

        $company->assignRole($companyRole);


        // advocate
        $advocateRole = Role::create(
            [
                'name' => 'advocate',
                'created_by' => $company->id,
            ]
        );

        $advocatePermissions = [
            ["name" => "show dashboard"],

            ["name" => "view advocate"],

            ["name" => "manage appointment"],
            ["name" => "create appointment"],
            ["name" => "edit appointment"],
            ["name" => "delete appointment"],

            ["name" => "manage doctype"],
            ["name" => "create doctype"],

            ["name" => "show group"],
            ["name" => "manage group"],

            ["name" => "show member"],

            ["name" => "manage case"],
            ["name" => "create case"],
            ["name" => "edit case"],
            ["name" => "view case"],

            ["name" => "create todo"],
            ["name" => "edit todo"],
            ["name" => "view todo"],
            ["name" => "delete todo"],
            ["name" => "manage todo"],

            ["name" => "manage cause"],
            ["name" => "create cause"],
            ["name" => "delete cause"],
            ["name" => "edit cause"],

            ["name" => "manage timesheet"],
            ["name" => "create timesheet"],
            ["name" => "edit timesheet"],
            ["name" => "view timesheet"],

            ["name" => "manage expense"],
            ["name" => "create expense"],
            ["name" => "view expense"],

            ["name" => "view calendar"],

            ["name" => "manage diary"],

        ];

        $advocateRole->givePermissionTo($advocatePermissions);

        $advocate = User::create(
            [
                'name' => 'Advocate',
                'email' => 'advocate@example.com',
                'password' => Hash::make('1234'),
                'type' => 'advocate',
                'lang' => 'en',
                'avatar' => '',
                'created_by' => $company->id,
                'email_verified_at' => now(),
            ]
        );

        $detail = new UserDetail();
        $detail->user_id = $advocate->id;
        $detail->save();

        // advocate table insert
        $addAdvocate = new Advocate();
        $addAdvocate['user_id']  = $advocate->id;
        $addAdvocate['created_by']  = $company->id;
        $addAdvocate->save();


        $advocate->assignRole($advocateRole);

        // client
        $clientRole = Role::create(
            [
                'name' => 'client',
                'created_by' => $company->id,
            ]
        );

        $clientPermissions = [
            ["name" => "show dashboard"],

            ["name" => "show group"],
            ["name" => "manage group"],

            ["name" => "manage case"],
            ["name" => "view case"],

            ["name" => "create todo"],
            ["name" => "edit todo"],
            ["name" => "view todo"],
            ["name" => "delete todo"],
            ["name" => "manage todo"],

            ["name" => "manage bill"],
            ["name" => "create bill"],
            ["name" => "edit bill"],
            ["name" => "delete bill"],
            ["name" => "view bill"],

            ["name" => "manage diary"],

            ["name" => "manage timesheet"],
            ["name" => "create timesheet"],
            ["name" => "edit timesheet"],
            ["name" => "delete timesheet"],
            ["name" => "view timesheet"],

            ["name" => "manage expense"],
            ["name" => "create expense"],
            ["name" => "edit expense"],
            ["name" => "delete expense"],
            ["name" => "view expense"],

            ["name" => "manage feereceived"],
            ["name" => "create feereceived"],
            ["name" => "edit feereceived"],
            ["name" => "delete feereceived"],
            ["name" => "view feereceived"],

            ["name" => "view calendar"],

            ["name" => "manage appointment"],
            ["name" => "create appointment"],
            ["name" => "edit appointment"],
            ["name" => "delete appointment"],

        ];

        $clientRole->givePermissionTo($clientPermissions);

        $client = User::create(
            [
                'name' => 'Client',
                'email' => 'client@example.com',
                'password' => Hash::make('1234'),
                'type' => 'client',
                'lang' => 'en',
                'avatar' => '',
                'created_by' => $company->id,
                'email_verified_at' => now(),
            ]
        );

        $detail = new UserDetail();
        $detail->user_id = $client->id;
        $detail->save();

        $client->assignRole($clientRole);

        Utility::languagecreate();

        $data = [
            ['name' => 'local_storage_validation', 'value' => 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'wasabi_storage_validation', 'value' => 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 's3_storage_validation', 'value' => 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'local_storage_max_upload_size', 'value' => 2048000, 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'wasabi_max_upload_size', 'value' => 2048000, 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 's3_max_upload_size', 'value' => 2048000, 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'storage_setting', 'value' => 'local', 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('settings')->insert($data);
    }


}

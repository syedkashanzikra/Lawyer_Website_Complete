<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('type')->default('user');
            $table->string('avatar', 100)->nullable();
            $table->string('lang', 100)->default('en');
            $table->integer('plan')->nullable();
            $table->integer('requested_plan')->default(0);
            $table->date('plan_expire_date')->nullable();
            $table->integer('is_active')->default(1);
            $table->string('created_by')->default(1);
            $table->string('referral_id')->default(null)->nullable();
            $table->float('storage_limit')->default(0);
            $table->integer('super_admin_employee')->default(0)->nullable();
            $table->string('permission_json')->default(null)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

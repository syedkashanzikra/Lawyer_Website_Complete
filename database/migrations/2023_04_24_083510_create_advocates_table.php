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
        Schema::create('advocates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('phone_number')->nullable();
            $table->integer('age')->nullable();
            $table->string('father_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('website')->nullable();
            $table->string('tin')->nullable();
            $table->string('gstin')->nullable();
            $table->string('pan_number')->nullable();
            $table->decimal('hourly_rate',15,2)->nullable();
            $table->string('ofc_address_line_1')->nullable();
            $table->string('ofc_address_line_2')->nullable();
            $table->bigInteger('ofc_country')->nullable();
            $table->bigInteger('ofc_state')->nullable();
            $table->string('ofc_city')->nullable();
            $table->bigInteger('ofc_zip_code')->nullable();
            $table->string('home_address_line_1')->nullable();
            $table->string('home_address_line_2')->nullable();
            $table->string('home_country')->nullable();
            $table->string('home_state')->nullable();
            $table->string('home_city')->nullable();
            $table->string('home_zip_code')->nullable();
            $table->string('bank_details')->nullable()->default(null);
            $table->integer('created_by');
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
        Schema::dropIfExists('advocates');
    }
};

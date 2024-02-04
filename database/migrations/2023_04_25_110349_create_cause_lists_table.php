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
        Schema::create('cause_lists', function (Blueprint $table) {
            $table->id();
            $table->string('court');
            $table->string('highcourt')->nullable();
            $table->string('bench')->nullable();
            $table->string('causelist_by');
            $table->string('advocate_name');
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
        Schema::dropIfExists('cause_lists');
    }
};

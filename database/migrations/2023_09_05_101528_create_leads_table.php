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
        Schema::create(
            'leads', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('subject');
            $table->string('phone_no');
            $table->integer('user_id');
            $table->integer('pipeline_id');
            $table->integer('stage_id');
            $table->string('sources')->nullable();
            $table->string('products')->nullable();
            $table->string('items')->nullable();
            $table->text('notes')->nullable();
            $table->string('labels')->nullable();
            $table->integer('order')->default(0);
            $table->integer('created_by');
            $table->integer('is_active')->default(1);
            $table->integer('is_converted')->default(0);
            $table->date('date')->nullable();
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
};

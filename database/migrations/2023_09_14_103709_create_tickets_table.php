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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticket_id',100)->unique();
            $table->string('name');
            $table->string('email');
            $table->integer('category');
            $table->integer('priority');
            $table->string('subject');
            $table->string('status');
            $table->longText('description');
            $table->string('created_by');
            $table->longText('attachments');
            $table->text('note')->nullable();
            $table->dateTime('reslove_at')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};

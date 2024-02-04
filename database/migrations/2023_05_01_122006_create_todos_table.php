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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable()->default(null);
            $table->string('due_date');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('relate_to');
            $table->string('assign_to');
            $table->integer('assign_by');
            $table->string('priority');
            $table->integer('status')->default(1);
            $table->integer('completed_by')->nullable();
            $table->string('completed_at')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('todos');
    }
};

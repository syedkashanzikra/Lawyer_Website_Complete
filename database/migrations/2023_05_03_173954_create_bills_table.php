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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_from');
            $table->integer('advocate')->nullable();
            $table->string('custom_advocate')->nullable();
            $table->string('custom_address')->nullable();
            $table->string('custom_email')->nullable();
            $table->string('title');
            $table->string('bill_number');
            $table->string('due_date');
            $table->longText('items')->nullable();
            $table->decimal('subtotal',15,2);
            $table->decimal('total_tax',15,2);
            $table->decimal('total_disc',15,2);
            $table->decimal('total_amount',15,2);
            $table->longText('description')->nullable();
            $table->integer('created_by');
            $table->string('bill_to');
            $table->string('reciept_date');
            $table->string('status')->default('PENDING');
            $table->string('due_amount')->default(0);
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
        Schema::dropIfExists('bills');
    }
};

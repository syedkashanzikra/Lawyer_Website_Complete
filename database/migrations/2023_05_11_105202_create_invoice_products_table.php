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
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id');
            $table->string('product_name');
            $table->integer('quantity');
            $table->float('price',15,2)->default('0.00');
            $table->float('tax',15,2)->default('0.00');
            $table->float('total',15,2)->default('0.00');
            $table->float('discount',15,2)->default('0.00');
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
        Schema::dropIfExists('invoice_products');
    }
};

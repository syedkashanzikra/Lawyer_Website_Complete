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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default(null)->nullable();
            $table->integer('type')->default(null)->nullable();
            $table->string('purpose')->default(null)->nullable();
            $table->string('description')->default(null)->nullable();
            $table->string('file')->default(null)->nullable();
            $table->string('doc_size')->default(null)->nullable();
            $table->string('cases')->default(null)->nullable();
            $table->string('document_subtype')->default(null)->nullable();
            $table->integer('created_by')->default(null)->nullable();
            $table->string('doc_link')->default(null)->nullable();
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
        Schema::dropIfExists('documents');
    }
};

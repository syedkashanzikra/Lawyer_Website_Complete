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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->integer('court')->nullable();
            $table->integer('highcourt')->nullable();
            $table->integer('bench')->nullable();
            $table->string('casetype')->nullable();
            $table->string('casenumber')->nullable();
            $table->bigInteger('diarybumber')->nullable();
            $table->integer('year')->nullable();
            $table->string('case_number')->nullable();
            $table->date('filing_date')->nullable();
            $table->bigInteger('court_hall')->nullable();
            $table->bigInteger('floor')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('under_acts')->nullable();
            $table->string('under_sections')->nullable();
            $table->bigInteger('FIR_number')->nullable();
            $table->integer('FIR_year')->nullable();
            $table->string('your_team')->nullable();
            $table->longText('opponents')->nullable();
            $table->longText('opponent_advocates')->nullable();
            $table->string('court_room')->nullable()->default(null);
            $table->string('judge')->nullable()->default(null);
            $table->string('police_station')->nullable()->default(null);
            $table->string('your_party')->nullable()->default(null);
            $table->string('your_party_name')->nullable()->default(null);
            $table->string('opp_party_name')->nullable()->default(null);
            $table->string('stage')->nullable()->default(null);
            $table->string('advocates')->nullable()->default(null);
            $table->string('opp_adv')->nullable()->default(null);
            $table->longText('case_docs')->nullable()->default(null);
            $table->string('filing_party')->default(null)->nullable();
            $table->string('case_status')->default(null)->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('cases');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('arena_name')->nullable();
            $table->string('date_of_soa')->nullable();
            $table->string('meron')->nullable();
            $table->string('wala')->nullable();
            $table->string('total_meron_wala')->nullable();
            $table->string('total_payout_paid')->nullable();
            $table->string('unclaimed')->nullable();
            $table->string('rake')->nullable();
            $table->string('draw_cancelled')->nullable();
            $table->string('draw_cancelled_paid')->nullable();
            $table->string('draw_paid')->nullable();
            $table->string('draw_unpaid')->nullable();
            $table->string('cancelled_unpaid')->nullable();
            $table->string('draw')->nullable();
            $table->string('draw_unclaimed')->nullable();
            $table->string('draw_gain_loss')->nullable();
            $table->string('draw_2_percent')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('imports');
    }
}

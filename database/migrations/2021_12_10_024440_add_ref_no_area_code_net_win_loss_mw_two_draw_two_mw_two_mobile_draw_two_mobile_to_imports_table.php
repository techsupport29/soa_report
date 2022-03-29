<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefNoAreaCodeNetWinLossMwTwoDrawTwoMwTwoMobileDrawTwoMobileToImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->string('refNo')->nullable();
            $table->string('areaCode')->nullable();
            $table->string('netWinLoss')->nullable();
            $table->string('mwTwo')->nullable();
            $table->string('drawTwo')->nullable();
            $table->string('mwTwoMobile')->nullable();
            $table->string('drawTwoMobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imports', function (Blueprint $table) {
            //
        });
    }
}

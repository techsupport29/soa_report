<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeKeyNullableToArenas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('arenas', function (Blueprint $table) {
            //
            $table->string('address')->nullable()->change();
            $table->string('operator')->nullable()->change();
            $table->string('arena')->nullable()->change();
            $table->string('bank_id')->nullable()->change();
            $table->string('contact_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('arenas', function (Blueprint $table) {
            //
        });
    }
}

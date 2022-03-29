<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnameAssignIsAssignTeamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->string('email')->nullable()->change();
            $table->boolean('isAdmin')->default(0);
            $table->string('username')->unique()->nullable();
            $table->foreignId('position_id')->nullable()->constrained('positions');
            $table->string('assign')->nullable();
            $table->boolean('isAssign')->default(false);
            $table->foreignId('team_id')->nullable()->constrained('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}

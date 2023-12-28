<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsLoginDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_details', function (Blueprint $table) {
            $table->string('district');
            $table->string('block')->nullable();
            $table->string('gp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('login_details', function (Blueprint $table) {
            $table->dropColumn('district');
            $table->dropColumn('block');
            $table->dropColumn('gp');
        });
    }
}

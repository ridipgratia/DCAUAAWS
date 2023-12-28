<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInAddUnempAllowance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_unemp_allowance', function (Blueprint $table) {
            $table->integer('district_id');
            $table->integer('block_id')->nullable();
            $table->integer('gp_id')->nullable();
            $table->date('app_rej_date')->nullable();
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('add_unemp_allowance', function (Blueprint $table) {
            //
        });
    }
}

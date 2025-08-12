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
        Schema::table('modules.products', function (Blueprint $table) {
            $table->string('registration_code')->nullable();
            $table->string('batch')->nullable();
            $table->integer('concentration')->nullable();
            $table->date('expiration_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.products', function (Blueprint $table) {
            $table->dropColumn('registration_code');
            $table->dropColumn('batch');
            $table->dropColumn('concentration');
            $table->dropColumn('expiration_date');
        });
    }
};

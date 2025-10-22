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
        Schema::table('modules.control_of_xylophages', function (Blueprint $table) {
            $table->string('effectiveness_verification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.control_of_xylophages', function (Blueprint $table) {
            $table->dropColumn('effectiveness_verification');
        });
    }
};

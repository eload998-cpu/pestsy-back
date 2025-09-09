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
        Schema::table('modules.control_of_rodents', function (Blueprint $table) {

            $table->bigInteger('aplication_id')->nullable();
            $table->foreign('aplication_id')->references('id')->on("modules.aplications")->onDelete('restrict');
            $table->string('infestation_level')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.control_of_rodents', function (Blueprint $table) {
            $table->dropColumn('infestation_level');
            $table->dropColumn('aplication_id');
            $table->dropForeign(['aplication_id']);
        });
    }
};

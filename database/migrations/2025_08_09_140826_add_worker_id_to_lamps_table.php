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
        Schema::table('modules.lamps', function (Blueprint $table) {
            $table->bigInteger('worker_id')->nullable();
            $table->foreign('worker_id')->references('id')->on("modules.workers")->onDelete('restrict');
            $table->string('application_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.lamps', function (Blueprint $table) {
            $table->dropColumn('worker_id');
            $table->dropColumn('application_time');
        });
    }
};

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
        Schema::table('modules.control_of_legionella', function (Blueprint $table) {

            $table->boolean('within_critical_limits')->default(false);
            
            $table->bigInteger('worker_id')->nullable();
            $table->foreign('worker_id')->references('id')->on("modules.workers")->onDelete('restrict');

            $table->bigInteger('aplication_id')->nullable();
            $table->foreign('aplication_id')->references('id')->on("modules.aplications")->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.control_of_legionella', function (Blueprint $table) {

            $table->dropColumn('within_critical_limits');
            $table->dropColumn('aplication_id');
            $table->dropColumn('worker_id');
        });
    }
};

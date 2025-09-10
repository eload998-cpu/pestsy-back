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
        Schema::table('modules.workers', function (Blueprint $table) {
            $table->string('certification_title')->nullable();
            $table->date('certification_date')->nullable();
            $table->string('certifying_entity')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.workers', function (Blueprint $table) {
            $table->dropColumn('certification_title');
            $table->dropColumn('certification_date');
            $table->dropColumn('certifying_entity');
        });
    }
};

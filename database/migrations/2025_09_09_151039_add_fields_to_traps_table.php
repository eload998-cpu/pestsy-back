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
        Schema::table('modules.traps', function (Blueprint $table) {
            $table->boolean('within_critical_limits')->default(false);
            $table->enum('pheromones_state', ['Activa', 'Vencida'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.traps', function (Blueprint $table) {
            $table->dropColumn('within_critical_limits');
            $table->dropColumn('pheromones_state');
        });
    }
};

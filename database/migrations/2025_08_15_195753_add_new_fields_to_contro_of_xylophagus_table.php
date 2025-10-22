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
            $table->decimal('treated_area_value', 10, 2)->nullable();
            $table->enum('treated_area_unit', ['m2', 'm3'])->nullable();
            $table->decimal('calculated_total_amount', 10, 2)->nullable();
            $table->string('calculated_total_unit', 10)->nullable();
            $table->enum('pre_humidity', ['baja', 'normal', 'alta'])->nullable();
            $table->enum('pre_ventilation', ['adecuada', 'deficiente'])->nullable();
            $table->enum('pre_access', ['libre', 'restringido', 'bloqueado'])->nullable();
            $table->text('pre_notes')->nullable();
            $table->enum('post_humidity', ['baja', 'normal', 'alta'])->nullable();
            $table->enum('post_ventilation', ['adecuada', 'deficiente'])->nullable();
            $table->enum('post_access', ['libre', 'restringido', 'bloqueado'])->nullable();
            $table->text('post_notes')->nullable();
            $table->string('dose')->nullable();

            $table->bigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on("modules.locations")->onDelete('restrict');

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
        Schema::table('modules.control_of_xylophages', function (Blueprint $table) {
            $table->dropColumn('treated_area_value');
            $table->dropColumn('treated_area_unit');
            $table->dropColumn('calculated_total_amount');
            $table->dropColumn('calculated_total_unit');

            $table->dropColumn('pre_humidity');
            $table->dropColumn('pre_ventilation');
            $table->dropColumn('pre_access');
            $table->dropColumn('pre_notes');
            $table->dropColumn('post_humidity');
            $table->dropColumn('post_ventilation');
            $table->dropColumn('post_access');
            $table->dropColumn('post_notes');
            $table->dropColumn('dose');
            $table->dropColumn('location_id');
            $table->dropColumn('aplication_id');
            $table->dropColumn('worker_id');

        });
    }
};

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
        if (! Schema::hasColumn('modules.fumigations', 'location_id')) {
            Schema::table('modules.fumigations', function (Blueprint $table) {
                $table->bigInteger('location_id')->nullable()->after('id');
                $table->foreign('location_id')
                    ->references('id')
                    ->on('modules.locations')
                    ->onDelete('restrict');
            });
        }

        if (! Schema::hasColumn('modules.traps', 'location_id')) {
            Schema::table('modules.traps', function (Blueprint $table) {
                $table->bigInteger('location_id')->nullable()->after('id');
                $table->foreign('location_id')
                    ->references('id')
                    ->on('modules.locations')
                    ->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('modules.fumigations', 'location_id')) {
            Schema::table('modules.fumigations', function (Blueprint $table) {
                $table->dropColumn('location_id');
            });
        }

        if (Schema::hasColumn('modules.traps', 'location_id')) {
            Schema::table('modules.traps', function (Blueprint $table) {
                $table->dropColumn('location_id');
            });
        }
    }
};

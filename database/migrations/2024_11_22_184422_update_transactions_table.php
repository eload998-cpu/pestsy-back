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
        // Ensure the table and schema exist
        if (Schema::hasTable('administration.transactions') && !Schema::hasColumn('administration.transactions', 'data')) {
            Schema::table('administration.transactions', function (Blueprint $table) {
                $table->jsonb('data')->nullable();
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
        if (Schema::hasTable('administration.transactions') && Schema::hasColumn('administration.transactions', 'data')) {

            // Ensure the column exists before attempting to drop it
            Schema::table('administration.transactions', function (Blueprint $table) {
                $table->dropColumn('data');
            });
        }
    }
};

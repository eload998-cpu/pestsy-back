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
        if (Schema::hasTable('administration.plans') && !Schema::hasColumn('administration.plans', 'paypal_id')) {
            Schema::table('administration.plans', function (Blueprint $table) {
                $table->string('paypal_id')->nullable();
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

        if (Schema::hasTable('administration.plans') && Schema::hasColumn('administration.plans', 'paypal_id')) {

        // Ensure the column exists before attempting to drop it
            Schema::table('administration.plans', function (Blueprint $table) {
                $table->dropColumn('paypal_id');
            });
        }
        
    }
};

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

        if (Schema::hasTable('administration.users') && ! Schema::hasColumn('administration.users', 'verify_paypal_subscription')) {

            Schema::table('administration.users', function (Blueprint $table) {
                $table->boolean('verify_paypal_subscription')->default(false);
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

        if (Schema::hasTable('administration.users') && Schema::hasColumn('administration.users', 'verify_paypal_subscription')) {

            Schema::table('administration.users', function (Blueprint $table) {

                $table->dropColumn('verify_paypal_subscription');

            });
        }

    }
};

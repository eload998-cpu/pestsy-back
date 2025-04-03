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

        if (Schema::hasTable('administration.users') && !Schema::hasColumn('administration.users', 'paypal_subscription_id') && !Schema::hasColumn('administration.users', 'active_subscription')) {

            Schema::table('administration.users', function (Blueprint $table) {
                $table->string('paypal_subscription_id')->nullable();
                $table->boolean('active_subscription')->default(false);

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

        if (Schema::hasTable('administration.users') && Schema::hasColumn('administration.users', 'paypal_subscription_id') && Schema::hasColumn('administration.users', 'active_subscription')) {

            Schema::table('administration.users', function (Blueprint $table) {
                $table->dropColumn('paypal_subscription_id');
                $table->dropColumn('active_subscription');
            });
      

        }

    }
};

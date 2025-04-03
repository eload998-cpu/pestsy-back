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
        Schema::table('administration.user_subscriptions', function (Blueprint $table) {
            $table->dropColumn('order_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('administration.user_subscriptions', function (Blueprint $table) {
            $table->integer('order_quantity')->nullable();
        });
    }
};

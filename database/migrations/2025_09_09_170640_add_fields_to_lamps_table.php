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
        Schema::table('modules.lamps', function (Blueprint $table) {
            $table->boolean('within_critical_limits')->default(false);
            $table->bigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on("modules.products")->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.lamps', function (Blueprint $table) {
            $table->dropColumn('within_critical_limits');
            $table->dropColumn('product_id');
        });
    }
};

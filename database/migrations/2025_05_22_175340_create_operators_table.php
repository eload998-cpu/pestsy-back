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
        Schema::create("modules.operators", function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id');
            $table->foreign('worker_id')->references('id')->on("modules.workers")->onDelete('cascade');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('administration.users')->onDelete('cascade');
            $table->bigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('administration.companies')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules.operators');
    }
};

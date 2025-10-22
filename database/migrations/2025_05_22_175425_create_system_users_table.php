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
        Schema::create("modules.system_users", function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("modules.clients")->onDelete('cascade');
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
        Schema::dropIfExists('modules.system_users');
    }
};

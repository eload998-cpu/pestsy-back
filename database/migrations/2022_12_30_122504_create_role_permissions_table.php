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
        Schema::create('administration.role_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('administration.roles')->onDelete('cascade');
            $table->bigInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('administration.permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administration.role_permissions');
    }
};

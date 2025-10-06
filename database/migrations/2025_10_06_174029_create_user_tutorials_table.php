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
        Schema::create('administration.user_tutorials', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->nullable();
            $table->boolean('client_tutorial')->default(false);
            $table->boolean('worker_tutorial')->default(false);
            $table->foreign('user_id')->references('id')->on("administration.users")->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administration.user_tutorials');
    }
};

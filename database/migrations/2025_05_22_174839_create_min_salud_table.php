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
        Schema::create("modules.min_salud", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->bigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on("administration.companies")->onDelete('cascade');
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
        Schema::dropIfExists('modules.min_salud');
    }
};

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
        Schema::create("modules.locations", function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->boolean('is_legionella')->default(false);
            $table->boolean('is_general')->default(false);
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
        Schema::dropIfExists('modules.locations');
    }
};

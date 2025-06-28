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
        Schema::create('administration.delete_account_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on("administration.companies")->onDelete('cascade');
            $table->text('reason');
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
        Schema::dropIfExists('administration.delete_account_requests');
    }
};

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
        Schema::create('administration.bank_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->bigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on("administration.users")->onDelete('cascade');

            $table->bigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on("public.statuses")->onDelete('cascade');

            $table->bigInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on("administration.transactions")->onDelete('cascade');
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
        Schema::dropIfExists('bank_transfers');
    }
};

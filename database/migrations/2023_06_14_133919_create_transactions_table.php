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
        Schema::create('administration.transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on("administration.users")->onDelete('cascade');

            $table->bigInteger('plan_id')->nullable();
            $table->foreign('plan_id')->references('id')->on("administration.plans")->onDelete('cascade');

            $table->string('bill_code');
            $table->enum('type', ['paypal', 'bank_transfer','zinli','credit_card']);
            $table->bigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on("public.statuses")->onDelete('cascade');

            $table->bigInteger('approved_plan_status_id')->nullable();
            $table->foreign('approved_plan_status_id')->references('id')->on("public.statuses")->onDelete('cascade');
            $table->jsonb("data")->nullable();
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
        Schema::dropIfExists('administration.transactions');
    }
};

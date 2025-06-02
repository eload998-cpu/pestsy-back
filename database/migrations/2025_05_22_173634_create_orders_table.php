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
        Schema::create("modules.orders", function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("modules.clients")->onDelete('restrict');

            $table->bigInteger('worker_id');
            $table->foreign('worker_id')->references('id')->on("modules.workers")->onDelete('restrict');
            $table->string('date')->nullable();
            $table->text('direction')->nullable();
            $table->text('service_type')->nullable();
            $table->string('arrive_time')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('coordinator')->nullable();
            $table->string('origin')->nullable();

            $table->bigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on("public.statuses")->onDelete('restrict');
            $table->bigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on("administration.companies")->onDelete('cascade');
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('modules.orders');
    }
};

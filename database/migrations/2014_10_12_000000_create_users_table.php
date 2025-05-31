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

        Schema::create('administration.users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('cellphone')->nullable();
            $table->string('profile_picture')->nullable();
            $table->bigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('administration.cities')->onDelete('cascade');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->bigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('administration.companies')->onDelete('cascade');
            $table->bigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on("public.statuses")->onDelete('cascade');
            $table->string('oauth_google_id')->nullable();
            $table->string('oauth_facebook_id')->nullable();
            $table->string('paypal_subscription_id')->nullable();
            $table->boolean('active_subscription')->default(false);
            $table->timestamp('last_email_sent')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('administration.users');
    }
};

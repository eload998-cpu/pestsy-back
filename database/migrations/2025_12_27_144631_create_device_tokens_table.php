<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administration.device_tokens', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on("administration.users")->onDelete('restrict');

            $table->string('token', 512)->unique();
            $table->string('platform', 32)->nullable();   // android/ios/web
            $table->string('device_id', 128)->nullable(); // optional
            $table->timestamp('last_seen_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administration.device_tokens');
    }
};

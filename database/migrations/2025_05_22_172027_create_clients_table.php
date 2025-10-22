<?php

use App\Classes\SchemaBuilder;
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

    private SchemaBuilder $schema;

    public function __construct()
    {
        $this->schema = new SchemaBuilder();
    }

    public function up()
    {
        $this->schema->up('modules');
        Schema::create('modules.clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('date')->nullable();
            $table->enum('identification_type', ['legal_id', 'physical_id'])->nullable();
            $table->string('identification_number')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('cellphone')->nullable();
            $table->text('direction')->nullable();
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
        $this->schema->destroySchema('modules');
        Schema::dropIfExists('modules.clients');
    }
};

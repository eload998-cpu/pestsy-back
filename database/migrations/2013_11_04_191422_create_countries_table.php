<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Classes\SchemaBuilder;

return new class extends Migration
{

    private SchemaBuilder $schema;

    public function __construct()
    {
        $this->schema=new SchemaBuilder();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->up('administration');

        Schema::create('administration.countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
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

        $this->schema->destroySchema('administration');

        Schema::dropIfExists('countries');
    }
};

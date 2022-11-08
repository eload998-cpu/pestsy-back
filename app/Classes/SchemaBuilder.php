<?php
namespace App\Classes;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SchemaBuilder
{


    public function __construct()
    {
    }

    public function createSchemas(int $id)
    {

        $schema_name="module_{$id}";

        $statement = "CREATE SCHEMA {$schema_name}";
        DB::statement($statement);

        $this->createTables($schema_name);
    }

    public function up(string $name):void
    {
        $statement = "CREATE SCHEMA {$name}";
        DB::statement($statement);
    }

    public function down(string $name):void
    {
        $statement = "DROP SCHEMA {$name} CASCADE";
        DB::statement($statement);
        

        //drop modules
        $schemas=DB::table('information_schema.schemata')->where('schema_name', 'like','%module%')->get();

        foreach($schemas as $schema)
        {
            $statement = "DROP SCHEMA {$schema->schema_name} CASCADE";
            DB::statement($statement);
        }

    }

    private function createTables(string $schema_name):void
    {

        //
        Schema::create("{$schema_name}.clients", function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('identification_number')->nullable();
            $table->string('email')->unique();
            $table->string('cellphone');
            $table->text('direction');
            $table->text('code')->nullable();

            $table->timestamps();
        });

        Schema::create("{$schema_name}.workers", function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('identification_number')->nullable();
            $table->string('email')->unique();
            $table->string('cellphone');
            $table->text('direction');
            $table->text('code')->nullable();
            $table->timestamps();
        });


    
        Schema::create("{$schema_name}.orders", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });


        
        Schema::create("{$schema_name}.locations", function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.devices", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        
    }

}
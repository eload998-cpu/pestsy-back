<?php
namespace App\Classes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchemaBuilder
{

    public function __construct()
    {
    }

    public function createSchemas(int $id)
    {

        $schema_name = "module_{$id}";

        $statement = "CREATE SCHEMA {$schema_name}";
        DB::statement($statement);
        $this->createTables($schema_name);
    }

    public function up(string $name): void
    {
        $statement = "CREATE SCHEMA {$name}";
        DB::statement($statement);
    }

    public function down(string $name): void
    {
        $statement = "DROP SCHEMA {$name} CASCADE";
        DB::statement($statement);

        //drop modules
        $schemas = DB::table('information_schema.schemata')->where('schema_name', 'like', '%module%')->get();

        foreach ($schemas as $schema) {
            $statement = "DROP SCHEMA {$schema->schema_name} CASCADE";
            DB::statement($statement);
        }

    }

    private function createTables(string $schema_name): void
    {

        //
        Schema::create("{$schema_name}.clients", function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('date')->nullable();
            $table->enum('identification_type', ['legal_id', 'physical_id'])->nullable();
            $table->string('identification_number')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('cellphone')->nullable();
            $table->text('direction')->nullable();
            $table->timestamps();
        });

        Schema::create("{$schema_name}.client_emails", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("{$schema_name}.clients")->onDelete('cascade');
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create("{$schema_name}.workers", function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('date')->nullable();
            $table->string('identification_number')->nullable();
            $table->enum('identification_type', ['legal_id', 'physical_id'])->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('cellphone')->nullable();
            $table->text('direction')->nullable();
            $table->text('code')->nullable();
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

        Schema::create("{$schema_name}.pests", function (Blueprint $table) {
            $table->id();
            $table->string('common_name');
            $table->string('scientific_name');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.aplications", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.aplication_places", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.products", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('active_ingredient')->nullable();

            $table->timestamps();
        });

        //ORDERS

        Schema::create("{$schema_name}.orders", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('order_number');
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("{$schema_name}.clients")->onDelete('restrict');

            $table->bigInteger('worker_id');
            $table->foreign('worker_id')->references('id')->on("{$schema_name}.workers")->onDelete('restrict');
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

            $table->bigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on("administration.users")->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.external_conditions", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('obsolete_machinery')->nullable();
            $table->string('sewer_system')->nullable();
            $table->string('debris')->nullable();
            $table->string('containers')->nullable();
            $table->string('spotlights')->nullable();
            $table->string('green_areas')->nullable();
            $table->string('waste')->nullable();
            $table->string('nesting')->nullable();

            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.internal_conditions", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('walls')->nullable();
            $table->string('floors')->nullable();
            $table->string('cleaning')->nullable();
            $table->string('windows')->nullable();
            $table->string('storage')->nullable();
            $table->string('space')->nullable();
            $table->string('evidences')->nullable();
            $table->string('roofs')->nullable();
            $table->string('sealings')->nullable();
            $table->string('closed_doors')->nullable();
            $table->string('pests_facilities')->nullable();
            $table->string('garbage_cans')->nullable();
            $table->string('equipment')->nullable();
            $table->string('ventilation')->nullable();
            $table->string('ducts')->nullable();
            $table->string('clean_walls')->nullable();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.infestation_grades", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('german_cockroaches')->nullable();
            $table->string('flies')->nullable();
            $table->string('bees')->nullable();
            $table->string('termites')->nullable();
            $table->string('fleas')->nullable();
            $table->string('moths')->nullable();
            $table->string('weevils')->nullable();
            $table->string('american_cockroaches')->nullable();
            $table->string('ants')->nullable();
            $table->string('spiders')->nullable();
            $table->string('rodents')->nullable();
            $table->string('fire_ants')->nullable();
            $table->string('stilt_walkers')->nullable();
            $table->string('others')->nullable();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.fumigations", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->bigInteger('aplication_id');
            $table->foreign('aplication_id')->references('id')->on("{$schema_name}.aplications")->onDelete('restrict');
            $table->bigInteger('aplication_place_id');
            $table->foreign('aplication_place_id')->references('id')->on("{$schema_name}.aplication_places")->onDelete('restrict');
            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on("{$schema_name}.products")->onDelete('restrict');
            $table->bigInteger('dose');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.control_of_rodents", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('device_id');
            $table->foreign('device_id')->references('id')->on("{$schema_name}.devices")->onDelete('restrict');
            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on("{$schema_name}.products")->onDelete('restrict');
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->bigInteger('device_number');
            $table->bigInteger('location_id');
            $table->foreign('location_id')->references('id')->on("{$schema_name}.locations")->onDelete('restrict');
            $table->string('aceptable_cleaning');
            $table->string('finished_cleaning');
            $table->string('bait_status');
            $table->bigInteger('dose');
            $table->string('activity');
            $table->boolean('cleaning')->default(false)->nullable();
            $table->boolean('bait_change')->default(false)->nullable();
            $table->text('observation');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.pest_bitacores", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('pest_id');
            $table->foreign('pest_id')->references('id')->on("{$schema_name}.pests")->onDelete('restrict');
            $table->bigInteger('control_of_rodent_id');
            $table->foreign('control_of_rodent_id')->references('id')->on("{$schema_name}.control_of_rodents")->onDelete('restrict');
            $table->string('quantity');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.lamps", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->string('lamp_not_working');
            $table->integer('station_number');
            $table->string('rubbery_iron_changed');
            $table->string('fluorescent_change')->nullable();
            $table->string('observation')->nullable();
            $table->string('lamp_cleaning');
            $table->integer('quantity_replaced')->nullable();
            $table->timestamps();
        });

        Schema::create("{$schema_name}.traps", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->integer('station_number');
            $table->bigInteger('device_id');
            $table->foreign('device_id')->references('id')->on("{$schema_name}.devices")->onDelete('restrict');
            $table->string('pheromones');
            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on("{$schema_name}.products")->onDelete('restrict');
            $table->bigInteger('dose');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.observations", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->text('observation');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.images", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->string('file_name');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.signatures", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("{$schema_name}.orders")->onDelete('cascade');
            $table->string('client_signature_url');
            $table->string('worker_signature_url');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.permissions", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.technical_sheets", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.msds", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.technical_staff", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.min_salud", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.reports", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("{$schema_name}.clients")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.management_plans", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("{$schema_name}.clients")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.sketchs", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("{$schema_name}.clients")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.labels", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.mip", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("{$schema_name}.clients")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.trends", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->string('name');
            $table->string('file_url');
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("{$schema_name}.clients")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.operators", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('worker_id');
            $table->foreign('worker_id')->references('id')->on("{$schema_name}.workers")->onDelete('cascade');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('administration.users')->onDelete('cascade');
            $table->bigInteger('administrator_id');
            $table->foreign('administrator_id')->references('id')->on('administration.users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create("{$schema_name}.system_users", function (Blueprint $table) use ($schema_name) {
            $table->id();
            $table->bigInteger('client_id');
            $table->foreign('client_id')->references('id')->on("{$schema_name}.clients")->onDelete('cascade');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('administration.users')->onDelete('cascade');
            $table->bigInteger('administrator_id');
            $table->foreign('administrator_id')->references('id')->on('administration.users')->onDelete('cascade');
        });

        updateConnectionSchema($schema_name);

        Artisan::call('db:seed', [
            '--class' => 'MasterSeeder',
        ]);

    }

}

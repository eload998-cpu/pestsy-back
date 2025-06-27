<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        updateConnectionSchema('administration');
        Permission::truncate();

        $permissions =
            [

            "list dashboard",
            "create dashboard",
            "edit dashboard",
            "delete dashboard",

            "list client",
            "create client",
            "edit client",
            "add file client",
            "delete client",
            "create_operator",
            "create_system_user",

            "list worker",
            "create worker",
            "edit worker",
            "delete worker",

            "list pest",
            "create pest",
            "edit pest",
            "delete pest",

            "list device",
            "create device",
            "edit device",
            "delete device",

            "list product",
            "create product",
            "edit product",
            "delete product",

            "list aplication",
            "create aplication",
            "edit aplication",
            "delete aplication",

            "list location",
            "create location",
            "edit location",
            "delete location",

            "list aplication place",
            "create aplication place",
            "edit aplication place",
            "delete aplication place",

            "list order",
            "create order",
            "edit order",
            "delete order",
            "download order",

            "list image",
            "create image",
            "edit image",
            "delete image",

            "list fumigation",
            "create fumigation",
            "edit fumigation",
            "delete fumigation",

            "list monitor",
            "create monitor",
            "edit monitor",
            "delete monitor",

            "list rodent control",
            "create rodent control",
            "edit rodent control",
            "delete rodent control",

            "list lamp",
            "create lamp",
            "edit lamp",
            "delete lamp",

            "list trap",
            "create trap",
            "edit trap",
            "delete trap",

            "list observation",
            "create observation",
            "edit observation",
            "delete observation",

            "list signature",
            "create signature",
            "edit signature",
            "delete signature",

            "list file",
            "create file",
            "edit file",
            "delete file",
            "download file",

            "list permission",
            "create permission",
            "edit permission",
            "delete permission",
            "download permission",

            "list msds",
            "create msds",
            "edit msds",
            "delete msds",
            "download msds",

            "list min salud",
            "create min salud",
            "edit min salud",
            "delete min salud",
            "download min salud",

            "list technical sheet",
            "create technical sheet",
            "edit technical sheet",
            "delete technical sheet",
            "download technical sheet",

            "list technical staff",
            "create technical staff",
            "edit technical staff",
            "delete technical staff",
            "download technical staff",

            "list label",
            "create label",
            "edit label",
            "delete label",
            "download label",

            "list sketch",
            "create sketch",
            "edit sketch",
            "delete sketch",
            "download sketch",

            "list trend",
            "create trend",
            "edit trend",
            "delete trend",
            "download trend",

            "list report",
            "create report",
            "edit report",
            "delete report",
            "download report",

            "list mip",
            "create mip",
            "edit mip",
            "delete mip",
            "download mip",

            "list management plan",
            "create management plan",
            "edit management plan",
            "delete management plan",
            "download management plan",

            "pay subscription",

            "list transactions",
            "create transactions",
            "delete transactions",

            "list_configuration",

        ];

        $role = Role::where('name', 'super_administrator')->first();

        foreach ($permissions as $key => $value) {
            $permission_model = Permission::create(
                [
                    "name"         => str_replace(" ", "_", $value),
                    "display_name" => $value,
                ]);

            $role->permissions()->attach($permission_model->id);
        }

        $role = Role::where('name', 'administrator')->first();

        foreach ($permissions as $key => $value) {
            $permission_model = Permission::where(
                [
                    "name" => str_replace(" ", "_", $value),
                ])->first();

            $role->permissions()->attach($permission_model->id);
        }

        $permissions =
            [

            "list order",
            "download order",

            "list permission",
            "download permission",

            "list msds",
            "download msds",

            "list min salud",
            "download min salud",

            "list technical sheet",
            "download technical sheet",

            "list technical staff",
            "download technical staff",

            "list label",
            "download label",

            "list sketch",
            "download sketch",

            "list trend",
            "download trend",

            "list report",
            "download report",

            "list mip",
            "download mip",

            "list management plan",
            "download management plan",

        ];

        $role = Role::where('name', 'system_user')->first();

        foreach ($permissions as $key => $value) {

            $permission_model = Permission::where(
                [
                    "name" => str_replace(" ", "_", $value),
                ])->first();

            $role->permissions()->attach($permission_model->id);
        }

        $permissions =
            [
            "list client",
            "create client",

            "create pest",
            "list pest",

            "create device",
            "list device",

            "create product",
            "list product",

            "create aplication",
            "list aplication",

            "create location",
            "list location",

            "create aplication place",
            "list aplication place",

            "list order",
            "create order",
            "edit order",
            "download order",

            "list image",
            "create image",
            "edit image",

            "list fumigation",
            "create fumigation",
            "edit fumigation",
            "delete fumigation",

            "list monitor",
            "create monitor",
            "edit monitor",
            "delete monitor",

            "list rodent control",
            "create rodent control",
            "edit rodent control",
            "delete rodent control",

            "list lamp",
            "create lamp",
            "edit lamp",
            "delete lamp",

            "list trap",
            "create trap",
            "edit trap",
            "delete trap",

            "list observation",
            "create observation",
            "edit observation",
            "delete observation",

            "list signature",
            "create signature",
            "edit signature",
            "delete signature",

        ];

        $role = Role::where('name', 'operator')->first();

        foreach ($permissions as $key => $value) {
            $permission_model = Permission::where(
                [
                    "name" => str_replace(" ", "_", $value),
                ])->first();

            $role->permissions()->attach($permission_model->id);
        }

        $permissions =
            [

            "list dashboard",
            "create dashboard",
            "edit dashboard",
            "delete dashboard",

            "list client",
            "create client",
            "edit client",
            "delete client",

            "list worker",
            "create worker",
            "edit worker",
            "delete worker",

            "list pest",
            "create pest",
            "edit pest",
            "delete pest",

            "list device",
            "create device",
            "edit device",
            "delete device",

            "list product",
            "create product",
            "edit product",
            "delete product",

            "list aplication",
            "create aplication",
            "edit aplication",
            "delete aplication",

            "list location",
            "create location",
            "edit location",
            "delete location",

            "list aplication place",
            "create aplication place",
            "edit aplication place",
            "delete aplication place",

            "list order",
            "create order",
            "edit order",
            "delete order",
            "download order",

            "list image",
            "create image",
            "edit image",
            "delete image",

            "list fumigation",
            "create fumigation",
            "edit fumigation",
            "delete fumigation",

            "list monitor",
            "create monitor",
            "edit monitor",
            "delete monitor",

            "list rodent control",
            "create rodent control",
            "edit rodent control",
            "delete rodent control",

            "list lamp",
            "create lamp",
            "edit lamp",
            "delete lamp",

            "list trap",
            "create trap",
            "edit trap",
            "delete trap",

            "list observation",
            "create observation",
            "edit observation",
            "delete observation",

            "list signature",
            "create signature",
            "edit signature",
            "delete signature",

            "pay subscription",
            "list_configuration",
        ];

        $role = Role::where('name', 'fumigator')->first();

        foreach ($permissions as $key => $value) {
            $permission_model = Permission::where(
                [
                    "name" => str_replace(" ", "_", $value),
                ])->first();

            $role->permissions()->attach($permission_model->id);
        }

    }
}

<?php
namespace Database\Seeders;

use App\Models\Module\AffectedElement;
use App\Models\Module\Aplication;
use App\Models\Module\AplicationPlace;
use App\Models\Module\AppliedTreatment;
use App\Models\Module\ConstructionType;
use App\Models\Module\DesinfectionMethod;
use App\Models\Module\Device;
use App\Models\Module\Location;
use App\Models\Module\Pest;
use App\Models\Module\Product;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Aplication::truncate();
        AplicationPlace::truncate();
        Device::truncate();
        Location::truncate();
        Pest::truncate();
        Product::truncate();
        AffectedElement::truncate();
        AppliedTreatment::truncate();
        ConstructionType::truncate();
        DesinfectionMethod::truncate();

        $directoryPath = storage_path() . "/app/storage/masters/"; // replace with your directory path

        $files_url = [
            $directoryPath . "aplicaciones.php",
            $directoryPath . "dispositivos.php",
            $directoryPath . "lugar_aplicaciones.php",
            $directoryPath . "plagas.php",
            $directoryPath . "productos.php",
            $directoryPath . "ubicaciones.php",
            $directoryPath . "elementos_afectados.php",
            $directoryPath . "tipos_de_construccion.php",
            $directoryPath . "tratamientos_aplicados.php",
            $directoryPath . "metodos_desinfeccion.php",

        ];

        foreach ($files_url as $f) {
            include $f;
            $arrayName = basename($f, '.php'); // For example: 'aplicaciones'

            if (isset($$arrayName)) { // Using variable variables to dynamically access array

                foreach ($$arrayName as $row) {

                    switch ($arrayName) {
                        case 'aplicaciones':

                            Aplication::create([
                                "name"       => $row["nombre"],
                                "is_general" => true,
                            ]);

                            break;

                        case 'lugar_aplicaciones':

                            AplicationPlace::create([
                                "name"       => $row["nombre"],
                                "is_general" => true,

                            ]);

                            break;

                        case 'dispositivos':

                            Device::create([
                                "name"       => $row["nombre"],
                                "is_general" => true,

                            ]);

                            break;

                        case 'plagas':

                            Pest::create([
                                "scientific_name" => $row["nombre_cientifico"],
                                "common_name"     => $row["nombre_comun"],
                                "is_general"      => true,
                                "is_xylophagus"   => isset($row["is_xylophagus"]) ? true : false,
                            ]);

                            break;

                        case 'productos':

                            Product::create([
                                "name"              => $row["nombre"],
                                "code"              => $row["numero_de_registro"],
                                "active_ingredient" => $row["ingrediente_activo"],
                                "is_general"        => true,

                            ]);

                            break;

                        case 'ubicaciones':

                            Location::create([
                                "name"       => $row["ubicacion"],
                                "is_general" => true,

                            ]);

                            break;

                        case 'elementos_afectados':

                            AffectedElement::create([
                                "name"       => $row["nombre"],
                                "is_general" => true,

                            ]);

                            break;
                        case 'tipos_de_construccion':

                            ConstructionType::create([
                                "name"       => $row["nombre"],
                                "is_general" => true,

                            ]);

                            break;

                        case 'tratamientos_aplicados':

                            AppliedTreatment::create([
                                "name"       => $row["nombre"],
                                "is_general" => true,

                            ]);

                            break;

                        case 'metodos_desinfeccion':

                            DesinfectionMethod::create([
                                "name"       => $row["nombre"],
                                "is_general" => true,

                            ]);

                            break;

                    }

                }
            }

        }

    }
}

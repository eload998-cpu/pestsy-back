<?php
namespace Database\Seeders;

use App\Models\Module\SafetyControl;
use Illuminate\Database\Seeder;

class SafetyControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        updateConnectionSchema('modules');

        SafetyControl::truncate();
        $fumigation_controls = [
            ['id' => '1', 'name' => 'Dosis correcta'],
            ['id' => '2', 'name' => 'Producto autorizado'],
            ['id' => '3', 'name' => 'Método de aplicación correcto'],

            ['id' => '4', 'name' => 'Área sellada correctamente'],
            ['id' => '5', 'name' => 'Tiempo de exposición adecuado'],
            ['id' => '6', 'name' => 'Equipo de protección usado'],
            ['id' => '7', 'name' => 'Área ventilada después del tratamiento'],

            ['id' => '8', 'name' => 'Plazo de reingreso respetado'],
            ['id' => '9', 'name' => 'Producto dentro de la fecha de expiración'],
            ['id' => '10', 'name' => 'Registro de lote y código del producto'],
            ['id' => '11', 'name' => 'Condiciones previas verificadas'],
            ['id' => '12', 'name' => 'Condiciones posteriores verificadas'],

            ['id' => '13', 'name' => 'Aviso de fumigación colocado'],
            ['id' => '14', 'name' => 'Residuos recogidos correctamente'],
            ['id' => '15', 'name' => 'Plan de emergencia disponible'],
        ];

        foreach ($fumigation_controls as $control) {
            SafetyControl::create($control);
        }

    }
}

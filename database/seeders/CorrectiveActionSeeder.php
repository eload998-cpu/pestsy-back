<?php
namespace Database\Seeders;

use App\Models\Module\CorrectiveAction;
use Illuminate\Database\Seeder;

class CorrectiveActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $correctiveActions = [
            'Limpieza profunda del área afectada',
            'Eliminación de residuos y fuentes de alimento',
            'Sellado de grietas y accesos',
            'Instalación de trampas adicionales',
            'Aumento de frecuencia de monitoreo',
            'Reubicación de estaciones de control',
            'Refuerzo de medidas de higiene',
            'Aplicación de gel insecticida en puntos críticos',
            'Nebulización localizada',
            'Colocación de trampas de luz UV',
            'Rotación de cebos rodenticidas',
            'Sustitución de cebos consumidos',
            'Tratamiento químico en zonas infestadas',
            'Aplicación de barrera perimetral',
            'Reparación de sellos de puertas o ventanas',
            'Instalación de mallas anti-insectos',
            'Desobstrucción de desagües o canales',
            'Mejoras en almacenamiento de alimentos',
            'Optimización del sistema de ventilación',
            'Control de humedad en zonas críticas',
            'Capacitación al personal sobre manejo higiénico',
            'Revisión de procedimientos de limpieza',
            'Registro de nueva incidencia en el sistema',
            'Notificación a mantenimiento interno',
            'Actualización del plan de control de plagas',
        ];

        foreach ($correctiveActions as $key => $value) {
            CorrectiveAction::create([
                "name"       => $value,
                "is_general" => true,
            ]);
        }
    }
}

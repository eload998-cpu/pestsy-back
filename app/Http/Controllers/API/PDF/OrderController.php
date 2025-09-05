<?php
namespace App\Http\Controllers\API\PDF;

use App\Http\Controllers\Controller;
use App\Models\Module\Order;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PDF;

class OrderController extends Controller
{

    // Download
    public function download($id)
    {
        updateConnectionSchema("administration");

        expiredAccountMessage();

        $order_status_type = StatusType::where('name', 'order')->first();
        $order_status      = Status::where('status_type_id', $order_status_type->id)->where('name', 'completed')->first();
        $user              = Auth::user();
        $user_role         = $user->roles()->first()->name;

        updateConnectionSchema("modules");

        // retreive all records from db
        $order = Order::find($id);

        if ($order->status_id != $order_status->id) {
            abort(401);
        }

        $order->load('user.company');
        $order->load('client');
        $order->load('worker');

        if ($order->service_type == "Xilofago") {
            $order->load('xylophageControl', 'xylophageControl.product', 'xylophageControl.location', 'xylophageControl.pest', 'xylophageControl.application', 'xylophageControl.constructionType', 'xylophageControl.affectedElement', 'xylophageControl.worker', 'xylophageControl.correctiveActions.correctiveAction');

        }

        if ($order->service_type == "Legionela") {
            $order->load('legionellaControl', 'legionellaControl.location', 'legionellaControl.application', 'legionellaControl.worker', 'legionellaControl.correctiveActions.correctiveAction');

        }

        if ($order->service_type == "Control de roedores") {

            $order->load('rodentControls', 'rodentControls.worker', 'rodentControls.device', 'rodentControls.product', 'rodentControls.location', 'rodentControls.pestBitacores.pest', 'rodentControls.correctiveActions.correctiveAction');
        }

        if ($order->service_type == "Fumigación") {

            $order->load('fumigations', 'fumigations.worker', 'fumigations.aplication', 'fumigations.location', 'fumigations.product');
        }

        if ($order->service_type == "Monitoreo de voladores (lámparas)") {

            $order->load('lamps', 'lamps.worker', 'lamps.correctiveActions.correctiveAction');
        }

        if ($order->service_type == "Monitoreo de insectos") {

            $order->load('traps', 'traps.correctiveActions.correctiveAction', 'traps.product', 'traps.worker', 'traps.location', 'traps.device');
        }

        if ($order->service_type == "General") {

            $order->load('externalCondition');
            $order->load('internalCondition');
            $order->load('rodentControls', 'rodentControls.worker', 'rodentControls.device', 'rodentControls.product', 'rodentControls.location', 'rodentControls.pestBitacores.pest', 'rodentControls.correctiveActions.correctiveAction');
            $order->load('fumigations', 'fumigations.worker', 'fumigations.aplication', 'fumigations.location', 'fumigations.product');
            $order->load('lamps', 'lamps.worker', 'lamps.correctiveActions.correctiveAction');
            $order->load('traps', 'traps.correctiveActions.correctiveAction', 'traps.product', 'traps.worker', 'traps.location', 'traps.device');
            $order->load('infestationGrade');
        }

        $order->load('observations');
        $order->load('signatures');
        $order->load('images');

        $orderProducts        = $this->getProducts($order);
        $order                = $order->toArray();
        $user_role            = $user->roles()->first()->name;
        $user["subscription"] = $user->subscriptions()->latest()->first();
        $order["logo"]        = $user->company->logo;
        $order["products"]    = $orderProducts;

        $PDFOptions = ['enable_remote' => true];

        $file_name = $order["order_number"] . ".pdf";
        $headers   = [
            'Content-Description'           => 'File Transfer',
            'Content-Disposition'           => 'attachment; filename=' . $file_name . '',
            'Content-Type'                  => 'application/zip, application/octet-stream',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ];
        // share data to view
        $pdf = PDF::setOptions($PDFOptions)->loadView('pdfs.index', ["order" => $order]);
        // download PDF file with download method

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, $headers);
    }

    private function getProducts(Order $order)
    {

        $rcProducts = $order->rodentControls
            ->map(function ($rc) {
                $p = $rc->product;
                if (! $p) {
                    return null;
                }

                $p->dose = $rc->dose;
                return $p;
            })
            ->filter();

        $trapProducts = $order->traps
            ->map(function ($trap) {
                $p = $trap->product;
                if (! $p) {
                    return null;
                }

                $p->dose = $trap->dose;
                return $p;
            })
            ->filter();

        $fumigationProducts = $order->fumigations
            ->map(function ($fum) {
                $p = $fum->product;
                if (! $p) {
                    return null;
                }

                $p->dose = $fum->dose;
                return $p;
            })
            ->filter();

        $xylophaguProducts = $order->xylophageControl
            ->map(function ($fum) {
                $p = $fum->product;
                if (! $p) {
                    return null;
                }

                $p->dose = $fum->dose;
                return $p;
            })
            ->filter();

        $legionellaProducts = $order->legionellaControl
            ->map(function ($fum) {
                $p = $fum->product;
                if (! $p) {
                    return null;
                }

                $p->dose = $fum->dose;
                return $p;
            })
            ->filter();

        $allProducts = $rcProducts
            ->merge($trapProducts)
            ->merge($fumigationProducts)
            ->merge($xylophaguProducts)
            ->merge($legionellaProducts)
            ->unique('id')
            ->values();

        return $allProducts;

    }

}

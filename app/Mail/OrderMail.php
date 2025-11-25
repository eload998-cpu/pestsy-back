<?php
namespace App\Mail;

use App\Models\Administration\Company;
use App\Models\Module\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use PDF;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $company_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $company_name)
    {
        $this->order        = $order;
        $this->company_name = $company_name;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Orden de servicio',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.order.index',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return \Illuminate\Mail\Mailables\Attachment[]
     */
    public function attachments()
    {

        $user = Auth::user();

        updateConnectionSchema("modules");

        // retreive all records from db
        $order = Order::find($this->order->id);
        $order->load('user');
        $order->load('client');
        $order->load('worker');
        $order->load('observations');
        $order->load('signatures');
        $order->load('images');
        $order->load('externalConditions.externalCondition');
        $order->load('internalConditions.internalCondition');
        $order->load('rodentControls', 'rodentControls.worker', 'rodentControls.application', 'rodentControls.device', 'rodentControls.product', 'rodentControls.location', 'rodentControls.pestBitacores.pest', 'rodentControls.correctiveActions.correctiveAction');
        $order->load('fumigations', 'fumigations.worker', 'fumigations.aplication', 'fumigations.location', 'fumigations.product', 'fumigations.correctiveActions.correctiveAction', 'fumigations.safetyControls.safetyControl');
        $order->load('lamps', 'lamps.worker', 'lamps.product', 'lamps.correctiveActions.correctiveAction');
        $order->load('traps', 'traps.correctiveActions.correctiveAction', 'traps.product', 'traps.worker', 'traps.location', 'traps.device');
        $order->load('infestationGrade');
        $order->load('legionellaControl', 'legionellaControl.location', 'legionellaControl.application', 'legionellaControl.worker', 'legionellaControl.correctiveActions.correctiveAction');
        $order->load('xylophageControl', 'xylophageControl.product', 'xylophageControl.location', 'xylophageControl.pest', 'xylophageControl.application', 'xylophageControl.constructionType', 'xylophageControl.affectedElement', 'xylophageControl.worker', 'xylophageControl.correctiveActions.correctiveAction');

        $orderProducts = $this->getProducts($order);

        $order = $order->toArray();

        $user_role            = $user->roles()->first()->name;
        $user["subscription"] = $user->subscriptions()->latest()->first();
        $order["logo"]        = $user->company->logo;
        $order["products"]    = $orderProducts;

        $PDFOptions = ['enable_remote' => true];

        // share data to view
        $pdf = PDF::setOptions($PDFOptions)->loadView('pdfs.index', ["order" => $order]);
        // download PDF file with download method

        return [
            Attachment::fromData(fn() => $pdf->output(), $order["order_number"] . '.pdf')
                ->withMime('application/pdf'),
        ];
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

        $lampProducts = $order->lamps
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
            ->merge($lampProducts)
            ->unique('id')
            ->values();

        return $allProducts;

    }
}

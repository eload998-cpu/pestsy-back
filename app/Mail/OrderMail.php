<?php

namespace App\Mail;

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
        $this->order = $order;
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
        $user_role = $user->roles()->first()->name;

        $module_name = $user->module_name;
        updateConnectionSchema($module_name);

        // retreive all records from db
        $order = Order::find($this->order->id);
        $order->load('user');
        $order->load('client');
        $order->load('worker');
        $order->load('externalCondition');
        $order->load('internalCondition');
        $order->load('rodentControls', 'rodentControls.device', 'rodentControls.product', 'rodentControls.location', 'rodentControls.pestBitacores.pest');
        $order->load('fumigations', 'fumigations.aplication', 'fumigations.aplicationPlace', 'fumigations.product');
        $order->load('lamps');
        $order->load('traps', 'traps.product', 'traps.device');
        $order->load('infestationGrade');
        $order->load('observations');
        $order->load('signatures');
        $order->load('images');

        $order = $order->toArray();

        $user_role = $user->roles()->first()->name;
        $user["subscription"] = $user->subscriptions()->latest()->first();


        switch ($user_role) {

            case 'operator':
                updateConnectionSchema($module_name);

                $administrator = User::find($user->operators()->first()->id);
                $order["logo"] = $administrator->company->logo;

                break;

            case 'system_user':
                updateConnectionSchema($module_name);

                $administrator = User::find($user->systemUsers()->first()->id);
                $order["logo"] = $administrator->company->logo;

                break;

            default:
                if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {
                    $order["logo"] = $user->company->logo;

                }
                break;

        }

        $PDFOptions = ['enable_remote' => true];

        // share data to view
        $pdf = PDF::setOptions($PDFOptions)->loadView('pdfs.index', ["order" => $order]);
        // download PDF file with download method

        return [
            Attachment::fromData(fn() => $pdf->output(), $order["order_number"] . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

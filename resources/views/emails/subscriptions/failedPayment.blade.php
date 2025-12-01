@extends('layouts.emails')
@section('title', 'Su pago ha sido rechazado')
@section('header')
    @include('emails.header', ['headerText' => 'Su pago ha sido rechazado'])
@endsection
@section('content')
    <tr>
        <td align="center">

            <div class="main-title" style="margin-top:16px;">
                <span style="font-size:30px; font-weight:700;">Estimado/a cliente</span>
            </div>

            <div class="description">
                <table width="100%" height="91" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:16px;">
                    <tr>

                        <td align="center" valign="middle" style="padding:30px;">

                            <div style="text-align:center;">

                                Le notificamos que su pago fue <b>rechazado</b> , le sugerimos renovar su suscripción lo más
                                pronto
                                posible para continuar utilizando las características <b>Premium</b> del sistema


                            </div>




                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td align="center">

            <div class="commerce-details">
                <table width="100%" height="42" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:20px;">
                    <tr>
                        <td align="center" valign="middle">
                            <b>Detalles de la transacción</b>
                        </td>
                    </tr>
                </table>


                <table width="100%" height="42" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:10px;">
                    <tr>
                        <td width="50%" style="padding-left:16px;">
                            <b>Referencia:</b>
                        </td>
                        <td width="50%" style="padding-left:16px;">
                            {{ $transactionId }}
                        </td>
                    </tr>
                </table>

                <table width="100%" height="42" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:10px;">
                    <tr>
                        <td width="50%" style="padding-left:16px;">
                            <b>Plan</b>
                        </td>
                        <td width="50%" style="padding-left:16px;">
                            {{ $planName }}
                        </td>
                    </tr>
                </table>

                <table width="100%" height="42" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:10px;">
                    <tr>
                        <td width="50%" style="padding-left:16px;">
                            <b>Monto</b>
                        </td>
                        <td width="50%" style="padding-left:16px;">
                            ${{ $data['shipping_amount']['value'] }}
                        </td>
                    </tr>
                </table>

                <table width="100%" height="42" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:10px;">
                    <tr>
                        <td width="50%" style="padding-left:16px;">
                            <b>Fecha</b>
                        </td>
                        <td width="50%" style="padding-left:16px;">
                            {{ $lastFailedPayment }}
                        </td>
                    </tr>
                </table>
            </div>


        </td>
    </tr>
@endsection
@section('footer')
    @include('emails.footer')
@endsection

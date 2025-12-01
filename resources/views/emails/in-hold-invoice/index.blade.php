@extends('layouts.emails')
@section('title', 'Recibo de pago')
@section('header')
    @include('emails.header', ['headerText' => 'Recibo de pago'])
@endsection
@section('content')
    <tr>
        <td align="center">

            <div class="main-title" style="margin-top:16px;">
                <span style="font-size:30px; font-weight:700;">Hola, {{ ucwords($user->first_name) }}</span>
            </div>

            <div class="description">
                <table width="100%" height="91" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:16px;">
                    <tr>

                        <td align="center" valign="middle" style="padding:30px;">

                            <div style="text-align:center;">
                                <b>Deseandole exito en sus labores,</b> le notificamos que nos encontramos verificando
                                su transferencia, esto puede tomar unos minutos
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
                            {{ $transaction->bill_code }}
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
                            {{ $plan->name }}
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
                            @if ($transaction->type == 'bank_transfer')
                                {{ $transactionAmount }} BS
                            @else
                                ${{ $plan->price }}
                            @endif
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
                            {{ $transaction->created_at->format('d/m/Y') }}
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

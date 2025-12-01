@extends('layouts.emails')
@section('title', 'Datos de transacción')
@section('header')
    @include('emails.header', ['headerText' => 'Datos de transacción'])
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

                                @if ($transaction->type == 'bank_transfer' || $transaction->type == 'zinli')
                                    <b>Deseandole exito en sus labores,</b> le notificamos que el
                                    numero de referencia que nos ha proveido <b>no ha sido encontrado.</b>
                                    <br><br>
                                    Le recomendamos enviar nuevamente el numero de referencia en caso de que
                                    haya
                                    ocurrido algun error de transcripción.
                                @else
                                    <b>Deseandole exito en sus labores,</b> le notificamos que su pago
                                    ha sido <b>rechazado</b> <br>
                                    Por favor verifique la transacción en caso de error y realice el pago
                                    nuevamente
                                    para renovar su plan.
                                @endif

                            </div>




                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    @if ($transaction->type == 'bank_transfer' || $transaction->type == 'zinli')

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
                                {{ $transaction->bankTransfers()->latest()->first()->reference }}
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
    @endif
@endsection
@section('footer')
    @include('emails.footer')
@endsection

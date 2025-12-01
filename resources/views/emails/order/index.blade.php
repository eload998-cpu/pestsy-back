@extends('layouts.emails')
@section('title', 'Orden de servicio')
@section('header')
    @include('emails.header', ['headerText' => 'Orden de servicio'])
@endsection
@section('content')
    <tr>
        <td align="center">

            <div class="main-title" style="margin-top:16px;">
                <span style="font-size:30px; font-weight:700;">Estimado/a, {{ ucwords($order->client->first_name) }}</span>
            </div>

            <div class="description">
                <table width="100%" height="91" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:16px;">
                    <tr>


                        <td align="center" valign="middle" style="padding:30px;">

                            <div style="text-align:center;">
                                <b> Queremos agradecerle por elegir a <b>{{ ucwords($company_name) }}</b> para proteger su
                                    espacio</b>,<br>
                                Adjunto a este correo, encontrará el documento PDF de la Orden de Servicio
                                <b>#{{ $order->order_number }}</b>, que resume el trabajo realizado.
                            </div>




                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td align="center">


            <div class="message">
                <table width="100%" height="42" cellpadding="0" cellspacing="0" style="margin-top:20px;">
                    <tr>
                        <td align="center" valign="middle">
                            Si tiene alguna <b>pregunta</b> sobre el informe o necesita agendar su próxima visita de
                            mantenimiento,
                            no dude en contactarnos.
                        </td>
                    </tr>

                    <tr>
                        <td align="center" valign="middle">
                            ¡Estamos para servirle!
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

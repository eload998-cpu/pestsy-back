@extends('layouts.emails')

@section('title', 'Datos de transacción')

@section('header')
    @include('emails.header', ['headerText' => 'Datos de transacción'])
@endsection

@section('content')
    <center style="width: 100%; background-color: #F0F3F4;">
        <div
            style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;font-family: Open Sans, Roboto, sans-serif;">
        </div>
        <div style="max-width: 600px; margin: 0 auto; background-color:#F0F3F4" class="email-container">


            <!-- BEGIN BODY -->
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin-top:50px !important;">
                <tr>

                <tr>
                    <td valign="middle" class="hero bg_white" style="padding: 1rem ;background: #F0F3F4;">
                        <table>
                            <tr>
                                <td>

                                    @if ($transaction->type == 'bank_transfer' || $transaction->type == 'zinli')
                                        <div class=""
                                            style="
                                          font-family: Open Sans, Roboto, sans-serif;
                                          font-style: normal;
                                          font-weight: 400;
                                          font-size: 22px;
                                          line-height: 29px;
                                          max-width: 500px;
                                          text-align: center;">
                                            Deseandole exito en sus
                                            labores, le notificamos que el
                                            numero de referencia que nos ha proveido no ha sido encontrado
                                            <br><br>
                                            Le recomendamos enviar nuevamente el numero de referencia en caso de que
                                            haya
                                            ocurrido algun error de transcripción.
                                            <br><br>

                                            <div>
                                                <table>
                                                    <tr>
                                                        <td><b>Referencia: </b></td>
                                                        <td> {{ $transaction->bankTransfers()->latest()->first()->reference }}
                                                        </td>

                                                    </tr>


                                                    <tr>
                                                        <td><b>Plan: </b></td>
                                                        <td> {{ $plan->name }}
                                                        </td>

                                                    </tr>

                                                    <tr>
                                                    <td><b>Monto: </b></td>

                                                    @if($transaction->type=="bank_transfer")
                                                    <td> {{ $transactionAmount }} BS
                                                    </td>
                                                    @else
                                                    <td> ${{ $plan->price }}
                                                    </td>
                                                    @endif

                                                </tr>

                                                    <tr>
                                                        <td><b>Fecha: </b></td>
                                                        <td> {{ $transaction->created_at->format('d/m/Y') }}
                                                        </td>

                                                    </tr>
                                                </table>

                                            </div>
                                        </div>
                                    @else
                                        <div class=""
                                            style="
                                          font-family: Open Sans, Roboto, sans-serif;
                                          font-style: normal;
                                          font-weight: 400;
                                          font-size: 22px;
                                          line-height: 29px;
                                          max-width: 500px;
                                          text-align: center;">
                                            Deseandole exito en sus
                                            labores, le notificamos que su pago
                                            ha sido rechazado<br><br>
                                            Por favor verifique la transacción en caso de error y realice el pago
                                            nuevamente
                                            para renovar su plan.
                                        </div>
                                    @endif


                                </td>
                            </tr>


                        </table>
                    </td>
                </tr>
            </table>


        </div>


    </center>
@endsection

@section('footer')
    @include('emails.footer')
@endsection

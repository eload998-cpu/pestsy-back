@extends('layouts.emails')

@section('title', 'Recibo de pago')

@section('header')
    @include('emails.header', ['headerText' => 'Recibo de pago'])
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
                                    <div class="text">
                                        <p
                                            style="
                                          font-family: Open Sans, Roboto, sans-serif;
                                          font-style: normal;
                                          font-weight: 400;
                                          font-size: 22px;
                                          line-height: 29px;
                                          max-width: 500px;
                                          text-align: center;">
                                            Estimado cliente,
                                            Deseandole exito en sus labores, le notificamos nos encontramos verificando 
                                            su transferencia, esto puede tomar unos minutos
                                        </p>


                                    </div>

                                    <center>
                                        <table
                                            style="
                                          font-family: Open Sans, Roboto, sans-serif;
                                          font-style: normal;
                                          font-weight: 400;
                                          font-size: 22px;
                                          line-height: 29px;
                                          max-width: 500px;
                                          text-align: center;">

                                            <tr>
                                                <td><b>Ref transacción: </b></td>
                                                <td> {{ $transaction->bill_code }}
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

                                    </center>


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

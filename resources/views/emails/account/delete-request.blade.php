@extends('layouts.emails')

@section('title', 'Eliminación de cuenta')

@section('header')
    @include('emails.header', ['headerText' => 'Eliminación de cuenta'])
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
                                           Estimado usuario,<br> hemos recibido una solicitud para eliminar su cuenta. La misma será procesada y eliminada en un plazo de <b>7 días</b>. <br>
                                           Si usted no realizó esta solicitud, comuníquese con nuestro equipo de soporte enviando un correo a <a href="mailto:support@castilloapp.com">support@castilloapp.com</a>.
                                        </p>


                                    </div>


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

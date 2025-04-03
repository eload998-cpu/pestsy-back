@extends('layouts.emails')

@section('title', 'Recuperacion de contraseña')

@section('header')
    @include('emails.header', ['headerText' => 'Recuperación de contraseña'])
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
                                        <div
                                            style="                                          
                                          font-family: Open Sans, Roboto, sans-serif;                                                                               
                                          font-style: normal;                                          
                                          font-weight: 400;                                          
                                          font-size: 22px;                                          
                                          line-height: 29px;
                                          max-width: 500px; 
                                          text-align: center;">
                                            Estimado cliente,
                                            <b>Hemos recibido una solicitud de cambio de contraseña</b><br><br>

                                            Usa el siguiente botón para establecer una nueva contraseña. Si no hiciste esta
                                            solicitud, comunícate con nuestro equipo de soporte
                                            <br><br>
                                            <div style="text-align:center; padding:2rem;">
                                                <a style="background-color: #76D293;
  color: white;
  padding: 15px 25px;
  text-decoration: none;"
                                                    href="{{ config('app.front_app_base_url') . 'cambiar-contrasena/' . urlencode(base64_encode($token)) }}">Haz
                                                    click aqui</a>
                                            </div>

                                        </div>


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

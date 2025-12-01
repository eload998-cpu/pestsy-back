@extends('layouts.emails')
@section('title', 'Recuperacion de contraseña')
@section('header')
    @include('emails.header', ['headerText' => 'Recuperación de contraseña'])
@endsection
@section('content')
    <tr>
        <td align="center">

            <div class="main-title" style="margin-top:16px;">
                <span style="font-size:30px; font-weight:700;">Hola, {{ ucwords($name) }}</span>
            </div>

            <div class="description">
                <table width="100%" height="91" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:16px;">
                    <tr>

                        <td align="center" valign="middle" style="padding:30px;">

                            <div style="text-align:justify;">
                                <b>Hemos recibido una solicitud de cambio de contraseña</b>
                            </div>

                            <div style="text-align:justify;">
                                Usa el siguiente botón para establecer una nueva contraseña. Si no hiciste esta
                                solicitud, comunícate con nuestro equipo de soporte
                            </div>



                        </td>
                    </tr>
                </table>
            </div>

            <div class="transaction-status">
                <table width="50%" height="52" cellpadding="0" cellspacing="0"
                    style="background-color: #76D293; border-radius:10px; margin-top:20px;">
                    <tr>
                        <td align="center" valign="middle" style="font-weight: 700;">
                            <a style="color:#FFF; text-decoration:none;"
                                href="{{ config('app.front_app_base_url') . 'cambiar-contrasena/' . urlencode(base64_encode($token)) }}">Haz
                                click aqui</a>

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

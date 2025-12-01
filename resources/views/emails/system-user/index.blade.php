@extends('layouts.emails')
@section('title', 'Datos de tu cuenta')
@section('header')
    @include('emails.header', ['headerText' => 'Datos de tu cuenta'])
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
                                Tus credenciales de acceso son las siguientes:
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
                            <b>Detalles de la cuenta</b>
                        </td>
                    </tr>
                </table>


                @if (isset($user->email))
                    <table width="100%" height="42" cellpadding="0" cellspacing="0"
                        style="background-color:#F8F8F8; border-radius:10px; margin-top:10px;">
                        <tr>
                            <td width="50%" style="padding-left:16px;">
                                <b>Correo</b>
                            </td>
                            <td width="50%" style="padding-left:16px;">
                                {{ $user->email }}
                            </td>
                        </tr>
                    </table>
                @endif

                @if (isset($password))
                    <table width="100%" height="42" cellpadding="0" cellspacing="0"
                        style="background-color:#F8F8F8; border-radius:10px; margin-top:10px;">
                        <tr>
                            <td width="50%" style="padding-left:16px;">
                                <b>Contraseña</b>
                            </td>
                            <td width="50%" style="padding-left:16px;">
                                {{ $password }}
                            </td>
                        </tr>
                    </table>
                @endif
            </div>

            <div class="message">
                <table width="100%" height="42" cellpadding="0" cellspacing="0"
                    style="background-color:#F8F8F8; border-radius:10px; margin-top:20px;">
                    <tr>
                        <td align="center" valign="middle">
                            Por tu comodidad, te recomendamos cambiar tu contraseña lo antes posible
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

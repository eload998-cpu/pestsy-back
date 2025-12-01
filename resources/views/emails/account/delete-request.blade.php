@extends('layouts.emails')
@section('title', 'Eliminación de cuenta')
@section('header')
    @include('emails.header', ['headerText' => 'Eliminación de cuenta'])
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
                                <b>Hemos recibido una solicitud para eliminar su cuenta.</b>
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
                <table width="100%" height="42" cellpadding="0" cellspacing="0" style=" margin-top:20px;">
                    <tr>
                        <td>
                            La misma será
                            procesada y su cuenta será eliminada en un plazo de <b>7 días</b>. <br>
                            <b>Si usted no realizó esta solicitud</b>, comuníquese con nuestro equipo de soporte enviando un
                            correo
                            a <a href="mailto:support@castilloapp.com">support@castilloapp.com</a>.
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

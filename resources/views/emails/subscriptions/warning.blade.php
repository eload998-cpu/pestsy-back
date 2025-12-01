@extends('layouts.emails')
@section('title', 'Su cuenta esta por expirar')
@section('header')
    @include('emails.header', ['headerText' => 'Su cuenta esta por expirar'])
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

                            <div style="text-align:justify;">
                                Le notificamos que su suscripción <b>esta por expirar</b> . <br>

                                Su subscripción caduca el <b>{{ $end_date }}</b>. Le recomendamos renovar su
                                suscripción lo más pronto posible para continuar utilizando las características
                                <b>Premium</b> del sistema.
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
                                href="https://pestsy.castilloapp.com/home/suscripciones">Haz
                                clic
                                aquí para renovar</a>

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

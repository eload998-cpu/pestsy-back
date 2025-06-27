@extends('layouts.emails')

@section('title', 'Su cuenta esta por expirar')

@section('header')
    @include('emails.header', ['headerText' => 'Su cuenta esta por expirar'])
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

                                            Deseándole éxitos en sus labores,
                                            Le agradecemos su preferencia y estamos a su disposición para cualquier duda
                                            o sugerencia!<br>
                                            Le notificamos que su suscripción esta por expirar.<br><br>

                                            <b>Su subscripción caduca el {{ $end_date }}</b><br> le recomendamos renovar
                                            su
                                            suscripción lo más pronto posible para continuar utilizando las características <b>premium</b> del sistema.
                                        </p>


                                    </div>

                                    <a title="pagar" href="https://pestsy.castilloapp.com/home/suscripciones"
                                        style="
                                    font-family: Poppins, Roboto;
                                    font-style: normal;
                                    font-weight: 600;
                                    font-size: 24px;
                                    width: 500px;
                                    height: 50px;
                                    text-align: center;
                                    background: #006DB6;
                                    color:#FFFFFF;
                                    border-radius: 30px;
                                    text-decoration:none;
                                    text-underline:none;
                                    display: block;
                                    margin:auto;
                                    margin-bottom: 50px;
                                    line-height: 50px;"
                                        target="_blank">
                                        ¡Ingresa aquí para renovar!
                                    </a>
                                    <div
                                        style="
                                    max-width: 500px;
                                    margin: auto;
                                    text-align: center;
                                    font-family: Open Sans, Roboto, sans-serif;
                                    font-style: normal;
                                    font-weight: 400;
                                    font-size: 20px;
                                    line-height: 32px;
                                    text-align: center;
                                    color: #23272B;
                                    margin-bottom: 30px;
                                    ">
                                        <p
                                            style="
                                       margin-bottom: 0px;
                                       font-family: Open Sans, Roboto, sans-serif;
                                       font-style: normal;
                                       font-weight: 400;
                                       ">
                                            Si presentas problemas al acceder, puedes copiar <br />
                                            y pegar el siguiente enlace en tu navegador:
                                        </p>
                                        <p
                                            style="
                                       margin-top: 5px;
                                       color: #1A84B4;
                                       ">
                                            https://pestsy.castilloapp.com/home/suscripciones
                                        </p>
                                        <p
                                            style="
                                       font-weight: 600;
                                       font-family: Open Sans, Roboto, sans-serif;
                                       ">
                                            Recuerda tener a la mano tu usuario y <br />
                                            contraseña para realizar <br />
                                            la renovación.
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

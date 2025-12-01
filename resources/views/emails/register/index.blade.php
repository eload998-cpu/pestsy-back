@extends('layouts.emails')
@section('title', 'Registro')
@section('header')
    @include('emails.header', ['headerText' => "¡Hola { ucwords($user->first_name) }!"])
@endsection
@section('content')
    <tr>
        <td align="center">

            <div class="main-title" style="margin-top:16px;">
                <span style="font-size:30px; font-weight:700;">Hola, {{ ucwords($user->first_name) }}</span>
            </div>

            <div class="description">
                <table width="100%" height="91" cellpadding="0" cellspacing="0" style="margin-top:16px;">
                    <tr>

                        <td align="center" valign="middle" style="padding:30px;">


                            <div style="text-align:justify;">
                                Has dado el paso correcto para <b>dejar atrás el papeleo</b> y centralizar toda tu operación
                                en un
                                solo lugar. A partir de hoy, podrás ofrecer un servicio más profesional a tus propios
                                clientes.
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
                <table width="100%" height="42" cellpadding="0" cellspacing="0" style="margin-top:20px;">
                    <tr>
                        <td align="center" valign="middle">
                            <b>Tus primeros pasos para optimizar tu negocio:</b>
                        </td>
                    </tr>
                </table>

                <table role="presentation" width="100%"
                    style="max-width: 600px; margin-left: auto; margin-right: auto; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin-top:16px;">
                    <tr>
                        <td class="card-column" width="33.33%"
                            style="width: 33.33%; padding: 8px; vertical-align: top; mso-padding-alt: 8px 8px 8px 8px;">
                            <div
                                style="background-color: #f0f7ff; padding: 16px; border-radius: 12px; text-align: center; height: 190px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); border: 1px solid #dbeafe;">
                                <p style="font-size: 36px; line-height: 40px; margin-bottom: 8px; margin-top: 0;">👥</p>
                                <!-- Icono -->
                                <p style="font-weight: bold; color: #1f2937; margin-bottom: 4px; margin-top: 0;">Sube tu
                                    cartera</p>
                                <p style="font-size: 14px; color: #4b5563; margin-top: 0; margin-bottom: 0;">Centraliza tus
                                    clientes, sus reportes e historial de cuenta.</p>
                            </div>
                        </td>

                        <td class="card-column" width="33.33%"
                            style="width: 33.33%; padding: 8px; vertical-align: top; mso-padding-alt: 8px 8px 8px 8px;">
                            <div
                                style="background-color: #f0f7ff; padding: 16px; border-radius: 12px; text-align: center; height: 190px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); border: 1px solid #dbeafe;">
                                <p style="font-size: 36px; line-height: 40px; margin-bottom: 8px; margin-top: 0;">📝</p>
                                <!-- Icono -->
                                <p style="font-weight: bold; color: #1f2937; margin-bottom: 4px; margin-top: 0;">Crea tu
                                    primera orden</p>
                                <p style="font-size: 14px; color: #4b5563; margin-top: 0; margin-bottom: 0;">Digitaliza tus
                                    reportes en minutos y envíalos al instante.</p>
                            </div>
                        </td>

                        <td class="card-column" width="33.33%"
                            style="width: 33.33%; padding: 8px; vertical-align: top; mso-padding-alt: 8px 8px 8px 8px;">
                            <div
                                style="background-color: #f0f7ff; padding: 16px; border-radius: 12px; text-align: center; height: 190px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); border: 1px solid #dbeafe;">
                                <p style="font-size: 36px; line-height: 40px; margin-bottom: 8px; margin-top: 0;">🪤</p>
                                <!-- Icono -->
                                <p style="font-weight: bold; color: #1f2937; margin-bottom: 4px; margin-top: 0;">Registra
                                    tus dispositivos</p>
                                <p style="font-size: 14px; color: #4b5563; margin-top: 0; margin-bottom: 0;">Lleva un
                                    control exacto de cebaderos y trampas instaladas.</p>
                            </div>
                        </td>
                    </tr>
                </table>





            </div>

            <div class="message">
                <table width="100%" height="42" cellpadding="0" cellspacing="0" style="margin-top:20px;">
                    <tr>
                        <td align="center" valign="middle">
                            <b>¿Necesitas ayuda para migrar tus datos actuales?</b> Contáctanos y nuestro equipo de soporte
                            técnico
                            te guiará.
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

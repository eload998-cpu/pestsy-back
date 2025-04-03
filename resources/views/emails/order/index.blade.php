@extends('layouts.emails')

@section('title', 'Orden de servicio')

@section('header')
    @include('emails.header', ['headerText' => 'Orden de servicio'])
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
                                            o sugerencia<br><br>
                                            Hemos creado una orden de servicio para usted:
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

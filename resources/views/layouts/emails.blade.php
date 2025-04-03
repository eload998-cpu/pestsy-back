<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraph.org/schema/">

<head>
    <meta property="og:title" content="pruebas">
    <meta property="fb:page_id" content="43929265776">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="referrer" content="origin">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600;700&family=Open+Sans:wght@300;400;600;700&family=Roboto&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
            .mainContainer {
                margin: 50px;
                text-align: justify;
                margin-top: 15px;
                line-height: 150%;
                padding: 20px;
                border-radius: 12px;
                box-sizing: border-box;
                background-color: #FFFFFF;
                color: #5C5B5C;
                font-family: 'Open Sans', sans-serif;
                font-size: 16px;
                font-weight: 300;
                border: 1px solid #e7e7e7;
                -ms-word-break: break-all;
                word-break: break-word;
                -webkit-hyphens: auto;
                -moz-hyphens: auto;
                -ms-hyphens: auto;
                hyphens: auto;
            }

            .itemMobileContainer {
                display: flex;
                flex-direction: column;
            }

            .itemMobileView {
                display: flex;
                align-items: center;
                width: 100%;
            }

            /* IDs */
            #deliveryProvider {
                display: block;
                text-align: left;
            }

            #deliveryProviderImg {
                display: inline;
                text-align: left;
            }
        }

        @media only screen and (min-device-width: 768px) {
            .mainContainer {
                margin: 50px;
                text-align: justify;
                margin-top: 0 !important;
                line-height: 150%;
                padding: 20px;
                border-radius: 12px;
                box-sizing: border-box;
                background-color: #FFFFFF;
                color: #5C5B5C;
                font-family: 'Open Sans', sans-serif;
                font-size: 16px;
                font-weight: 300;
                border: 1px solid #e7e7e7;
                -ms-word-break: break-all;
                word-break: break-word;
                -webkit-hyphens: auto;
                -moz-hyphens: auto;
                -ms-hyphens: auto;
                hyphens: auto;
            }

            .itemMobileContainer {
                display: flex;
                flex-direction: row;
            }

            .itemMobileView {
                display: flex;
                align-items: center;
                width: 50%
            }

            /* IDs */
            #deliveryProviderImg {
                width: 105px;
                margin-left: 10px;
            }
        }
    </style>
</head>

<body style="height: 100%;margin: 0;padding: 0;width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"
            id="bodyTable"
            style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100%;margin: 0;padding: 0;width: 100%;">
            <tr>
                <td align="center" valign="top" id="bodyCell"
                    style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100%;margin: 0;padding: 0;width: 100%;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                        style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <tr>
                            <td align="center" valign="top" id="templateBody" data-template-container=""
                                style="background:#ffffff none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 36px;padding-bottom: 45px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    style="border-collapse: collapse;
                              mso-table-lspace: 0pt;
                              mso-table-rspace: 0pt;
                              -ms-text-size-adjust: 100%;
                              -webkit-text-size-adjust: 100%;
                              max-width: 600px !important;">
                                    <!-- START HEADER  -->
                                    <tr>
                                        <td>
                                            @yield('header')
                                        </td>
                                    </tr>
                                    <!--END HEADER-->


                                    <!--START CONTENT-->
                                    <tr>
                                        <td>

                                            @yield('content')

                                        </td>

                                    </tr>

                                    <!-- END CONTENT-->

                                    <!--START FOOTER-->
                                    <tr>
                                        <td>
                                            @yield('footer')

                                        </td>
                                    </tr>

                                    <!--END FOOTER-->
                                </table>
                            </td>
                        </tr>
                    </table>
                    <!-- // END TEMPLATE -->
                </td>
            </tr>
        </table>
    </center>
</body>

</html>

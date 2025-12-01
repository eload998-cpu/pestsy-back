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
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" align="center"
        style="font-family: Poppins, Roboto, sans-serif;">
        <tr>
            <td align="center">

                <table width="500" cellpadding="0" cellspacing="0" style="width:500px; max-width:500px;">

                    @yield('header')

                    @yield('content')

                    @yield('footer')

                </table>

            </td>
        </tr>

    </table>
</body>

</html>

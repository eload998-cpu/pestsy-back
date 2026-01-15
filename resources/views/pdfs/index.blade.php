<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            height: 100%;

        }

        .row {
            font-size: 11px;

        }

        .table tr:not(:last-of-type) {
            border-bottom: 2px solid transparent !important;
        }



        .table th {
            text-align: center;
        }

        .table-bottom {
            width: 350px;
            margin-left: 80px;
        }

        .table-bottom tr {
            width: 50%;
            font-size: 11px;
        }

        .table-bottom th {
            margin: 0;
            padding: 0;
            text-align: left;
        }

        .table-bottom tr th:first-child {}

        .text-left {
            text-align: left;
        }

        .center-block {
            display: block;
            margin-right: auto;
            margin-left: auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .block {
            margin: 0 0 10px;
            padding: 20px 15px 1px;
            background-color: #ffffff;
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;
            -webkit-box-shadow: 0 2px 0 rgba(218, 224, 232, .5);
            /* box-shadow: 0 2px 0 rgba(218, 224, 232, .5); */
        }

        .block-title {
            margin: -20px -15px 20px;
            border-bottom: 2px solid #dae0e8;
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;
            background: rgba(218, 224, 232, .15);
        }

        .block.full {
            padding: 20px 15px;
        }

        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-xs-1,
        .col-sm-1,
        .col-md-1,
        .col-lg-1,
        .col-xs-2,
        .col-sm-2,
        .col-md-2,
        .col-lg-2,
        .col-xs-3,
        .col-sm-3,
        .col-md-3,
        .col-lg-3,
        .col-xs-4,
        .col-sm-4,
        .col-md-4,
        .col-lg-4,
        .col-xs-5,
        .col-sm-5,
        .col-md-5,
        .col-lg-5,
        .col-xs-6,
        .col-sm-6,
        .col-md-6,
        .col-lg-6,
        .col-xs-7,
        .col-sm-7,
        .col-md-7,
        .col-lg-7,
        .col-xs-8,
        .col-sm-8,
        .col-md-8,
        .col-lg-8,
        .col-xs-9,
        .col-sm-9,
        .col-md-9,
        .col-lg-9,
        .col-xs-10,
        .col-sm-10,
        .col-md-10,
        .col-lg-10,
        .col-xs-11,
        .col-sm-11,
        .col-md-11,
        .col-lg-11,
        .col-xs-12,
        .col-sm-12,
        .col-md-12,
        .col-lg-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .col-xs-1,
        .col-xs-2,
        .col-xs-3,
        .col-xs-4,
        .col-xs-5,
        .col-xs-6,
        .col-xs-7,
        .col-xs-8,
        .col-xs-9,
        .col-xs-10,
        .col-xs-11,
        .col-xs-12 {
            float: left;
        }

        .col-xs-12 {
            width: 100%;
        }

        .col-xs-11 {
            width: 91.66666667%;
        }

        .col-xs-10 {
            width: 83.33333333%;
        }

        .col-xs-9 {
            width: 75%;
        }

        .col-xs-8 {
            width: 66.66666667%;
        }

        .col-xs-7 {
            width: 58.33333333%;
        }

        .col-xs-6 {
            width: 50%;
        }

        .col-xs-5 {
            width: 41.66666667%;
        }

        .col-xs-4 {
            width: 33.33333333%;
        }

        .col-xs-3 {
            width: 25%;
        }

        .col-xs-2 {
            width: 16.66666667%;
        }

        .col-xs-1 {
            width: 8.33333333%;
        }

        .paragrap {
            margin-bottom: 0px;
            font-size: 8px;
            font-weight: bold;
        }

        .eleven {
            font-size: 11px;
        }

        .h {
            color: black !important;
            height: 1px !important;
            background-color: black !important;
        }

        .text-justify {
            text-align: justify;
        }

        .page-break {
            page-break-after: always;
        }

        .table th {
            font-size: 10px !important
        }

        .table td {
            font-size: 10px !important
        }

        .text-center {
            text-align: center
        }

        .break-before {
            page-break-before: always;
        }

        .section {
            margin-top: 5px;
        }

        thead:before,
        thead:after {
            display: none;
        }

        tbody:before,
        tbody:after {
            display: none;
        }

        @page {
            margin: .5in;
        }

        .bg {
            top: 6in;
            right: -.5in;
            bottom: -.5in;
            left: -.5in;
            position: absolute;
            z-index: -1000;
            min-width: 1in;
            min-height: 1in;
        }

        .red {
            background-color: red;
        }

        .blue {
            background-color: blue;
        }

        .green {
            background-color: green;
        }

        .yellow {
            background-color: yellow;
        }


        .header-row {
            height: 150px;
        }

        .block-header-row {
            background: rgba(218, 224, 232, .15);
            border-bottom: 2px solid #dae0e8;
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;

        }


        .block-header-row--small {
            height: 45px;
            background: rgba(218, 224, 232, .15);
            border-bottom: 2px solid #dae0e8;
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;

        }

        .content-row {
            height: 55px;

        }


        .content-row--big {
            height: 75px;

        }

        .content-row--big-2 {
            height: 100px;

        }

        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="row clearfix" style="background-color:#76D292; color:White !important; border-radius:2px;">
        <div class="col-xs-1" style="margin-top:5px;">

            @if ($order['logo'])
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($order['logo']))) }}"
                    style="display: block; width:50px; height:50px; object-fit:cover; border-radius:50%;margin-top:20px;">
            @else
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/pestsy-logo.png'))) }}"
                    style="display: block; width:50px; height:50px; border-radius:50%; margin-top:20px;">
            @endif
        </div>

        <div class="col-xs-5">
            <h1 style="font-size: 21px !important; margin-top: 30px; margin-bottom: 4%;">
                {{ ucwords($order['user']['company']['name']) }}</h1>
        </div>

        <div class="col-xs-4">
            <p class="text-right" style="font-size: 10px; margin-top: 5%;">
                <b>Licencia:</b> {{ $order['user']['company']['license_number'] }}<br>
                <b>Tel:</b> {{ $order['user']['company']['phone'] }}<br>
                <b>E-mail:</b> {{ $order['user']['company']['email'] }}<br>
                <b class="eleven">Fecha:</b> {{ $order['parsed_date'] }}<br>
                @if (!empty($order['user']['company']['direction']))
                    <b class="eleven">Dirección:</b><br>
                    {{ $order['user']['company']['direction'] }}
                @else
                    <br>
                @endif
            </p>
        </div>
    </div>

    <div class="row clearfix mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">Bitacora de trabajo</h6>
        </div>
    </div>

    {{-- ===== ORDER / CLIENT INFO AS TABLE (with borders) ===== --}}
    <table class="table mt-3">
        <tbody>
            <tr>
                <td>
                    <b>Cédula Física:</b>
                    {{ $order['client']['identification_number'] }}
                </td>
                <td>
                    <b>Cédula Jurídica:</b>
                    {{ $order['client']['identification_number'] }}
                </td>
                <td>
                    <b>Encargado:</b>
                    {{ $order['worker']['full_name'] }}
                </td>
            </tr>

            <tr>
                <td>
                    <b>Cliente:</b>
                    {{ $order['client']['full_name'] }}
                </td>
                <td>
                    <b>Tipo de servicio:</b>
                    @if (!empty($order['service_type']))
                        {{ $order['service_type'] }}
                    @else
                        Ninguno
                    @endif
                </td>
                <td>
                    <b>Orden de servicio:</b>
                    {{ $order['order_number'] }}
                </td>
            </tr>

            <tr>
                <td>
                    <b>Hora de llegada:</b>
                    @if (!empty($order['arrive_time']))
                        {{ $order['parsed_arrive_time'] }}
                    @else
                        Ninguno
                    @endif
                </td>
                <td>
                    <b>Hora de inicio:</b>
                    @if (!empty($order['start_time']))
                        {{ $order['parsed_start_time'] }}
                    @else
                        Ninguno
                    @endif
                </td>
                <td>
                    <b>Hora de salida:</b>
                    @if (!empty($order['end_time']))
                        {{ $order['parsed_end_time'] }}
                    @else
                        Ninguno
                    @endif
                </td>
            </tr>

            <tr>
                <td>
                    <b>Procedencia:</b>
                    @if (!empty($order['origin']))
                        {{ $order['origin'] }}
                    @else
                        Ninguno
                    @endif
                </td>
                <td colspan="2">
                    <b>Dirección:</b>
                    @if (!empty($order['direction']))
                        {{ $order['direction'] }}
                    @else
                        Ninguno
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    {{-- ===== END TABLE ===== --}}

    <!--XYLOPHAGE-->
    @include('pdfs.xylophage.index')
    <!-- -->

    <!--LEGIONELLA-->
    @include('pdfs.legionella.index')
    <!-- -->

    <!--INTERNAL CONDITIONS-->
    @include('pdfs.internal-conditions.index')
    <!-- -->

    <!--EXTERNAL CONDITIONS-->
    @include('pdfs.external-conditions.index')
    <!-- -->

    <!--RODENT CONTROL-->
    @include('pdfs.rodent-control.index')
    <!-- -->

    <!--FUMIGATIONS-->
    @include('pdfs.fumigations.index')
    <!-- -->

    <!--LAMPS-->
    @include('pdfs.lamps.index')
    <!-- -->

    <!--TRAPS-->
    @include('pdfs.traps.index')
    <!-- -->

    <!--INFESTATION GRADES-->
    @include('pdfs.infestation-grades.index')
    <!-- -->

    <!--OBSERVATIONS-->
    @include('pdfs.observations.index')
    <!-- -->

    <!--PRODUCTS-->
    @include('pdfs.products.index')
    <!-- -->

    <!--TECHNICIAN INFO-->
    @include('pdfs.technician-info.index')
    <!-- -->

    <!--SIGNATURES-->
    @include('pdfs.signatures.index')
    <!-- -->

    <!--IMAGES-->
    @include('pdfs.images.index')
    <!-- -->
</body>


</html>

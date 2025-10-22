
@if(isset ($order["fumigations"]) && count($order["fumigations"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >FUMIGACIONES</h6>
            </div>
</div>

<div class="row clearfix">
        <div class="col-xs-12 mt-2">
            <span><b>Peligro controlado: plagas – CCP: dosis, producto aplicado, área tratada.</b></span>

        </div>
</div>


<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">

            <tbody>

                @foreach($order["fumigations"] as $fumigation)

                <tr style="width=100%">
                <td class="text-center" colspan="6"><b>#{{ $loop->iteration }}</b></td>
                </tr>


                <tr style="width=100%">
                <td><b>Tratamiento aplicado: </b>{{$fumigation["aplication"]["name"]}}</td>
                <td><b>Ubicación: </b>{{$fumigation["location"]["name"]}}</td>
                <td colspan="2"><b>Producto: </b>{{$fumigation["product"]["name"]}}</td>
                <td colspan="2"><b>Dosis: </b>{{$fumigation["dose"]}}</td>
                </tr>

                <tr style="width=100%">
                <td><b>Técnico responsable: </b>{{$fumigation["worker"]["full_name"]}}</td>
                <td><b>Hora aplicación: </b>{{\Carbon\Carbon::parse($fumigation["application_time"])->format('h:i A')}}</td>
                <td colspan="2"><b>Dentro de límites críticos: </b>{{$fumigation["within_critical_limits"] ? 'Si':'No'}}</td>

            </tr>

                @if($fumigation["safety_controls"])
                <tr style="width=100%">
                <td colspan="6">
                        <b>Controles de seguridad:</b>
                        @php
                            $actions = collect($fumigation["safety_controls"])
                                        ->pluck('safety_control.name')
                                        ->filter()
                                        ->implode(', ');
                        @endphp

                        <span>{{ $actions }}</span>
                    </td>
                </tr>
                @endif

                @if($fumigation["corrective_actions"])
                <tr style="width=100%">
                <td colspan="6">
                        <b>Acciones correctivas:</b>
                        @php
                            $actions = collect($fumigation["corrective_actions"])
                                        ->pluck('corrective_action.name')
                                        ->filter()
                                        ->implode(', ');
                        @endphp

                        <span>{{ $actions }}</span>
                    </td>
                </tr>
                @endif


                @endforeach

            </tbody>
    </table>

    </div>
</div>

@endif

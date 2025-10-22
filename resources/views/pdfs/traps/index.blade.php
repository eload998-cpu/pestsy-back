
@if(isset ($order["traps"]) && count($order["traps"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >TRAMPAS UNITRAP</h6>
            </div>
</div>
<div class="row clearfix">
        <div class="col-xs-12 mt-2">
            <span><b>Peligro controlado: insectos – CCP: dosis, área tratada, método aplicado</b></span>

        </div>
</div>


<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
   
         <tbody>

            @foreach($order["traps"] as $trap)

                <tr style="width=100%">
                    <td class="text-center" colspan="6"><b>#{{ $loop->iteration }}</b></td>
                </tr>

                <tr style="width=100%">
                <td><b>Número Estación: </b>{{$trap["station_number"]}}</td>
                <td><b>Posee feromonas: </b>{{translate_yes_no($trap["pheromones"])}}</td>
                <td colspan="2"><b>Estado de feromonas: </b>{{$trap["pheromones_state"]}}</td>
                <td colspan="2"><b>Lugar aplicación: </b>{{$trap["location"]["name"]}}</td>
                </tr>

                <tr style="width=100%">
                <td><b>Dispositivo: </b>{{$trap["device"]["name"]}}</td>
                <td><b>Trampa en mal funcionamiento: </b>{{$trap["condition"]}}</td>
                <td colspan="2"><b>Producto: </b>{{$trap["product"]["name"]}}</td>
                <td colspan="2"><b>Dosis: </b>{{$trap["dose"]}}</td>
                </tr>

                <tr style="width=100%">
                <td><b>Técnico responsable: </b>{{$trap["worker"]["full_name"]}}</td>
                <td><b>Hora aplicación: </b>{{\Carbon\Carbon::parse($trap["application_time"])->format('h:i A')}}</td>
                <td colspan="2"><b>Dentro de límites críticos: </b>{{$trap["within_critical_limits"] ? 'Si':'No'}}</td>
  
            </tr>


               @if(!empty($trap["corrective_actions"]))

                <tr style="width=100%">
                <td colspan="6">
                        <b>Acciones correctivas:</b>
                           @php
                            $actions = collect($trap["corrective_actions"])
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

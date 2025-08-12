
@if(isset ($order["traps"]) && count($order["traps"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >TRAMPAS UNITRAP</h6>
            </div>
</div>



<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
       <thead>
            <tr>
            <th class="text-center">Número Estación</th>
            <th class="text-center">Posee feromonas</th>
            <th class="text-center">Dispositivo</th>
            <th class="text-center">Producto</th>
            <th class="text-center">Dosis</th>
            <th class="text-center">Lugar aplicación</th>
            <th class="text-center">Técnico responsable</th>
            <th class="text-center">Hora aplicación </th>
            </tr>
        </thead>

            <tbody>

            @foreach($order["traps"] as $trap)
                <tr>
                    <td class="text-center">{{$trap["station_number"]}}</td>
                    <td class="text-center">{{translate_yes_no($trap["pheromones"])}}</td>
                    <td class="text-center">{{$trap["device"]["name"]}}</td>
                    <td class="text-center">{{$trap["product"]["name"]}}</td>
                    <td class="text-center">{{$trap["dose"]}}</td>
                    <td class="text-center">{{$trap["aplication_place"]["name"]}}</td>
                    <td class="text-center">{{$trap["worker"]["full_name"]}}</td>
                    <td class="text-center">{{\Carbon\Carbon::parse($trap["application_time"])->format('h:i A')}}</td>

                </tr>

                     @if(!empty($trap["corrective_actions"]))
                <tr>
                    <td colspan="7" style="padding: 10px;">
                        <strong>Acciones correctivas:</strong>
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

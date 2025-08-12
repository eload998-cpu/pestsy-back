@if(isset ($order["lamps"]) && count($order["lamps"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >LAMPARAS DE LUZ</h6>
            </div>
    </div>



<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
       <thead>
            <tr>
            <th class="text-center">Número Estación</th>
            <th class="text-center">Cambio de plancha</th>
            <th class="text-center">Limpieza</th>
            <th class="text-center">Lampara en mal estado</th>
            <th class="text-center">Cambio de Fluorescente</th>
            <th class="text-center">Cantidad</th>
            <th class="text-center">Técnico responsable</th>
            <th class="text-center">Hora aplicación </th>


            </tr>
        </thead>

            <tbody>

                @foreach($order["lamps"] as $lamp)
                <tr>
                    <td class="text-center">{{$lamp["station_number"]}}</td>
                    <td class="text-center">{{translate_yes_no($lamp["rubbery_iron_changed"])}}</td>
                    <td class="text-center">{{translate_yes_no($lamp["lamp_cleaning"])}}</td>
                    <td class="text-center">{{translate_yes_no($lamp["lamp_not_working"])}}</td>
                    <td class="text-center">{{translate_yes_no($lamp["fluorescent_change"])}}</td>
                    <td class="text-center">{{!empty($lamp["quantity_replaced"])?$lamp["quantity_replaced"]:'0'}}</td>
                    <td class="text-center">{{$lamp["worker"]["full_name"]}}</td>
                    <td class="text-center">{{\Carbon\Carbon::parse($lamp["application_time"])->format('h:i A')}}</td>  
                </tr>

                  <tr style="width=100%">
                  <td colspan="7"><b>Observaciones: </b>{{$lamp["observation"]}}</td>
                  </tr>

                     @if(!empty($lamp["corrective_actions"]))
                <tr>
                    <td colspan="7" style="padding: 10px;">
                        <strong>Acciones correctivas:</strong>
                        @php
                            $actions = collect($lamp["corrective_actions"])
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

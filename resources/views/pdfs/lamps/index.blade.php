@if(isset ($order["lamps"]) && count($order["lamps"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >LAMPARAS DE LUZ</h6>
            </div>
    </div>



<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
     
            <tbody>

                @foreach($order["lamps"] as $lamp)

                <tr style="width=100%">
                    <td class="text-center" colspan="6"><b>#{{ $loop->iteration }}</b></td>
                </tr>

                <tr style="width=100%">
                <td><b>Número Estación: </b>{{$lamp["station_number"]}}</td>
                <td><b>Cambio de plancha gomosa: </b>{{translate_yes_no($lamp["rubbery_iron_changed"])}}</td>
                <td colspan="2"><b>Limpieza: </b>{{translate_yes_no($lamp["lamp_cleaning"])}}</td>
                <td colspan="2"><b>Lampara en mal estado: </b>{{translate_yes_no($lamp["lamp_not_working"])}}</td>
                </tr>

                <tr style="width=100%">
                <td><b>Cambio de Fluorescente: </b>{{translate_yes_no($lamp["fluorescent_change"])}}</td>
                <td><b>Cantidad: </b>{{!empty($lamp["quantity_replaced"])?$lamp["quantity_replaced"]:'0'}}</td>
                <td colspan="2"><b>Técnico responsable: </b>{{$lamp["worker"]["full_name"]}}</td>
                <td colspan="2"><b>Hora aplicación: </b>{{\Carbon\Carbon::parse($lamp["application_time"])->format('h:i A')}}</td>
                </tr>

                <tr style="width=100%">
                  <td colspan="7"><b>Observaciones: </b>{{$lamp["observation"]}}</td>
                </tr>

                     @if(!empty($lamp["corrective_actions"]))
                <tr>
                    <td colspan="7" style="padding: 10px;">
                        <b>Acciones correctivas:</b>
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

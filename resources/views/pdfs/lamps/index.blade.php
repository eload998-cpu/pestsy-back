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
            <th class="text-center">Numero</th>
            <th class="text-center">Cambio de plancha</th>
            <th class="text-center">Limpieza</th>
            <th class="text-center">Lampara en mal estado</th>
            <th class="text-center">Cambio de Fluorescente</th>
            <th class="text-center">Cantidad</th>


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
                    <td class="text-center">{{!empty($lamp["quantity_replaced"])?$lamp["quantity_replaced"]:'Sin definir'}}</td>
                  </tr>

                  <tr style="width=100%">
                  <td colspan="7"><b>Observaciones: </b>{{$lamp["observation"]}}</td>
                  </tr>
                @endforeach

            </tbody>
    </table>

    </div>
</div>





@endif


@if( isset ($order["legionella_control"])  && count($order["legionella_control"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >LEGIONELA</h6>
            </div>
    </div>


<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
       <thead>
            <tr>
            <th class="text-center">Codigo</th>
            <th class="text-center">Instalación evaluada</th>
            <th class="text-center">Metodo de desinfección</th>
            <th class="text-center">Resultado de inspección</th>
            <th class="text-center">Ultima revisión</th>
            <th class="text-center">Proxima revisión</th>
            <th class="text-center">Requiere muestra</th>

            </tr>
        </thead>

            <tbody>

                @foreach($order["legionella_control"] as $legionellaControl)
                <tr>
                    <td class="text-center">{{$legionellaControl["code"]}}</td>
                    <td class="text-center">{{$legionellaControl["location"]["name"]}}</td>
                    <td class="text-center">{{$legionellaControl["desinfection_method"]["name"]}}</td>
                    <td class="text-center">{{$legionellaControl["inspection_result"]}}</td>
                    <td class="text-center">{{$legionellaControl["last_treatment_date"]}}</td>
                    <td class="text-center">{{$legionellaControl["next_treatment_date"]}}</td>
                    <td class="text-center">{{($legionellaControl["sample_required"]) ? 'Si':'No'}}</td>

                </tr>

                <tr style="width=100%">
                <td colspan="2">
                <b>  Temperatura del agua:</b>{{(empty($legionellaControl["water_temperature"]) ? 'No aplica' : $legionellaControl["water_temperature"])}}
                </td>
                <td colspan="2">
                <b> Nivel de cloro residual:</b>{{(empty($legionellaControl["residual_chlorine_level"]) ? 'No aplica' : $legionellaControl["residual_chlorine_level"])}}
                </td>
                </tr>

                <tr style="width=100%">
                <td colspan="7"><b>Observaciones: </b>{{$legionellaControl["observation"]}}</td>
                </tr>
                @endforeach

            </tbody>
    </table>

    </div>
</div>

@endif

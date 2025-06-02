
@if(isset ($order["xylophage_control"]) && count($order["xylophage_control"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >XILOFAGOS</h6>
            </div>
    </div>


<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
       <thead>
            <tr>
            <th class="text-center">Elemento afectado</th>
            <th class="text-center">Tipo de construcción</th>
            <th class="text-center">Tratamiento aplicado</th>
            <th class="text-center">Producto</th>
            <th class="text-center">Plaga</th>
            <th class="text-center">Nivel infestación</th>
            <th class="text-center">Fecha tratamiento</th>
            <th class="text-center">Proximo tratamiento</th>

            </tr>
        </thead>

            <tbody>

                @foreach($order["xylophage_control"] as $xylophageControl)
                <tr>
                    <td class="text-center">{{$xylophageControl["affected_element"]["name"]}}</td>
                    <td class="text-center">{{$xylophageControl["construction_type"]["name"]}}</td>
                    <td class="text-center">{{$xylophageControl["applied_treatment"]["name"]}}</td>
                    <td class="text-center">{{$xylophageControl["product"]["name"]}}</td>
                    <td class="text-center">{{$xylophageControl["pest"]["common_name"]}}</td>
                    <td class="text-center">{{$xylophageControl["infestation_level"]}}</td>
                    <td class="text-center">{{$xylophageControl["treatment_date"]}}</td>
                    <td class="text-center">{{$xylophageControl["next_treatment_date"]}}</td>

                </tr>

           

                <tr style="width=100%">
                <td colspan="7"><b>Observaciones: </b>{{$xylophageControl["observation"]}}</td>
                </tr>
                @endforeach

            </tbody>
    </table>

    </div>
</div>

@endif

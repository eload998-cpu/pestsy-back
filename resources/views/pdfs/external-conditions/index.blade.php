@if(isset ($order["external_condition"]) && count($order["external_condition"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >CONDICIONES EXTERNAS</h6>
            </div>
</div>



<div class="row clearfix  mt-3">
    <div class="col-xs-6">
        <span><b>Maquinaria obsoleta ordenada o guardada: </b></span>{{translate_conditions($order["external_condition"]["obsolete_machinery"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Drenajes en buen estado: </b></span>{{translate_conditions($order["external_condition"]["sewer_system"])}}
    </div>
</div>

<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Escombros o materiales ordenados:</b> </span>{{translate_conditions($order["external_condition"]["debris"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Contenedores de basura cerrados: </b></span>{{translate_conditions($order["external_condition"]["containers"])}}
    </div>
</div>


<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Focos de contaminación en areas externas:</b> </span>{{translate_conditions($order["external_condition"]["spotlights"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Mantenimiento de areas verdes: </b></span>{{translate_conditions($order["external_condition"]["green_areas"])}}
    </div>
</div>

<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Adecuado manejo de residuos: </b></span>{{translate_conditions($order["external_condition"]["waste"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Lugares de anidacion para plagas: </b></span>{{translate_conditions($order["external_condition"]["nesting"])}}
    </div>
</div>

@endif

@if(isset ($order["internal_condition"]) && count($order["internal_condition"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >CONDICIONES INTERNAS</h6>
            </div>
    </div>


<div class="row clearfix mt-3">
    <div class="col-xs-6">
        <span><b>Paredes sin grietas e irregularidades:</b> </span>{{translate_conditions($order["internal_condition"]["walls"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Techos, cielorrazos en buen estado:</b> </span>{{translate_conditions($order["internal_condition"]["roofs"])}}
    </div>
</div>

<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Pisos limpios y en buen estado: </b></span>{{translate_conditions($order["internal_condition"]["floors"])}}
    </div>

     <div class="col-xs-6">
        <span><b>Equipos y utensilios sucios: </b></span>{{translate_conditions($order["internal_condition"]["cleaning"])}}
    </div>

</div>


<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Sellamientos o exclusiones realizadas correctamente: </b></span>{{translate_conditions($order["internal_condition"]["sealings"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Puertas de acceso cerradas y selladas:</b> </span>{{translate_conditions($order["internal_condition"]["closed_doors"])}}
    </div>
</div>

<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Ventanas con cedazos en buen estado: </b></span>{{translate_conditions($order["internal_condition"]["windows"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Evidencias de plagas en las instalaciones: </b></span>{{translate_conditions($order["internal_condition"]["pests_facilities"])}}
    </div>
</div>

<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Adecuado almacenamiento de MP, ME, PT:</b> </span>{{translate_conditions($order["internal_condition"]["storage"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Basureros limpios, tapados y en buen estado:</b> </span>{{translate_conditions($order["internal_condition"]["garbage_cans"])}}
    </div>
</div>

<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Limpieza debajo y detrás del mobiliario:</b> </span>{{translate_conditions($order["internal_condition"]["space"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Separación entre equipos :</b> </span>{{translate_conditions($order["internal_condition"]["equipment"])}}
    </div>
</div>

<div class="row clearfix ">
    <div class="col-xs-6">
        <span><b>Evidencias de plagas detras de rótulos:</b></span>{{translate_conditions($order["internal_condition"]["evidences"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Adecuada ventilacion e iluminacion:</b>  </span>{{translate_conditions($order["internal_condition"]["ventilation"])}}
    </div>
</div>

<div class="row clearfix ">
    <div class="col-xs-6">
        <span> <b>Paredes, bisagras, rodapiés, tuberías, marcos de puertas y/o equipos limpios:</b></span>{{translate_conditions($order["internal_condition"]["clean_walls"])}}
    </div>

    <div class="col-xs-6">
        <span><b>Ductos, bajantes de agua y elec. Sellados:</b>  </span>{{translate_conditions($order["internal_condition"]["ducts"])}}
    </div>
</div>

@endif

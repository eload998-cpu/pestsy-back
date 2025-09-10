
<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >TÉCNICO RESPONSABLE</h6>
            </div>
</div>


    <div class="row clearfix mt-3">
        <div class="col-xs-4 mt-2">
            <span><b>Nombre completo:</b></span> {{ $order['worker']['full_name'] }}
        </div>

        <div class="col-xs-4 mt-2">
            <span><b>Certificación:</b></span>
            {{ (isset($order['worker']['certification_title']))? $order['worker']['certification_title']: 'N/A'}}
        </div>

        <div class="col-xs-4 mt-2">
            <span><b>Nro de identificación</b></span>
            {{ (isset($order['worker']['identification_number']))? $order['worker']['identification_number']: 'N/A'}}

        </div>


    </div>


        <div class="row clearfix">
        <div class="col-xs-4 mt-2">
            <span><b>Fecha de certificación:</b></span>
                   {{ (isset($order['worker']['certification_date']))? $order['worker']['certification_date']: 'N/A'}}

        </div>

        <div class="col-xs-4 mt-2">
            <span><b>Institución que certifica</b></span>
            {{ (isset($order['worker']['certifying_entity']))? $order['worker']['certifying_entity']: 'N/A'}}
        </div>

        <div class="col-xs-4 mt-2">
            <span><b>Teléfono:</b></span>
            {{ (isset($order['worker']['cellphone']))? $order['worker']['cellphone']: 'N/A'}}
        </div>
    </div>

@if(isset ($order["rodent_controls"]) && count($order["rodent_controls"]))
<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >CONTROL DE ROEDORES</h6>
            </div>
    </div>


<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
<thead>
      <tr>
      <th class="text-center">Numero</th>
      <th class="text-center">Dispositivo</th>
      <th class="text-center">Producto</th>
      <th class="text-center">Dosis</th>
      <th class="text-center">Ubicacion</th>
      <th class="text-center">Limpieza</th>

    </tr>
    </thead>

    <tbody>

        @foreach($order["rodent_controls"] as $rodent_control)
        <tr>
            <td class="text-center">{{$rodent_control["device_number"]}}</td>
            <td class="text-center">{{$rodent_control["device"]["name"]}}</td>
            <td class="text-center">{{$rodent_control["product"]["name"]}}</td>
            <td class="text-center">{{$rodent_control["dose"]}}</td>
            <td class="text-center">{{$rodent_control["location"]["name"]}}</td>
            <td class="text-center">{{translate_done_not_done($rodent_control["cleaning"]?'done':'not done')}}</td>
     

        </tr>
        
        <tr style="width=100%">
            <td colspan="2">
            <b>  Se realizó limpieza:</b>{{translate_yes_no($rodent_control["finished_cleaning"])}}
            </td>
            <td colspan="2">
            <b> Cambio de cebo:</b>{{translate_done_not_done($rodent_control["bait_change"]?'done':'not done')}}
            </td>

            <td colspan="2">
            <b> Actividad:</b>{{translate_monitoring_activities($rodent_control["activity"])}}
            </td>
        </tr>

        <tr style="width=100%">
         <td colspan="7"><b>Observaciones: </b>{{$rodent_control["observation"]}}</td> 
        </tr>

        @if(!empty($rodent_control["pest_bitacores"]))
        <tr style="width=100%">
        <td colspan="7">
        <table class="table" >
                    <thead>
                    <tr>
                    <th class="text-center"  style="width=100%">Plaga</th>
                    <th class="text-center"  style="width=100%">Cantidad</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($rodent_control["pest_bitacores"] as $pest_bitacore)
                    <tr>
                        <td class="text-center">{{$pest_bitacore["pest"]["common_name"]}}</td>
                        <td class="text-center">{{$pest_bitacore["quantity"]}}</td>
                    </tr>
                     @endforeach
          
                    </tbody>
                </table>
            </td> 
        </tr>
        @endif
    
    
        @endforeach
    </tbody>
</table>

</div>
</div>

@endif
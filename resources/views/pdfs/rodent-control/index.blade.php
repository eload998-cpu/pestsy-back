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


        @if(!empty($rodent_control["order_corrective_actions"]))
        <tr>
            <td colspan="7" style="padding: 10px;">
                <strong>Acciones correctivas:</strong>
                @php
                    $actions = collect($rodent_control["order_corrective_actions"])
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

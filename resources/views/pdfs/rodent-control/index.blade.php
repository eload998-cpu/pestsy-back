@if(isset ($order["rodent_controls"]) && count($order["rodent_controls"]))
<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >CONTROL DE ROEDORES</h6>
            </div>
    </div>

<div class="row clearfix">
        <div class="col-xs-12 mt-2">
            <span><b>Peligro controlado: roedores – CCP: dosis, área tratada, método aplicado</b></span>

        </div>
</div>


<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
    <tbody>

        @foreach($order["rodent_controls"] as $rodent_control)

         <tr style="width=100%">
                <td class="text-center" colspan="6"><b>#{{ $loop->iteration }}</b></td>
         </tr>

         <tr style="width=100%">
                <td><b>Numero de dispositivo: </b>{{$rodent_control["device_number"]}}</td>
                <td><b>Dispositivo: </b>{{$rodent_control["device"]["name"]}}</td>
                <td colspan="2"><b>Producto: </b>{{$rodent_control["product"]["name"]}}</td>
                <td colspan="2"><b>Nivel de infestación: </b>{{$rodent_control["infestation_level"]}}</td>

         </tr>

         <tr style="width=100%">

                <td><b>Dosis: </b>{{$rodent_control["dose"]}}</td>
                <td><b>Ubicacion: </b>{{$rodent_control["location"]["name"]}}</td>
                <td colspan="2"><b>Técnico responsable:</b>  {{$rodent_control["worker"]["full_name"]}}</td>
                <td colspan="2"><b>Hora aplicación: </b>{{\Carbon\Carbon::parse($rodent_control["application_time"])->format('h:i A')}}</td>
         </tr>

         <tr style="width=100%">

                <td><b>Tratamiento Aplicado: </b>{{$rodent_control["application"]["name"]}} </td>
         </tr>

        @if($rodent_control["corrective_actions"])
                <tr style="width=100%">
                <td colspan="6">
                        <b>Acciones correctivas:</b>
                        @php
                            $actions = collect($rodent_control["corrective_actions"])
                                        ->pluck('corrective_action.name')
                                        ->filter()
                                        ->implode(', ');
                        @endphp

                        <span>{{ $actions }}</span>
                    </td>
                </tr>
         @endif

        <tr style="width=100%">
                <td colspan="6"><b>Observación: </b>{{$rodent_control["observation"]}}</td>
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

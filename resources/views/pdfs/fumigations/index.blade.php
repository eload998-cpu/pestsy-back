
@if(count($order["fumigations"]))

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >FUMIGACIONES</h6>
            </div>
    </div>


<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="table">
       <thead>
            <tr>
            <th class="text-center">Aplicación</th>
            <th class="text-center">Lugar</th>
            <th class="text-center">Producto</th>
            <th class="text-center">Dosis</th>


            </tr>
        </thead>

            <tbody>

                @foreach($order["fumigations"] as $fumigation)
                <tr>
                    <td class="text-center">{{$fumigation["aplication"]["name"]}}</td>
                    <td class="text-center">{{$fumigation["aplication_place"]["name"]}}</td>
                    <td class="text-center">{{$fumigation["product"]["name"]}}</td>
                    <td class="text-center">{{$fumigation["dose"]}}</td>
                </tr>
                @endforeach

            </tbody>
    </table>

    </div>
</div>

@endif


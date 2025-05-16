
@if(isset ($order["observations"]) && $order["observations"])

<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >OBSERVACIONES GENERALES</h6>
            </div>
  </div>
<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="">

            <tbody>

                @foreach($order["observations"] as $observation)
                <tr>
                    <td class="">* {{$observation["observation"]}}</td>
                </tr>

                @endforeach
            </tbody>
    </table>

    </div>
</div>

@endif

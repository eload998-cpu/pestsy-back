@if(isset ($order["images"]) && $order["images"])
<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
            <div class="col-xs-12 mt-3">
                <h6 class="text-center" >IMAGENES ADJUNTAS</h6>
            </div>
  </div>


<div class="row clearfix mt-3">
<div class="col-xs-12">
<table class="" style="width:100%;">
  
            <tbody>
                @foreach($order["images"] as $image)
                @if($image['file_name'])
                <tr >
                <td class="text-center">
                <img style="max-width:350px; max-height:350px; margin-top:1rem;" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($image['file_name']))) }}" alt="">
                 </td>
                </tr>
                @endif
                @endforeach
            </tbody>
    </table>

    </div>
</div>
@endif


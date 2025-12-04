@if (isset($order['signatures']) && $order['signatures'])
    <div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">Firmas</h6>
        </div>
    </div>

    <div class="row clearfix mt-3">

        @foreach ($order['signatures'] as $signature)
            @if ($signature['client_signature_url'])
                <div class="col-xs-6 text-center">
                    <img style="width:150px;"
                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($signature['client_signature_url']))) }}"
                        alt="">

                    <div class="text-center"><b>Firma del Cliente</b></div>
                </div>
            @endif

            @if ($signature['worker_signature_url'])
                <div class="col-xs-6 text-center">
                    <img style="width:150px;"
                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($signature['worker_signature_url']))) }}"
                        alt="">
                    <div class="text-center"><b>Firma del Técnico</b></div>

                </div>
            @endif
        @endforeach
    </div>

@endif

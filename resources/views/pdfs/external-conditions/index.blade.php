@php
    // Just chunk, DON'T call values() here
$chunks = collect($order['external_conditions'])
    ->filter(fn($item) => $item['value'] !== 'no_apply')
        ->values()
        ->chunk(2);
@endphp

@if ($chunks->count())
    <div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">CONDICIONES EXTERNAS</h6>
        </div>
    </div>

    @foreach ($chunks as $pair)
        @php
            // Reindex the keys for THIS pair: now it's [0, 1]
            $pair = $pair->values();
        @endphp

        <div class="row clearfix mt-2">
            <div class="col-xs-6">
                @if (isset($pair[0]))
                    <span>
                        <b>{{ $pair[0]['external_condition']['name'] }}: </b>
                    </span>
                    {{ translate_conditions($pair[0]['value']) }}
                @endif
            </div>

            <div class="col-xs-5">
                @if (isset($pair[1]))
                    <span>
                        <b>{{ $pair[1]['external_condition']['name'] }}: </b>
                    </span>
                    {{ translate_conditions($pair[1]['value']) }}
                @endif
            </div>
        </div>
    @endforeach
@endif

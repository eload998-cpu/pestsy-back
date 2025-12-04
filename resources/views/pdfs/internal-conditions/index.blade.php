@php
    // Filter out "no_apply" and chunk every 2 items
    $chunks = collect($order['internal_conditions'])
        ->filter(fn($item) => $item['value'] !== 'no_apply')
        ->values()
        ->chunk(2);
@endphp

@if ($chunks->count())
    <div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">Condiciones internas</h6>
        </div>
    </div>

    <table class="table mt-2">
        <tbody>
            @foreach ($chunks as $pair)
                @php $pair = $pair->values(); @endphp

                <tr>
                    <td style="width:50%;">
                        @if (isset($pair[0]))
                            <b>{{ $pair[0]['internal_condition']['name'] }}:</b>
                            {{ translate_conditions($pair[0]['value']) }}
                        @endif
                    </td>

                    <td style="width:50%;">
                        @if (isset($pair[1]))
                            <b>{{ $pair[1]['internal_condition']['name'] }}:</b>
                            {{ translate_conditions($pair[1]['value']) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

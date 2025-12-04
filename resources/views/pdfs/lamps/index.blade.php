@if (isset($order['lamps']) && count($order['lamps']))

    <div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">Lamparas de luz</h6>
        </div>
    </div>

    <div class="row clearfix mt-3">
        <div class="col-xs-12">
            <table class="table">
                <tbody>
                    @foreach ($order['lamps'] as $lamp)
                        <tr style="width:100%">
                            <td class="text-center" colspan="6"><b>#{{ $loop->iteration }}</b></td>
                        </tr>

                        <tr style="width:100%">
                            <td><b>Número Estación: </b>{{ $lamp['station_number'] }}</td>
                            <td><b>Cambio de plancha gomosa: </b>{{ translate_yes_no($lamp['rubbery_iron_changed']) }}
                            </td>
                            <td colspan="2"><b>Limpieza: </b>{{ translate_yes_no($lamp['lamp_cleaning']) }}</td>
                            <td colspan="2"><b>Lampara en mal estado:
                                </b>{{ translate_yes_no($lamp['lamp_not_working']) }}</td>
                        </tr>

                        <tr style="width:100%">
                            <td><b>Cambio de Fluorescente: </b>{{ translate_yes_no($lamp['fluorescent_change']) }}</td>
                            <td><b>Cantidad:
                                </b>{{ !empty($lamp['quantity_replaced']) ? $lamp['quantity_replaced'] : '0' }}
                            </td>
                            <td colspan="2"><b>Técnico responsable: </b>{{ $lamp['worker']['full_name'] }}</td>
                            <td colspan="2"><b>Hora aplicación:
                                </b>{{ \Carbon\Carbon::parse($lamp['application_time'])->format('h:i A') }}</td>
                        </tr>

                        <tr style="width:100%">
                            <td colspan="2"><b>Producto aplicado: </b>{{ $lamp['product']['name'] }}</td>
                            <td colspan="2"><b>Dentro de límites críticos:
                                </b>{{ $lamp['within_critical_limits'] ? 'Si' : 'No' }}</td>
                            <td colspan="2"><b>Nivel de infestación: </b>{{ $lamp['infestation_level'] }}</td>
                        </tr>

                        @if (!empty($lamp['observation']))
                            <tr style="width:100%">
                                <td colspan="6"><b>Observaciones: </b>{{ $lamp['observation'] }}</td>
                            </tr>
                        @endif

                        @if (!empty($lamp['corrective_actions']))
                            <tr style="width:100%">
                                <td colspan="6" style="padding: 10px;">
                                    <b>Acciones correctivas:</b>
                                    @php
                                        $actions = collect($lamp['corrective_actions'])
                                            ->pluck('corrective_action.name')
                                            ->filter()
                                            ->implode(', ');
                                    @endphp
                                    <span>{{ $actions }}</span>
                                </td>
                            </tr>
                        @endif

                        @if (!empty($lamp['captures']))
                            <tr style="width:100%">
                                <td colspan="6">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Plaga</th>
                                                <th class="text-center">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lamp['captures'] as $captures)
                                                <tr>
                                                    <td class="text-center">{{ $captures['pest']['common_name'] }}</td>
                                                    <td class="text-center">{{ $captures['quantity'] }}</td>
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

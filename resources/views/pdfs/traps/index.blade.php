@if (isset($order['traps']) && count($order['traps']))

    <div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">Trampas</h6>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 mt-2">
            <span><b>Peligro controlado: insectos – CCP: dosis, área tratada, método aplicado</b></span>
        </div>
    </div>

    <div class="row clearfix mt-3">
        <div class="col-xs-12">
            <table class="table">
                <tbody>
                    @foreach ($order['traps'] as $trap)
                        <tr style="width:100%">
                            <td class="text-center" colspan="6"><b>#{{ $loop->iteration }}</b></td>
                        </tr>

                        <tr style="width:100%">
                            <td><b>Número Estación: </b>{{ $trap['station_number'] }}</td>
                            <td><b>Posee feromonas: </b>{{ translate_yes_no($trap['pheromones']) }}</td>
                            <td colspan="2"><b>Estado de feromonas: </b>{{ $trap['pheromones_state'] }}</td>
                            <td colspan="2"><b>Lugar aplicación: </b>{{ $trap['location']['name'] }}</td>
                        </tr>

                        <tr style="width:100%">
                            <td><b>Dispositivo: </b>{{ $trap['device']['name'] }}</td>
                            <td><b>Trampa en mal funcionamiento: </b>{{ $trap['condition'] }}</td>
                            <td colspan="2"><b>Producto: </b>{{ $trap['product']['name'] }}</td>
                            <td colspan="2"><b>Dosis: </b>{{ $trap['dose'] }}</td>
                        </tr>

                        <tr style="width:100%">
                            <td><b>Técnico responsable: </b>{{ $trap['worker']['full_name'] }}</td>
                            <td><b>Hora aplicación:
                                </b>{{ \Carbon\Carbon::parse($trap['application_time'])->format('h:i A') }}</td>
                            <td colspan="2"><b>Dentro de límites críticos:
                                </b>{{ $trap['within_critical_limits'] ? 'Si' : 'No' }}</td>
                            <td colspan="2"><b>Nivel de infestación: </b>{{ $trap['infestation_level'] }}</td>
                        </tr>

                        @if (!empty($trap['corrective_actions']))
                            <tr style="width:100%">
                                <td colspan="6">
                                    <b>Acciones correctivas:</b>
                                    @php
                                        $actions = collect($trap['corrective_actions'])
                                            ->pluck('corrective_action.name')
                                            ->filter()
                                            ->implode(', ');
                                    @endphp
                                    <span>{{ $actions }}</span>
                                </td>
                            </tr>
                        @endif

                        @if (!empty($trap['captures']))
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
                                            @foreach ($trap['captures'] as $captures)
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

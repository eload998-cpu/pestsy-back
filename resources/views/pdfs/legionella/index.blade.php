@if (isset($order['legionella_control']) && count($order['legionella_control']))

    <div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">Control de legionella</h6>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 mt-2">
            <span><b>Peligro controlado: legionela – CCP: dosis, área tratada, método aplicado</b></span>

        </div>
    </div>


    <div class="row clearfix mt-3">
        <div class="col-xs-12">
            <table class="table">

                <tbody>

                    @foreach ($order['legionella_control'] as $legionellaControl)
                        <tr style="width=100%">
                            <td class="text-center" colspan="6"><b>#{{ $loop->iteration }}</b></td>
                        </tr>

                        <tr style="width=100%">
                            <td><b>Instalación evaluada: </b>{{ $legionellaControl['location']['name'] }}</td>
                            <td><b>Metodo aplicado: </b>{{ $legionellaControl['application']['name'] }}</td>
                            <td colspan="2"><b>Resultado de inspección:
                                </b>{{ $legionellaControl['inspection_result'] }}</td>
                        </tr>

                        <tr style="width=100%">
                            <td><b>Ultima revisión:
                                </b>{{ \Carbon\Carbon::parse($legionellaControl['last_treatment_date'])->format('d/m/Y') }}
                            </td>
                            <td><b>Proxima revisión:
                                </b>{{ \Carbon\Carbon::parse($legionellaControl['next_treatment_date'])->format('d/m/Y') }}
                            </td>
                            <td colspan="2"><b>Requiere muestra:
                                </b>{{ $legionellaControl['sample_required'] ? 'Si' : 'No' }}</td>
                            <td colspan="2"><b>Temperatura del agua:
                                </b>{{ empty($legionellaControl['water_temperature']) ? 'No aplica' : $legionellaControl['water_temperature'] }}
                            </td>
                        </tr>

                        <tr style="width=100%">
                            <td><b>Nivel de cloro residual:
                                </b>{{ empty($legionellaControl['residual_chlorine_level']) ? 'No aplica' : $legionellaControl['residual_chlorine_level'] }}
                            </td>
                            <td><b>Tecnico que ejecuta: </b>{{ $legionellaControl['worker']['full_name'] }}</td>
                            <td colspan="2"><b>Dentro de límites críticos:
                                </b>{{ $legionellaControl['within_critical_limits'] ? 'Si' : 'No' }}</td>
                            <td colspan="2"><b>Producto aplicado: </b>{{ $legionellaControl['product']['name'] }}
                            </td>

                        </tr>


                        @if ($legionellaControl['corrective_actions'])
                            <tr style="width=100%">
                                <td colspan="6">
                                    <b>Acciones correctivas:</b>
                                    @php
                                        $actions = collect($legionellaControl['corrective_actions'])
                                            ->pluck('corrective_action.name')
                                            ->filter()
                                            ->implode(', ');
                                    @endphp

                                    <span>{{ $actions }}</span>
                                </td>
                            </tr>
                        @endif

                        @if (!empty($legionellaControl['observation']))
                            <tr style="width=100%">
                                <td colspan="6"><b>Observación: </b>{{ $legionellaControl['observation'] }}</td>
                            </tr>
                        @endif


                        @if ($loop->iteration % 2 === 0 && !$loop->last)
                            <tr>
                                <td colspan="6" style="page-break-after: always;"></td>
                            </tr>
                        @endif
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>

@endif

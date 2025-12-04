@if (isset($order['xylophage_control']) && count($order['xylophage_control']))

    <div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">Control de xilófagos</h6>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 mt-2">
            <span><b>Peligro controlado: xilófagos – CCP: dosis, área tratada, método aplicado</b></span>

        </div>




    </div>


    <div class="row clearfix mt-3">
        <div class="col-xs-12">
            <table class="table">

                <tbody>

                    @foreach ($order['xylophage_control'] as $xylophageControl)
                        <tr style="width=100%">
                            <td class="text-center" colspan="6"><b>#{{ $loop->iteration }}</b></td>
                        </tr>

                        <tr style="width=100%">
                            <td><b>Plaga: </b>{{ $xylophageControl['pest']['common_name'] }}</td>
                            <td><b>Producto: </b>{{ $xylophageControl['product']['name'] }}</td>
                            <td colspan="2"><b>Fecha tratamiento:
                                </b>{{ \Carbon\Carbon::parse($xylophageControl['treatment_date'])->format('d/m/Y') }}
                            </td>
                            <td colspan="2"><b>Próxima revisión:
                                </b>{{ \Carbon\Carbon::parse($xylophageControl['next_treatment_date'])->format('d/m/Y') }}
                            </td>
                        </tr>

                        <tr style="width=100%">
                            <td><b>Nivel infestación: </b>{{ $xylophageControl['infestation_level'] }}</td>
                            <td><b>Dosis: </b>{{ $xylophageControl['dose'] }}</td>
                            <td colspan="2"><b>Area tratada: </b>{{ $xylophageControl['treated_area_value'] }}
                                {{ $xylophageControl['treated_area_unit'] }}</td>
                            <td colspan="2"><b>Dosis total: </b>{{ $xylophageControl['calculated_total_amount'] }}
                                {{ $xylophageControl['calculated_total_unit'] }}</td>

                        </tr>




                        <tr style="width=100%">
                            <td colspan="2"><b>Elemento afectado:
                                </b>{{ $xylophageControl['affected_element']['name'] }}</td>
                            <td colspan="2"><b>Tipo construcción:
                                </b>{{ $xylophageControl['construction_type']['name'] }}</td>
                            <td colspan="2"><b>Metodo aplicado: </b>{{ $xylophageControl['application']['name'] }}
                            </td>

                        </tr>

                        <tr style="width=100%">

                            <td colspan="2"><b>Ubicación: </b>{{ $xylophageControl['location']['name'] }}</td>
                            <td colspan="2"><b>Verificación de eficacia:
                                </b>{{ $xylophageControl['effectiveness_verification'] }}</td>
                            <td colspan="2"><b>Tecnico que ejecuta:
                                </b>{{ $xylophageControl['worker']['full_name'] }}</td>
                        </tr>



                        @if (isset($xylophageControl['pre_humidity']) ||
                                isset($xylophageControl['pre_ventilation']) ||
                                isset($xylophageControl['pre_access']) ||
                                isset($xylophageControl['pre_notes']))
                            <tr>
                                <td class="text-center" colspan="6"><b>Condiciones previas </b></td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <b>Humedad: </b>
                                    {{ $xylophageControl['pre_humidity'] ? $xylophageControl['pre_humidity'] : 'N/A' }}
                                </td>
                                <td colspan="2">
                                    <b>Ventilación: </b>
                                    {{ $xylophageControl['pre_ventilation'] ? $xylophageControl['pre_ventilation'] : 'N/A' }}
                                </td>
                                <td colspan="2">
                                    <b>Acceso: </b>
                                    {{ $xylophageControl['pre_access'] ? $xylophageControl['pre_access'] : 'N/A' }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <b>Notas previas: </b>
                                    {{ $xylophageControl['pre_notes'] ? $xylophageControl['pre_notes'] : 'N/A' }}
                                </td>
                            </tr>
                        @endif

                        @if (isset($xylophageControl['post_humidity']) ||
                                isset($xylophageControl['post_ventilation']) ||
                                isset($xylophageControl['post_access']) ||
                                isset($xylophageControl['post_notes']))
                            <tr>
                                <td class="text-center" colspan="6"><b>Condiciones posteriores </b></td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <b>Humedad: </b>
                                    {{ $xylophageControl['post_humidity'] ? $xylophageControl['post_humidity'] : 'N/A' }}
                                </td>
                                <td colspan="2">
                                    <b>Ventilación: </b>
                                    {{ $xylophageControl['post_ventilation'] ? $xylophageControl['post_ventilation'] : 'N/A' }}
                                </td>
                                <td colspan="2">
                                    <b>Acceso: </b>
                                    {{ $xylophageControl['post_access'] ? $xylophageControl['post_access'] : 'N/A' }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <b>Notas previas: </b>
                                    {{ $xylophageControl['post_notes'] ? $xylophageControl['post_notes'] : 'N/A' }}
                                </td>
                            </tr>
                        @endif


                        @if ($xylophageControl['corrective_actions'])
                            <tr style="width=100%">
                                <td colspan="6">
                                    <b>Acciones correctivas:</b>
                                    @php
                                        $actions = collect($xylophageControl['corrective_actions'])
                                            ->pluck('corrective_action.name')
                                            ->filter()
                                            ->implode(', ');
                                    @endphp

                                    <span>{{ $actions }}</span>
                                </td>
                            </tr>
                        @endif

                        @if (!empty($xylophageControl['observation']))
                            <tr style="width=100%">
                                <td colspan="6"><b>Observación: </b>{{ $xylophageControl['observation'] }}</td>
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

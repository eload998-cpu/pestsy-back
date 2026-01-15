@if (isset($order['products']) && count($order['products']))

    <div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
        <div class="col-xs-12 mt-3">
            <h6 class="text-center">Productos utilizados</h6>
        </div>
    </div>



    <div class="row clearfix mt-3">
        <div class="col-xs-12">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Ingrediente activo</th>
                        <th class="text-center">Concentración (%)</th>
                        <th class="text-center">Dosis</th>
                        <th class="text-center">Fecha expiración</th>
                        <th class="text-center">Lote</th>
                        <th class="text-center">Codigo</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($order['products'] as $product)
                        <tr>
                            <td class="text-center">{{ $product['name'] }}</td>
                            <td class="text-center">
                                {{ !empty($product['active_ingredient']) ? $product['active_ingredient'] : 'N/A' }}</td>
                            <td class="text-center">
                                {{ !empty($product['concentration']) ? $product['concentration'] : 'N/A' }}</td>
                            <td class="text-center">{{ !empty($product['dose']) ? $product['dose'] : 'N/A' }}</td>
                            <td class="text-center">
                                {{ !empty($product['expiration_date']) ? \Carbon\Carbon::parse($product['expiration_date'])->format('d/m/y') : 'N/A' }}
                            </td>
                            <td class="text-center">{{ !empty($product['batch']) ? $product['batch'] : 'N/A' }}</td>
                            <td class="text-center">{{ !empty($product['registration_code']) ? $product['registration_code'] : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endif

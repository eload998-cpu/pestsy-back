<div class="row clearfix block-header-row mt-3" style="background-color:#76D292; color:White !important;">
    <div class="col-xs-12 mt-3">
        <h6 class="text-center">Técnico responsable</h6>
    </div>
</div>

<table class="table mt-3">
    <tbody>
        <tr>
            <td><b>Nombre completo:</b> {{ $order['worker']['full_name'] }}</td>
            <td><b>Certificación:</b> {{ $order['worker']['certification_title'] ?? 'N/A' }}</td>
            <td><b>Nro de identificación:</b> {{ $order['worker']['identification_number'] ?? 'N/A' }}</td>
        </tr>

        <tr>
            <td><b>Fecha de certificación:</b> {{ $order['worker']['certification_date'] ?? 'N/A' }}</td>
            <td><b>Institución que certifica:</b> {{ $order['worker']['certifying_entity'] ?? 'N/A' }}</td>
            <td><b>Teléfono:</b> {{ $order['worker']['cellphone'] ?? 'N/A' }}</td>
        </tr>
    </tbody>
</table>

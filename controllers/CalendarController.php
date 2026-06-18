<?php

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

$sql = "

SELECT

    c.id_cita,

    cli.nombre_completo AS cliente,

    u.nombre_completo AS barbero,

    c.fecha_cita,

    c.hora_cita,

    c.estado_cita,

    s.nombre_servicio

FROM citas c

INNER JOIN clientes cli
ON c.id_cliente = cli.id_cliente

INNER JOIN usuarios u
ON c.id_barbero = u.id_usuario

INNER JOIN servicios s
ON c.id_servicio = s.id_servicio

";

$stmt = $db->prepare($sql);
$stmt->execute();

$eventos = [];

while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $color = '#0d6efd';

    switch($fila['estado_cita']){

        case 'Programada':
            $color = '#ffc107';
        break;

        case 'Confirmada':
            $color = '#198754';
        break;

        case 'En Proceso':
            $color = '#0dcaf0';
        break;

        case 'Finalizada':
            $color = '#6f42c1';
        break;

        case 'Cancelada':
            $color = '#dc3545';
        break;
    }

    $eventos[] = [

        'title' =>
            $fila['cliente']
            . ' - '
            . $fila['nombre_servicio'],

        'start' =>
            $fila['fecha_cita']
            . 'T'
            . $fila['hora_cita'],

        'backgroundColor' => $color,

        'borderColor' => $color
    ];
}

header('Content-Type: application/json');

echo json_encode($eventos);
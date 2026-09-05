<?php
header('Content-Type: application/json');

require_once 'conexion.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $equipo_id = trim($data['equipo_id'] ?? '');
    $equipo_nombre = trim($data['equipo_nombre'] ?? 'Equipo');
    $monto = trim($data['monto'] ?? '');
    $parcialidad = trim($data['parcialidad'] ?? '');
    $fecha = trim($data['fecha'] ?? '');
    
    // Datos bancarios
    $metodo_pago = $data['metodo_pago'] ?? 'Efectivo';
    $folio_txn = $data['folio'] ?? '';
    $auth_txn = $data['autorizacion_bancaria'] ?? '';
    $concepto_pago = "Abono (Parcialidad $parcialidad)";

    if (empty($equipo_id) || empty($monto) || empty($parcialidad) || empty($fecha)) {
        throw new Exception("Faltan datos para registrar el abono.");
    }

    $id_usuario = 1; 

    $stmt = $pdo->prepare("INSERT INTO `registro de abono` (Usuario_IdUsuario, MontoAbonado, Parcialidad, FechaDeAbono) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_usuario, $monto, $parcialidad, $fecha]);

    echo json_encode([
        'success' => true,
        'mensaje' => 'Abono registrado correctamente.',
        'transaccion' => [
            'tipo_pago' => 'ABONO',
            'folio' => $folio_txn ?: 'ABN-' . time(),
            'autorizacion_bancaria' => $auth_txn,
            'concepto' => $concepto_pago,
            'equipo_nombre' => $equipo_nombre,
            'monto' => $monto,
            'fecha' => $fecha,
            'hora' => date('H:i:s'),
            'metodo_pago' => $metodo_pago
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Ocurrió un error en el servidor. Intenta nuevamente.'
    ]);
}
?>
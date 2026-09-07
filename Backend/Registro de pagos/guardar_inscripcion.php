<?php
header('Content-Type: application/json');

require_once 'conexion.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $equipo_id = $data['equipo_id'] ?? null;
    $fecha = $data['fecha'] ?? null;
    
    // Recibimos datos bancarios opcionales del simulador
    $metodo_pago = $data['metodo_pago'] ?? 'Efectivo';
    $folio_txn = $data['folio'] ?? '';
    $auth_txn = $data['autorizacion_bancaria'] ?? '';
    $equipo_nombre = $data['equipo_nombre'] ?? 'Equipo Desconocido';
    
    $monto = 1500; // Monto fijo de inscripción
    // Concatenamos detalles bancarios en el concepto por compatibilidad con la tabla actual
    $concepto = 'Inscripción de Equipo' . ($folio_txn ? " ($metodo_pago | Folio: $folio_txn)" : "");
    $estatus = 'PAGADO';

    if (!$equipo_id || !$fecha) {
        throw new Exception("Faltan datos obligatorios.");
    }

    $stmtJugador = $pdo->prepare("
        SELECT IdJugador 
        FROM `jugadores` 
        WHERE `IdEquipo` = ? 
        LIMIT 1
    ");
    $stmtJugador->execute([$equipo_id]);
    $jugador = $stmtJugador->fetch(PDO::FETCH_ASSOC);

    $id_jugador = $jugador ? $jugador['IdJugador'] : 1; 

    // Insertar el pago en la base de datos
    $stmt = $pdo->prepare("INSERT INTO `registro de pagos` (Jugadores_IdJugador, Monto, Concepto, FechaDePago, EstatusDePago) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_jugador, $monto, $concepto, $fecha, $estatus]);

    // Devolver el JSON estructurado con todos los datos para el PDF
    echo json_encode([
        'success' => true,
        'mensaje' => 'Pago de inscripción registrado exitosamente.',
        'transaccion' => [
            'tipo_pago' => 'INSCRIPCIÓN',
            'folio' => $folio_txn ?: 'REC-' . time(),
            'autorizacion_bancaria' => $auth_txn,
            'concepto' => 'Inscripción de Torneo',
            'equipo_nombre' => $equipo_nombre,
            'monto' => $monto,
            'fecha' => $fecha,
            'hora' => date('H:i:s'),
            'metodo_pago' => $metodo_pago
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Ocurrió un error: ' . $e->getMessage()]);
}
?>
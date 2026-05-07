<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$dbname = 'club_leon';
$db_user = 'root';
$db_pass = 'admin'; // Añade tu contraseña si es necesario

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    $equipo_id = $data['equipo_id'] ?? null;
    $fecha = $data['fecha'] ?? null;
    $monto = 1500; // Monto fijo de inscripción
    $concepto = 'Inscripción de Equipo';
    $estatus = 'PAGADO';

    if (!$equipo_id || !$fecha) {
        throw new Exception("Faltan datos obligatorios.");
    }

    // Buscamos un jugador que pertenezca a ese equipo para asociarle el pago (según tu diagrama)
    // Nota: Esta es una adaptación temporal. Idealmente, la tabla Registro de Pagos debería aceptar IdEquipo.
    $stmtJugador = $pdo->prepare("
        SELECT Jugadores_IdJugador 
        FROM `Jugadores_has_Rol de Juego` 
        WHERE `Rol de Juego_Equipos_IdEquipo` = ? 
        LIMIT 1
    ");
    $stmtJugador->execute([$equipo_id]);
    $jugador = $stmtJugador->fetch(PDO::FETCH_ASSOC);

    $id_jugador = $jugador ? $jugador['Jugadores_IdJugador'] : 1; // Si no hay jugador, asignamos 1 por defecto para la prueba

    // Insertar el pago en la base de datos
    $stmt = $pdo->prepare("INSERT INTO `Registro de Pagos` (Jugadores_IdJugador, Monto, Concepto, FechaDePago, EstatusDePago) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_jugador, $monto, $concepto, $fecha, $estatus]);

    echo json_encode(['success' => true, 'mensaje' => 'Pago de inscripción registrado exitosamente.']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Error al registrar el pago: ' . $e->getMessage()]);
}
?>
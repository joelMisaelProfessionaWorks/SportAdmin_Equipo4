<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$dbname = 'club_leon';
$db_user = 'root';
$db_pass = 'admin'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    $equipo_local = trim($data['equipo_local'] ?? '');
    $equipo_visitante = trim($data['equipo_visitante'] ?? '');
    $fecha = trim($data['fecha'] ?? '');
    $hora = trim($data['hora'] ?? '');
    $cancha = trim($data['cancha'] ?? '');

    // Validar que no falten datos
    if (empty($equipo_local) || empty($equipo_visitante) || empty($fecha) || empty($hora) || empty($cancha)) {
        throw new Exception("Todos los campos son obligatorios para programar el partido.");
    }

    // El VAR: Validar que un equipo no juegue contra sí mismo
    if ($equipo_local === $equipo_visitante) {
        throw new Exception("Un equipo no puede jugar contra sí mismo. Revisa la selección.");
    }

    $stmt = $pdo->prepare("INSERT INTO `rol_juegos` (EquipoLocal_Id, EquipoVisitante_Id, Fecha, Hora, Cancha) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$equipo_local, $equipo_visitante, $fecha, $hora, $cancha]);

    echo json_encode([
        'success' => true,
        'mensaje' => '¡Partido programado con éxito!'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Error: ' . $e->getMessage()
    ]);
}
?>
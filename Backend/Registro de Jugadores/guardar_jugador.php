<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$dbname = 'club_leon';
$db_user = 'root';
$db_pass = 'admin'; // Pon tu contraseña aquí si la necesitas

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recibimos los datos del frontend
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = trim($data['nombre'] ?? '');
    $apellido = trim($data['apellido'] ?? '');
    $curp = trim($data['curp'] ?? '');
    $telefono = trim($data['telefono'] ?? '');
    $idEquipo = trim($data['equipo_id'] ?? '');

    // Validación básica en backend
    if (empty($nombre) || empty($apellido) || empty($curp) || empty($telefono) || empty($idEquipo)) {
        throw new Exception("Todos los campos son obligatorios.");
    }

    // Insertar en la tabla Jugadores con TUS nombres de columnas exactos
    $stmt = $pdo->prepare("INSERT INTO `jugadores` (NombreDeJugador, ApellidosDeJugador, Curp, TelEmergencia, IdEquipo) VALUES (?, ?, ?, ?, ?)");
    
    $stmt->execute([$nombre, $apellido, $curp, $telefono, $idEquipo]);

    echo json_encode([
        'success' => true, 
        'mensaje' => "Jugador '$nombre $apellido' registrado con éxito."
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'mensaje' => 'Error al registrar: ' . $e->getMessage()
    ]);
}
?>
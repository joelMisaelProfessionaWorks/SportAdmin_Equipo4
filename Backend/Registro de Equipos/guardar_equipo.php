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

    $encargado = trim($data['nombreEncargado'] ?? '');
    $telefono = trim($data['telefono'] ?? '');
    $equipo = trim($data['nombreEquipo'] ?? '');
    $clave = 'AMISTOSO'; // Clave fija según tu diseño

    // Validación básica en backend
    if (empty($encargado) || empty($telefono) || empty($equipo)) {
        throw new Exception("Todos los campos son obligatorios.");
    }

    // Insertar en la tabla Equipos
    $stmt = $pdo->prepare("INSERT INTO `Equipos` (NombreDelEncargado, TelefonoDelEncargado, Equipo, Clave) VALUES (?, ?, ?, ?)");
    $stmt->execute([$encargado, $telefono, $equipo, $clave]);

    // Opcional: Obtener el ID del equipo recién creado para usarlo después (ej. en registro de jugadores)
    $nuevoId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true, 
        'mensaje' => "Equipo '$equipo' registrado con éxito.",
        'idEquipo' => $nuevoId
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'mensaje' => 'Error al registrar el equipo: ' . $e->getMessage()
    ]);
}
?>
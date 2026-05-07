<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$dbname = 'club_leon';
$db_user = 'root';
$db_pass = 'admin'; // Añade tu contraseña si es necesario

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT IdEquipo, Equipo FROM Equipos ORDER BY Equipo ASC");
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $equipos]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Error al obtener equipos: ' . $e->getMessage()]);
}
?>
<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$dbname = 'club_leon';
$db_user = 'root';
$db_pass = 'admin'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultamos los roles oficiales (Admin, Encargado del rol, Encargado de liga)
    $stmt = $pdo->prepare("SELECT IdRol, NombreRol FROM roles ORDER BY IdRol ASC");
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'data' => $roles
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'mensaje' => 'Error al obtener roles: ' . $e->getMessage()
    ]);
}
?>
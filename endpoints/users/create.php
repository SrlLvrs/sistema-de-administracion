<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

// Obtener parámetros de la URL
$name = isset($_GET['name']) ? $_GET['name'] : '';
$rol = isset($_GET['rol']) ? $_GET['rol'] : '';
$pass = isset($_GET['pass']) ? $_GET['pass'] : '';

if (!empty($name)) {
    $query = "  INSERT INTO usuarios
                SET Nombre = :name, Rol = :rol, Pass = :pass";

    $stmt = $db->prepare($query);

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":rol", $rol);
    $stmt->bindParam(":pass", $pass);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "El usuario ha sido creado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se ha podido crear el usuario."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Faltan datos para crear el usuario."));
}
?>

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
//$email = isset($_GET['email']) ? $_GET['email'] : '';

/*
Hay que crear todas las variables, una por cada columna de la base de datos
Hay que ponerlas todas en el if, para que analice si están o no vacias
Hay que bindearlas usando BINDPARAM
*/

if (!empty($name) /*&& !empty($email)*/) {
    $query = "INSERT INTO usuario SET Nombre=:name";

    $stmt = $db->prepare($query);

    $stmt->bindParam(":name", $name);
    //$stmt->bindParam(":email", $email);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "User was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create user."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

/*
Obtener los parámetros por url
//PRODUCTOS: ID, Descripcion, Precio, Visible
*/
$descripcion = isset($_GET['descripcion']) ? $_GET['descripcion'] : '';
$precio = isset($_GET['precio']) ? $_GET['precio'] : '';

//Comprueba que las variables NO estén vacías
if (!empty($descripcion) /*&& !empty($direccion) && !empty($idsector) && !empty($telefono)*/) {
    $query = "INSERT INTO productos 
            SET Descripcion=:d, Precio=:p";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":d", $descripcion);
    $stmt->bindParam(":p", $precio);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "El producto fue creado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se puede crear el producto. Revisar consola."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Falta información"));
}
?>

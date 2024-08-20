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
Obtiene por url sólo el parámetro de ID de cliente, lo demás se rellena por valores default.
*/
$id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';

//Comprueba que las variables NO estén vacías
if (!empty($id_cliente) /*&& !empty($direccion) && !empty($idsector) && !empty($telefono)*/) {
    $query = "INSERT INTO pedidos 
            SET IDCliente=:id";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":id", $id_cliente);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "El pedido fue creado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se puede crear el pedido. Revisa el log."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Falta información"));
}
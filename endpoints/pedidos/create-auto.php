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
Obtiene por url el ID de cliente y la fecha de creación, lo demás se rellena por default.
*/
$id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';
$id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : '';
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';
$ultimo_pedido = isset($_GET['ultimo_pedido']) ? $_GET['ultimo_pedido'] : '';
$frecuencia = isset($_GET['frecuencia']) ? $_GET['frecuencia'] : '';

//Comprueba que las variables NO estén vacías
if (!empty($id_cliente)) {
    $query = "  INSERT INTO pedidos_automaticos 
                SET IDCliente = :idc, IDProducto = :idp, Cantidad = :c, UltimoPedido = :up, Frecuencia = :f";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":idc", $id_cliente);
    $stmt->bindParam(":idp", $id_producto);
    $stmt->bindParam(":c", $cantidad);
    $stmt->bindParam(":up", $ultimo_pedido);
    $stmt->bindParam(":f", $frecuencia);
    
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
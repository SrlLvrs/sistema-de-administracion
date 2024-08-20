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
Obtener valores por URL, todos son necesarios
TABLA PEDIDOS_PRODUCTOS = IDPedido, IDProducto, Cantidad, Total
*/
$id_pedido = isset($_GET['id_pedido']) ? $_GET['id_pedido'] : '';
$id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : '';
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';
$total = isset($_GET['total']) ? $_GET['total'] : '';

//Comprueba que las variables NO estén vacías
if (!empty($id_pedido) && !empty($id_producto) /*&& !empty($idsector) && !empty($telefono)*/) {
    $query = "INSERT INTO pedidos_productos 
            SET IDPedido=:pedido, IDProducto=:producto, Cantidad=:c, Total=:t";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":pedido", $id_pedido);
    $stmt->bindParam(":producto", $id_producto);
    $stmt->bindParam(":c", $cantidad);
    $stmt->bindParam(":t", $total);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "El producto " . $id_producto . " fue añadido al detalle."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se puede crear el pedido. Revisa el log."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Falta información. IDPedido y IDProducto son obligatorios."));
}
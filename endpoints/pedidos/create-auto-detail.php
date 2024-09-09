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
$idpa = isset($_GET['idpa']) ? $_GET['idpa'] : '';
$idproducto = isset($_GET['idproducto']) ? $_GET['idproducto'] : '';
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';
$total = isset($_GET['total']) ? $_GET['total'] : '';

//Comprueba que las variables NO estén vacías
if (!empty($idpa)) {
    $query = "  INSERT INTO pedidos_auto_productos
                SET IDPedidoAuto = :idpa, IDProducto = :idp, Cantidad = :c, Total = :t";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":idpa", $idpa);
    $stmt->bindParam(":idp", $idproducto);
    $stmt->bindParam(":c", $cantidad);
    $stmt->bindParam(":t", $total);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "El producto " . $idproducto . " fue añadido al detalle."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se puede crear el pedido. Revisa el log."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Falta información. IDPedidoAutomatico es obligatorio"));
}
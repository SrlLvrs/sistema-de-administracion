<?php
//Componente: CrearPedidoAuto.vue
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
$ultimo_pedido = isset($_GET['ultimo_pedido']) ? $_GET['ultimo_pedido'] : '';
$frecuencia = isset($_GET['frecuencia']) ? $_GET['frecuencia'] : '';
$observacion = isset($_GET['observacion']) ? $_GET['observacion'] : '';

//Comprueba que las variables NO estén vacías
if (!empty($id_cliente)) {
    $query = "  INSERT INTO pedidos_automaticos 
                SET IDCliente = :idc, UltimoPedido = :up, Frecuencia = :f, Observacion = :o";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":idc", $id_cliente);
    $stmt->bindParam(":up", $ultimo_pedido);
    $stmt->bindParam(":f", $frecuencia);
    $stmt->bindParam(":o", $observacion);
    
    if ($stmt->execute()) {
        //Obtener ultimo id
        $last_id = $db->lastInsertId();

        http_response_code(201); // 201 Created
        echo json_encode(array("message" => "Insert successful", "id" => $last_id));        
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se puede crear el pedido. Revisa el log."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Falta información"));
}
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
Obtiene por url el ID de cliente, la fecha de creación, el estado y la observación.
*/
$id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';
$hora_creacion = isset($_GET['hora_creacion']) ? $_GET['hora_creacion'] : '';
$fecha_reparto = isset($_GET['fecha_reparto']) ? $_GET['fecha_reparto'] : '';
$idpa = isset($_GET['idpa']) ? $_GET['idpa'] : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : 'Agendado';
$observacion = isset($_GET['observacion']) ? $_GET['observacion'] : '';

//Comprueba que las variables necesarias NO estén vacías
if (!empty($id_cliente) && !empty($hora_creacion)) {
    $query = "  INSERT INTO pedidos 
                SET IDCliente = :id, HoraCreacion = :hora, FechaEntrega = :fecha, 
                    IDPA = :idpa, Estado = :estado, ObservacionPA = :observacion";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":id", $id_cliente);
    $stmt->bindParam(":hora", $hora_creacion);
    $stmt->bindParam(":fecha", $fecha_reparto);
    $stmt->bindParam(":idpa", $idpa);
    $stmt->bindParam(":estado", $estado);
    $stmt->bindParam(":observacion", $observacion);
    
    if ($stmt->execute()) {
        //Obtener ultimo id
        $last_id = $db->lastInsertId();

        http_response_code(201);
        echo json_encode(array("message" => "Pedido creado", "id" => $last_id));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se puede crear el pedido. Revisa el log."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Falta información"));
}

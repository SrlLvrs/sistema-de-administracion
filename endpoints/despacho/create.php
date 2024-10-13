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
Obtiene por url el ID de repartidor, ID de producto, cantidad y fecha, lo demás se rellena por default.
*/
$idrepartidor = isset($_GET['idrepartidor']) ? $_GET['idrepartidor'] : '';
$idproducto = isset($_GET['idproducto']) ? $_GET['idproducto'] : '';
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

//Comprueba que las variables NO estén vacías
if (!empty($idrepartidor) && !empty($idproducto) && !empty($cantidad) && !empty($fecha)) {
    $query = "INSERT INTO despacho (IDRepartidor, IDProducto, Cantidad, Fecha)
              VALUES (:idrepartidor, :idproducto, :cantidad, :fecha)
              ON DUPLICATE KEY UPDATE Cantidad = :cantidad";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":idrepartidor", $idrepartidor);
    $stmt->bindParam(":idproducto", $idproducto);
    $stmt->bindParam(":cantidad", $cantidad);
    $stmt->bindParam(":fecha", $fecha);
    
    if ($stmt->execute()) {
        $affected_rows = $stmt->rowCount();
        if ($affected_rows == 0) {
            http_response_code(200);
            echo json_encode(array("message" => "No se realizaron cambios. Los datos ya existían."));
        } elseif ($affected_rows == 1) {
            http_response_code(201);
            echo json_encode(array("message" => "Despacho creado exitosamente"));
        } else {
            http_response_code(200);
            echo json_encode(array("message" => "Despacho actualizado exitosamente"));
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo procesar el despacho. Por favor, revise el registro de errores."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Faltan datos requeridos para procesar el despacho"));
}

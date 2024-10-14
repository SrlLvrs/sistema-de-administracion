<?php
// vista: pedidos-aprobar.vue
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Verificar si se recibió el parámetro ID
$id_pedido = isset($_GET['id_pedido']) ? $_GET['id_pedido'] : '';

// Comprueba que la variable NO esté vacía
if (!empty($id_pedido)) {
    $query = "UPDATE pedidos SET Estado = 'Agendado' WHERE ID = :id";

    $stmt = $db->prepare($query);

    // Bindear la variable usando bindParam
    $stmt->bindParam(":id", $id_pedido);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Pedido actualizado a estado Agendado"));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se puede actualizar el pedido. Revisa el log."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Falta el parámetro ID del pedido"));
}

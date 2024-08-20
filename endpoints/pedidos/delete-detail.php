<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
// También permiten el uso del método DELETE y gestionan los encabezados para las solicitudes.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Obtener id de pedido
$id = isset($_GET['id']) ? $_GET['id'] : '';
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';

// Verifica que el 'id' no esté vacío.
if (!empty($id)) {
    // Se prepara la consulta SQL para eliminar el pedido.
    $query = "  DELETE FROM pedidos_productos 
                WHERE IDPedido = :id AND Cantidad = :c
                LIMIT 1";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    // Se vincula el parámetro 'id' con el valor recibido.
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":c", $cantidad);

    // Se ejecuta la consulta.
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        // Se envía una respuesta JSON indicando que el pedido fue eliminado.
        echo json_encode(array("message" => "El producto con ID " . $id . " y cantidad " . $cantidad . " fue eliminado."));
    } else {
        // Si la ejecución falla, se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        // Se envía una respuesta JSON indicando que no se pudo eliminar el pedido
        echo json_encode(array("message" => "No fue posible eliminar el pedido. Revisar error_log."));
    }
} else {
    // Si el 'id' está vacío, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    // Se envía una respuesta JSON indicando que el 'id' está incompleto.
    echo json_encode(array("message" => "Falta el id de pedido."));
}
?>

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

// Obtener el parámetro 'id' de la URL.
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Verifica que el 'id' no esté vacío.
if (!empty($id)) {
    // Se prepara la consulta SQL para eliminar el cliente.
    $query = "UPDATE clientes SET Visible = 0 WHERE ID = :id";
    //$query = "DELETE FROM sector WHERE ID = :id";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    // Se vincula el parámetro 'id' con el valor recibido.
    $stmt->bindParam(":id", $id);

    // Se ejecuta la consulta.
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        // Se envía una respuesta JSON indicando que el cliente fue eliminado.
        echo json_encode(array("message" => "El cliente " . $id . " fue eliminado."));
    } else {
        // Si la ejecución falla, se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        // Se envía una respuesta JSON indicando que no se pudo eliminar el cliente.
        echo json_encode(array("message" => "No se ha podido eliminar el cliente."));
    }
} else {
    // Si el 'id' está vacío, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    // Se envía una respuesta JSON indicando que el 'id' está incompleto.
    echo json_encode(array("message" => "Falta el id."));
}
?>

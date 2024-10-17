<?php
// Agregar estos encabezados al principio del archivo
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar la solicitud OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Obtener los parámetros de la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$nuevaFecha = isset($_GET['nuevaFecha']) ? $_GET['nuevaFecha'] : null;

// Verificar que se han recibido los datos necesarios
if (!empty($id) && !empty($nuevaFecha)) {
    // Preparar la consulta SQL para actualizar el pedido
    $query = "UPDATE pedidos SET FechaEntrega = :nuevaFecha WHERE ID = :id";

    $stmt = $db->prepare($query);

    // Bindear los parámetros
    $stmt->bindParam(":nuevaFecha", $nuevaFecha);
    $stmt->bindParam(":id", $id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "La fecha de reparto fue actualizada", "success" => true));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo actualizar la fecha de reparto", "success" => false));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Faltan datos necesarios para actualizar la fecha de reparto", "success" => false));
}
?>

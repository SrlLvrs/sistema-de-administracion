<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
// También permiten el uso del método PUT y gestionan los encabezados para las solicitudes.
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

// Obtener parámetros de la URL.
$id = isset($_GET['id']) ? $_GET['id'] : '';
$rol = isset($_GET['rol']) ? $_GET['rol'] : '';

// Verifica que el campo 'id' no esté vacío
if (!empty($id)) {
    // Se prepara la consulta SQL para actualizar el usuario.
    $query = "  UPDATE usuarios
                SET Rol = :rol
                WHERE id = :id";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    // Se vinculan los parámetros de la consulta con los valores recibidos.
    if (!empty($id)) {
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":rol", $rol);
    }

    // Se ejecuta la consulta.
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        // Se envía una respuesta JSON indicando que el usuario fue actualizado.
        echo json_encode(array("message" => "El usuario fue actualizado."));
    } else {
        // Si la ejecución falla, se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        // Se envía una respuesta JSON indicando que no se pudo actualizar el usuario.
        echo json_encode(array("message" => "No se pudo actualizar el usuario."));
    }
} else {
    // Si los datos están incompletos, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    // Se envía una respuesta JSON indicando que los datos están incompletos.
    echo json_encode(array("message" => "Los datos son incompletos."));
}
?>

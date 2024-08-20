<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
// También permiten el uso del método POST y gestionan los encabezados para las solicitudes.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Obtener parámetros de la URL.
$name = isset($_GET['name']) ? $_GET['name'] : '';

// Verifica que el campo 'name' no esté vacío
if (!empty($name)) {
    // Se prepara la consulta SQL para actualizar el usuario.
    $query = "  UPDATE usuario 
                SET Nombre = :name
                WHERE Rut = 123456789";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    // Se vinculan los parámetros de la consulta con los valores recibidos.
    if (!empty($name)) {
        $stmt->bindParam(":name", $name);
    }

    // Se ejecuta la consulta.
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        // Se envía una respuesta JSON indicando que el usuario fue actualizado.
        echo json_encode(array("message" => "User was updated."));
    } else {
        // Si la ejecución falla, se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        // Se envía una respuesta JSON indicando que no se pudo actualizar el usuario.
        echo json_encode(array("message" => "Unable to update user."));
    }
} else {
    // Si los datos están incompletos, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    // Se envía una respuesta JSON indicando que los datos están incompletos.
    echo json_encode(array("message" => "Incomplete data."));
}
?>

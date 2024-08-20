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

/*
Obtener los parámetros por url
hay que crear una variable por cada columna
*/
$id = isset($_GET['id']) ? $_GET['id'] : '';
$nombresector = isset($_GET['nombresector']) ? $_GET['nombresector'] : '';
$comuna = isset($_GET['comuna']) ? $_GET['comuna'] : '';
$diareparto = isset($_GET['diareparto']) ? $_GET['diareparto'] : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : ''; // ESTA VARIABLE ES UN INT

// Verifica que los campos no estén vacíos
if (!empty($nombresector) && !empty($comuna) && !empty($diareparto) && !empty($orden)) {
    // Se prepara la consulta SQL para actualizar el sector.
    $query = "  UPDATE sector 
                SET NombreSector = :nombresector, Comuna = :comuna, DiaReparto = :diareparto, Orden = :orden
                WHERE ID = :id";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":nombresector", $nombresector);
    $stmt->bindParam(":comuna", $comuna);
    $stmt->bindParam(":diareparto", $diareparto);
    $stmt->bindParam(":orden", $orden);

    // Se ejecuta la consulta.
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        // Se envía una respuesta JSON indicando que el usuario fue actualizado.
        echo json_encode(array("message" => "El sector fue actualizado"));
    } else {
        // Si la ejecución falla, se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        // Se envía una respuesta JSON indicando que no se pudo actualizar el sector.
        echo json_encode(array("message" => "No se pudo actualizar el sector"));
    }
} else {
    // Si los datos están incompletos, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    // Se envía una respuesta JSON indicando que los datos están incompletos.
    echo json_encode(array("message" => "Falta informacion del sector."));
}
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
//CLIENTES: ID, Nombre, Direccion, IDSector, Telefono, Telefono2, LinkMaps, Frecuencia, Observacion, DiaRepartoExcepcional, Visible
*/
$id = isset($_GET['id']) ? $_GET['id'] : ''; //NOT NULL
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$direccion = isset($_GET['direccion']) ? $_GET['direccion'] : '';
$idsector = isset($_GET['idsector']) ? $_GET['idsector'] : ''; //NOT NULL
$telefono = isset($_GET['telefono']) ? $_GET['telefono'] : '';
$telefono2 = isset($_GET['telefono2']) ? $_GET['telefono2'] : '';
$linkmaps = isset($_GET['linkmaps']) ? $_GET['linkmaps'] : '';
$frecuencia = isset($_GET['frecuencia']) ? $_GET['frecuencia'] : '';
$observacion = isset($_GET['observacion']) ? $_GET['observacion'] : '';
$preferencia = isset($_GET['preferencia']) ? $_GET['preferencia'] : '';
$dre = isset($_GET['dre']) ? $_GET['dre'] : NULL;

// Verifica que los campos no estén vacíos
if (!empty($id)) {
    // Se prepara la consulta SQL para actualizar el cliente.
    $query = "  UPDATE clientes 
                SET Nombre=:n, Direccion=:d, IDSector=:ids, Telefono=:t, Telefono2=:t2, LinkMaps=:l, Frecuencia=:f, Observacion=:o, Preferencia=:p, DiaRepartoExcepcional=:dre 
                WHERE ID = :id";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":n", $nombre);
    $stmt->bindParam(":d", $direccion);
    $stmt->bindParam(":ids", $idsector);
    $stmt->bindParam(":t", $telefono);
    $stmt->bindParam(":t2", $telefono2);
    $stmt->bindParam(":l", $linkmaps);
    $stmt->bindParam(":f", $frecuencia);
    $stmt->bindParam(":o", $observacion);
    $stmt->bindParam(":p", $preferencia);
    $stmt->bindParam(":dre", $dre);

    // Se ejecuta la consulta.
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        // Se envía una respuesta JSON indicando que el cliente fue actualizado.
        echo json_encode(array("message" => "El cliente fue actualizado"));
    } else {
        // Si la ejecución falla, se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        // Se envía una respuesta JSON indicando que no se pudo actualizar el cliente.
        echo json_encode(array("message" => "No se pudo actualizar el cliente."));
    }
} else {
    // Si los datos están incompletos, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    // Se envía una respuesta JSON indicando que los datos están incompletos.
    echo json_encode(array("message" => "Falta informacion del cliente."));
}
?>

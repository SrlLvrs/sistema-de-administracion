<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';
include_once '../inicio/log_functions.php';

$database = new Database();
$db = $database->getConnection();

/*
Obtener los parámetros por url
hay que crear una variable por cada columna
*/
$nombresector = isset($_GET['nombresector']) ? $_GET['nombresector'] : '';
$comuna = isset($_GET['comuna']) ? $_GET['comuna'] : '';
$diareparto = isset($_GET['diareparto']) ? $_GET['diareparto'] : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : ''; // ESTA VARIABLE ES UN INT

//Comprueba que las variables NO estén vacías
if (!empty($nombresector) && !empty($comuna) && !empty($diareparto) && !empty($orden)) {
    $query = "INSERT INTO sector SET NombreSector=:nombresector, Comuna=:comuna, DiaReparto=:diareparto, Orden=:orden";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":nombresector", $nombresector);
    $stmt->bindParam(":comuna", $comuna);
    $stmt->bindParam(":diareparto", $diareparto);
    $stmt->bindParam(":orden", $orden);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "El sector fue creado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No fue posible crear el sector. Revisa el log para más información."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Falta información."));
}
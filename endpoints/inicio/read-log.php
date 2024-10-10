<?php
//VISTA: Inicio.vue
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Información de logs del día
$query = "  SELECT id, changes, DATE_FORMAT(log_time, '%H:%i - %d/%m/%Y') AS log_time
            FROM logs
            WHERE changes IS NOT NULL
            AND DATE(log_time) = CURDATE()
            ORDER BY id DESC";

$stmt = $db->prepare($query);

// Se ejecuta la consulta.
$stmt->execute();

// Array para almacenar resultados
$resultados = array();

// Recorrer todos los registros obtenidos
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultados[] = $row;
}

// Verifica si se encontraron registros
if (count($resultados) > 0) {
    http_response_code(200);
    echo json_encode($resultados);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No se encontraron pedidos"));
    exit();
}
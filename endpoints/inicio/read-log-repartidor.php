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

// Obtener el ID del repartidor de la URL, si no se proporciona, usar 12 como valor predeterminado
$idRepartidor = isset($_GET['idRepartidor']) ? intval($_GET['idRepartidor']) : 12;

// Obtener la fecha de hoy en formato 'YYYY-MM-DD'
$hoy = date('Y-m-d');

// Se prepara la consulta SQL para seleccionar toda la información de pedidos.
$query = "  SELECT id, idrepartidor, changes, DATE_FORMAT(log_time, '%H:%i - %d/%m/%Y') AS log_time
            FROM logs
            WHERE changes IS NOT NULL 
            AND (idrepartidor = 12 OR idrepartidor = :idRepartidor)
            AND DATE(log_time) = :fechaHoy
            ORDER BY id DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':idRepartidor', $idRepartidor, PDO::PARAM_INT);
$stmt->bindParam(':fechaHoy', $hoy, PDO::PARAM_STR);

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
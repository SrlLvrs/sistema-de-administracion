<?php
// VISTA: RESUMEN.VUE
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// GET stock de la semana
$query = "  SELECT r.Fecha, r.IDRepartidor, u.Username, r.IDProducto, p.Descripcion, r.Cantidad, p.Precio, r.Cantidad * p.Precio as TOTAL
            FROM resumen r
            JOIN productos p ON r.IDProducto = p.ID
            JOIN usuarios u ON r.IDRepartidor = u.ID
            WHERE Fecha = CURDATE()";

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
    echo json_encode(array("message" => "No hay stock"));
    exit();
}
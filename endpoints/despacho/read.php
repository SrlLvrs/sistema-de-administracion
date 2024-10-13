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
$query = "  SELECT 
                p.ID AS IDProducto, 
                d.IDRepartidor, 
                u.Username, 
                p.Descripcion, 
                SUM(CASE WHEN DATE(d.Fecha) = CURDATE() THEN d.Cantidad ELSE 0 END) AS CantidadHoy,
                SUM(CASE WHEN DATE(d.Fecha) = CURDATE() - INTERVAL 1 DAY THEN d.Cantidad ELSE 0 END) AS CantidadAyer
            FROM productos p
            JOIN despacho d ON d.IDProducto = p.ID
            JOIN usuarios u ON u.ID = d.IDRepartidor
            GROUP BY p.ID, d.IDRepartidor, u.Username, p.Descripcion";

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

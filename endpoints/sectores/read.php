<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Se prepara la consulta SQL para seleccionar todos los registros de la tabla 'sector'.
$query = "  SELECT s.*, u.Username as Repartidor
            FROM sector s
            LEFT JOIN usuarios u ON s.IDRepartidor = u.ID
            WHERE s.Visible = 1
            ORDER BY s.ID DESC";
$stmt = $db->prepare($query);

// Se ejecuta la consulta.
$stmt->execute();

// Se obtiene el número de filas devueltas.
$num = $stmt->rowCount();

// Se verifica si se encontraron registros.
if ($num > 0) {
    // Se crea un array para almacenar los sectores.
    $sectores_arr = array();

    // Se recorren los resultados de la consulta.
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Se agrega el sector actual al array de sectores.
        array_push($sectores_arr, $row);
    }

    // Se establece el código de respuesta a 200 OK.
    http_response_code(200);

    // Se envía la respuesta en formato JSON.
    echo json_encode($sectores_arr);
} else {
    // Si no se encontraron registros, se establece el código de respuesta a 404 Not Found.
    http_response_code(404);

    // Se envía un mensaje indicando que no se encontraron sectores.
    echo json_encode(array("message" => "No se encontraron sectores."));
}
?>

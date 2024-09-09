<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Se prepara la consulta SQL para seleccionar todos los registros de la tabla 'pedidos'.
$query = "  SELECT pa.ID, pa.IDCliente, c.Nombre, c.Direccion, s.NombreSector, s.Comuna, pa.IDProducto, p.Descripcion AS Producto, pa.Cantidad, pa.UltimoPedido, pa.Frecuencia
            FROM pedidos_automaticos pa
            JOIN clientes c ON pa.IDCliente = c.ID
            JOIN sector s ON c.IDSector = s.ID
            JOIN productos p ON pa.IDProducto = p.ID
            ORDER BY pa.ID DESC";

$stmt = $db->prepare($query);

// Se ejecuta la consulta.
$stmt->execute();

// Se obtiene el número de filas devueltas.
$num = $stmt->rowCount();

// Se verifica si se encontraron registros.
if ($num > 0) {
    // Array para almacenar los resultados
    $resultados = array();

    // Recorrer todos los registros obtenidos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $row;
    }

    // Se establece el código de respuesta a 200 OK.
    http_response_code(200);

    // Se envía la respuesta en formato JSON.
    echo json_encode($resultados);
} else {
    // Si no se encontraron registros, se establece el código de respuesta a 404 Not Found.
    http_response_code(404);

    // Se envía un mensaje indicando que no se encontraron pedidos.
    echo json_encode(array("message" => "No se encontraron pedidos."));
}
?>

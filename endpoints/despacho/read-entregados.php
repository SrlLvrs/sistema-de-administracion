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
                s.IDRepartidor,
                pp.IDProducto,
                prod.Descripcion AS NombreProducto,
                SUM(CASE 
                    WHEN p.Estado = 'entregado' THEN pp.Cantidad
                    ELSE 0
                END) AS cantidadEntregada
            FROM pedidos_productos pp
            JOIN pedidos p ON pp.IDPedido = p.ID
            JOIN clientes c ON p.IDCliente = c.ID
            JOIN sector s ON c.IDSector = s.ID
            JOIN productos prod ON pp.IDProducto = prod.ID
            GROUP BY s.IDRepartidor, pp.IDProducto, prod.Descripcion
            ORDER BY s.IDRepartidor, pp.IDProducto";

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

<?php
// VISTA: Inicio.vue
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Se prepara la consulta SQL para seleccionar toda la información de pedidos.
$query = "  SELECT s.IDProducto,
                pro.Descripcion,
                FLOOR(SUM(s.Stock) / 30) AS TotalStockDividido,
                COALESCE(
                            (SELECT SUM(pp.Cantidad)
                            FROM pedidos_productos pp
                            JOIN pedidos p ON pp.IDPedido = p.ID
                            WHERE pp.IDProducto = s.IDProducto
                                AND WEEK(p.FechaEntrega, 1) = WEEK(CURDATE(), 1)
                                AND YEAR(p.FechaEntrega) = YEAR(CURDATE()) ), 0) AS StockNecesario
            FROM stock s
            JOIN productos pro ON pro.ID = s.IDProducto
            WHERE WEEK(s.Fecha, 1) = WEEK(CURDATE(), 1) -- Filtrar stock de la semana actual
            AND YEAR(s.Fecha) = YEAR(CURDATE()) -- Asegurarse de que sea el mismo año
            GROUP BY s.IDProducto";

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
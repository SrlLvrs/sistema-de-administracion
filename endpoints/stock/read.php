<?php
// VISTA: PRODUCCION.VUE
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// GET stock de la semana y entregados
$query = "  SELECT 
                p.ID AS idproducto,
                CONCAT('[', 
                    IFNULL(MAX(CASE WHEN DAYOFWEEK(s.Fecha) = 2 THEN s.Stock END), 0), ',',  -- Lunes
                    IFNULL(MAX(CASE WHEN DAYOFWEEK(s.Fecha) = 3 THEN s.Stock END), 0), ',',  -- Martes
                    IFNULL(MAX(CASE WHEN DAYOFWEEK(s.Fecha) = 4 THEN s.Stock END), 0), ',',  -- Miércoles
                    IFNULL(MAX(CASE WHEN DAYOFWEEK(s.Fecha) = 5 THEN s.Stock END), 0), ',',  -- Jueves
                    IFNULL(MAX(CASE WHEN DAYOFWEEK(s.Fecha) = 6 THEN s.Stock END), 0), ',',  -- Viernes
                    IFNULL(MAX(CASE WHEN DAYOFWEEK(s.Fecha) = 7 THEN s.Stock END), 0), ',',  -- Sábado
                    IFNULL(MAX(CASE WHEN DAYOFWEEK(s.Fecha) = 1 THEN s.Stock END), 0)        -- Domingo
                , ']') AS stock,
                IFNULL((SELECT SUM(pp2.cantidad)
                        FROM pedidos_productos pp2
                        JOIN pedidos ped2 ON pp2.IDPedido = ped2.ID
                        WHERE pp2.IDProducto = p.ID
                        AND YEARWEEK(ped2.FechaEntrega, 1) = YEARWEEK(CURDATE(), 1)
                        AND ped2.Estado = 'Entregado'), 0) AS entregados
            FROM productos p
            LEFT JOIN stock s ON p.ID = s.IDProducto AND YEARWEEK(s.Fecha, 1) = YEARWEEK(CURDATE(), 1)
            GROUP BY p.ID";

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

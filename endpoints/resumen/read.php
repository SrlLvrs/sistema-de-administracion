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
                u.Username AS Repartidor,                   -- Nombre del repartidor
                s.IDRepartidor AS IDRepartidor,             -- ID del repartidor (ahora desde la tabla sector)
                prod.Descripcion AS Producto,               -- Descripción del producto
                prod.Precio AS Precio,                      -- Precio del producto
                SUM(pp.Cantidad) AS TotalCantidad,          -- Cantidad total de productos
                SUM(CASE WHEN p.Estado = 'Entregado' THEN pp.Cantidad ELSE 0 END) AS CantidadEntregada,  -- Cantidad de productos entregados
                (SUM(pp.Cantidad) - SUM(CASE WHEN p.Estado = 'Entregado' THEN pp.Cantidad ELSE 0 END)) AS TotalRestante,  -- Total restante por entregar
                (SUM(pp.Cantidad) * prod.Precio) AS Total   -- Total (Cantidad * Precio)
            FROM 
                pedidos p
            JOIN 
                clientes c ON p.IDCliente = c.ID            -- Unir con la tabla clientes
            JOIN 
                sector s ON c.IDSector = s.ID               -- Unir con la tabla sector
            JOIN 
                usuarios u ON s.IDRepartidor = u.ID         -- Unir con la tabla usuarios para obtener el nombre del repartidor
            JOIN 
                pedidos_productos pp ON p.ID = pp.IDPedido  -- Unir con pedidos_productos
            JOIN 
                productos prod ON pp.IDProducto = prod.ID   -- Unir con productos para obtener el precio
            WHERE 
                DATE(p.FechaEntrega) = CURDATE()            -- Filtrar solo los pedidos de hoy
            GROUP BY 
                u.Username, s.IDRepartidor, prod.Descripcion, prod.Precio
            ORDER BY 
                u.Username, prod.Descripcion";

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

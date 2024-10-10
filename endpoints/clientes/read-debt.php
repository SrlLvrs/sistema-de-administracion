<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Se prepara la consulta SQL para seleccionar todos los clientes con deuda.

$query = "  SELECT
                c.ID,
                c.Nombre,
                c.Direccion,
                c.IDSector,
                s.NombreSector,
                s.Comuna,
                c.Telefono,
                c.Telefono2,
                c.LinkMaps,
                c.Observacion,
                COALESCE(
                    c.DiaRepartoExcepcional, s.DiaReparto
                ) AS diaDeReparto,
                SUM(pro.Total) AS Deuda_Total
            FROM
                clientes c
                JOIN sector s ON c.IDSector = s.ID
                JOIN pedidos p ON c.ID = p.IDCliente
                JOIN pedidos_productos pro ON p.ID = pro.IDPedido
            WHERE
                p.Pagado = 'No'
                AND p.Estado = 'Entregado'
            GROUP BY
                c.ID
            ORDER BY
                Deuda_Total DESC";

$stmt = $db->prepare($query);

// Se ejecuta la consulta.
$stmt->execute();

// Se obtiene el número de filas devueltas.
$num = $stmt->rowCount();

// Se verifica si se encontraron registros.
if ($num > 0) {
    // Se crea un array para almacenar los clientes.
    $clientes_arr = array();

    // Se recorren los resultados de la consulta.
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Se agrega el cliente actual al array de clientes.
        $clientes_arr[] = $row;
    }

    // Se establece el código de respuesta a 200 OK.
    http_response_code(200);

    // Se envía la respuesta en formato JSON.
    echo json_encode($clientes_arr);
} else {
    // Si no se encontraron registros, se establece el código de respuesta a 404 Not Found.
    http_response_code(404);

    // Se envía un mensaje indicando que no se encontraron clientes.
    echo json_encode(array("message" => "No se encontraron clientes."));
}
<?php
//VISTA: OrdenarPedidos.vue
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Obtener el parámetro 'id' de la URL.
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Verifica que el 'id' no esté vacío.
if (empty($id)) {
    http_response_code(400);
    echo json_encode(array("mensaje" => "El parámetro 'id' es requerido."));
    exit;
}

// Se prepara la consulta SQL para seleccionar toda la información de pedidos.
$query = "  SELECT
                p.ID,
                p.IDPA,
                p.IDCliente,
                c.Nombre,
                c.Direccion,
                s.NombreSector,
                s.Comuna,
                c.Telefono,
                c.Telefono2,
                c.LinkMaps,
                s.IDRepartidor,
                p.Pagado,
                DATE_FORMAT(p.FechaEntrega, '%d/%m/%Y') AS FechaEntrega,
                DATE_FORMAT(
                    p.HoraCreacion, '%d/%m/%Y %H:%i'
                ) AS HoraCreacion,
                DATE_FORMAT(p.HoraCierre, '%d/%m/%Y %H:%i') AS HoraCierre
            FROM
                pedidos p
                JOIN clientes c ON p.IDCliente = c.ID
                JOIN sector s ON c.IDSector = s.ID
            WHERE
                p.Visible = 1
                AND YEARWEEK(p.FechaEntrega, 1) = YEARWEEK(CURDATE(), 1)
                AND s.IDRepartidor = :id
                AND p.Estado = 'Agendado'
            ORDER BY
                p.FechaEntrega ASC, p.OrdenEntrega ASC";

$stmt = $db->prepare($query);

// Se ejecuta la consulta.
$stmt->bindParam(':id', $id);
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

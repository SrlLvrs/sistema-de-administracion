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
// PEDIDOS: ID, IDCliente, IDRepartidor, Estado, Pagado, MedioPago, FechaEntrega HoraCreacion, HoraCierre, Visible
// CLIENTES: ID, Nombre, Direccion, IDSector, Telefono, Telefono2, LinkMaps, Frecuencia, DiaRepartoExcepcional, Preferencia, Observacion, Visible

$query = "  SELECT p.ID, p.IDCliente, c.Nombre, c.Direccion, s.NombreSector, s.Comuna, COALESCE (c.DiaRepartoExcepcional, s.DiaReparto) AS DiaReparto, p.IDRepartidor, p.FechaEntrega
            FROM pedidos p
            JOIN clientes c ON p.IDCliente = c.ID
            JOIN sector s ON c.IDSector = s.ID
            WHERE p.Visible = 1
            ORDER BY p.ID DESC
            LIMIT 1";

$stmt = $db->prepare($query);

// Se ejecuta la consulta.
$stmt->execute();

// Se obtiene el número de filas devueltas.
$num = $stmt->rowCount();

// Se verifica si se encontraron registros.
if ($num > 0) {
    // Se crea un array para almacenar los pedidos.
    $pedidos_arr = array();

    // Se recorren los resultados de la consulta.
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extrae los valores de la fila actual.
        extract($row);

        // Se crea un array para el pedido actual
        $pedido_item = array(
            "id" => $row["ID"],
            "id_cliente" => $row["IDCliente"],
            "cliente" => $row["Nombre"],
            "direccion" => $row["Direccion"],
            "sector" => $row["NombreSector"],
            "comuna" => $row["Comuna"],
            "dia_reparto" => $row["DiaReparto"],
            "id_repartidor" => $row["IDRepartidor"],
            "fecha_entrega" => $row["FechaEntrega"],
        );

        // Se agrega el pedido actual al array de pedidos.
        array_push($pedidos_arr, $pedido_item);
    }

    // Se establece el código de respuesta a 200 OK.
    http_response_code(200);

    // Se envía la respuesta en formato JSON.
    echo json_encode($pedidos_arr);
} else {
    // Si no se encontraron registros, se establece el código de respuesta a 404 Not Found.
    http_response_code(404);

    // Se envía un mensaje indicando que no se encontraron pedidos.
    echo json_encode(array("message" => "No se encontraron pedidos."));
}
?>

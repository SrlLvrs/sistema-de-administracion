<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
// También permiten el uso del método PUT y gestionan los encabezados para las solicitudes.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';
include_once '../inicio/log_functions.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

/*
Obtiene la id del pedido por URL y lo marca como PAGADO
*/
$id = isset($_GET['id']) ? $_GET['id'] : ''; //NOT NULL
$user = isset($_GET['user']) ? $_GET['user'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Verifica que los campos no estén vacíos
if (!empty($id)) {
    // Se prepara la consulta SQL para actualizar el cliente.
    $query = "  UPDATE pedidos p SET p.Pagado = 'Si', p.MedioPago = 'Transferencia'
                WHERE p.ID = :id";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":id", $id);

    // Se ejecuta la consulta.
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        // Se envía una respuesta JSON indicando que el pedido fue actualizado.
        echo json_encode(array("message" => "El pedido fue marcado como pagado"));
        logChange($id, "$user marcó el pedido $id de $msg como Pagado con Transferencia");
    } else {
        // Si la ejecución falla, se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        // Se envía una respuesta JSON indicando que no se pudo actualizar el pedido.
        echo json_encode(array("message" => "No se pudo actualizar el pedido."));
    }
} else {
    // Si los datos están incompletos, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    // Se envía una respuesta JSON indicando que los datos están incompletos.
    echo json_encode(array("message" => "Falta informacion del pedido."));
}
?>

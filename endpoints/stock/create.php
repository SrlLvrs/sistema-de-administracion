<?php
//VISTA: Produccion.vue
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
// También permiten el uso del método PUT y gestionan los encabezados para las solicitudes.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

/*
Parámetros por url: id de producto, fecha, stock
*/
$idp = isset($_GET['idp']) ? $_GET['idp'] : ''; //NOT NULL
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$stock = isset($_GET['stock']) ? $_GET['stock'] : '';

// Verifica que los campos no estén vacíos
if (!empty($idp)) {
    // Se prepara la consulta SQL para actualizar el cliente.
    $query = "  INSERT INTO stock
                SET IDProducto = :idp, Fecha = :fecha, Stock = :stock";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":idp", $idp);
    $stmt->bindParam(":fecha", $fecha);
    $stmt->bindParam(":stock", $stock);

    // Se ejecuta la consulta.
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        // Se envía una respuesta JSON indicando que el producto fue creado.
        echo json_encode(array("message" => "El producto fue creado"));
    } else {
        // Si la ejecución falla, se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        // Se envía una respuesta JSON indicando que No se pudo crear el producto.
        echo json_encode(array("message" => "No se pudo crear el producto."));
    }
} else {
    // Si los datos están incompletos, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    // Se envía una respuesta JSON indicando que los datos están incompletos.
    echo json_encode(array("message" => "Falta informacion del producto."));
}
?>

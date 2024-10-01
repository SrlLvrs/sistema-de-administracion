<?php
//COMPONENTE: DespacharRepartidor.vue
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
// También permiten el uso del método POST y gestionan los encabezados para las solicitudes.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

/*
Parámetros por url: id de producto, id de repartidor, fecha, cantidad
*/
$idp = isset($_GET['idp']) ? $_GET['idp'] : ''; //NOT NULL
$idr = isset($_GET['idr']) ? $_GET['idr'] : ''; //NOT NULL
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';

// Verifica que los campos no estén vacíos
if (!empty($idp)) {
    // Se prepara la consulta SQL para actualizar el cliente.
    $query = "  INSERT INTO resumen
                SET IDProducto = :idp, IDRepartidor = :idr, Fecha = :fecha, Cantidad = :cantidad";

    // Se prepara la consulta para su ejecución.
    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":idp", $idp);
    $stmt->bindParam(":idr", $idr);
    $stmt->bindParam(":fecha", $fecha);
    $stmt->bindParam(":cantidad", $cantidad);

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

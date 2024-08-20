<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Se prepara la consulta SQL para seleccionar todos los registros de la tabla 'productos'.
// ID, Descripcion, Precio

$query = "SELECT ID, Descripcion, Precio
        FROM productos
        WHERE Visible = 1
        ORDER BY ID DESC";

$stmt = $db->prepare($query);

// Se ejecuta la consulta.
$stmt->execute();

// Se obtiene el número de filas devueltas.
$num = $stmt->rowCount();

// Se verifica si se encontraron registros.
if ($num > 0) {
    // Se crea un array para almacenar los productos.
    $productos_arr = array();

    // Se recorren los resultados de la consulta.
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extrae los valores de la fila actual.
        extract($row);

        // Se crea un array para el producto actual
        $producto_item = array(
            "id" => $row["ID"],
            "descripcion" => $row["Descripcion"],
            "precio" => $row["Precio"],
        );

        // Se agrega el producto actual al array de productos.
        array_push($productos_arr, $producto_item);
    }

    // Se establece el código de respuesta a 200 OK.
    http_response_code(200);

    // Se envía la respuesta en formato JSON.
    echo json_encode($productos_arr);
} else {
    // Si no se encontraron registros, se establece el código de respuesta a 404 Not Found.
    http_response_code(404);

    // Se envía un mensaje indicando que no se encontraron productos.
    echo json_encode(array("message" => "No se encontraron productos."));
}
?>

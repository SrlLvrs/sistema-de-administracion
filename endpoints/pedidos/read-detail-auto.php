<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

//Parámetros por URL: id de pedido
$id = isset($_GET['id']) ? $_GET['id'] : ''; // NOT NULL

// Verifica si el ID no está vacío
if (!empty($id)) {
    // Se prepara la consulta SQL para seleccionar el registro correspondiente al ID proporcionado.
    $query = "  SELECT pa.ID, c.Nombre, c.Direccion, s.NombreSector, s.Comuna, pap.Cantidad, p.Descripcion, pap.Total
                FROM pedidos_automaticos pa
                JOIN pedidos_auto_productos pap ON pa.ID = pap.IDPedidoAuto
                JOIN productos p ON pap.IDProducto = p.ID
                JOIN clientes c ON pa.IDCliente = c.ID
                JOIN sector s ON c.IDSector = s.ID
                WHERE pa.ID = :id";
                
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Array para almacenar los resultados
    $resultados = array();

    // Recorrer todos los registros obtenidos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $row;
    }

    // Verifica si se encontraron registros
    if(count($resultados) > 0) {
        echo json_encode($resultados);
    } else {
        echo json_encode(array("message" => "No se encontró ningún registro con el ID proporcionado."));
    }
} else {
    // Manejo de error: id no proporcionado
    echo json_encode(array("message" => "ID no proporcionado."));
    exit();
}
?>

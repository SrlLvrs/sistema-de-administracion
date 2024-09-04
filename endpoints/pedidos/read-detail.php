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
    $query = "  SELECT pp.ID AS IDPedidoProducto, p.ID AS IDPedido, c.ID AS IDCliente, 
                c.Nombre, pp.IDProducto, pro.Precio, pro.Descripcion, pp.Cantidad, pp.Total, 
                p.Estado, p.Pagado, p.MedioPago, p.FechaEntrega, p.HoraCreacion, p.HoraCierre,
                c.Direccion, s.NombreSector, s.Comuna, COALESCE (c.DiaRepartoExcepcional, s.DiaReparto) AS Dia_Reparto
                FROM pedidos p
                JOIN clientes c ON c.ID = p.IDCliente
                JOIN pedidos_productos pp ON pp.IDPedido = p.ID
                JOIN productos pro ON pro.ID = pp.IDProducto
                JOIN sector s ON s.ID = c.IDSector
                WHERE p.ID = :id";
                
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

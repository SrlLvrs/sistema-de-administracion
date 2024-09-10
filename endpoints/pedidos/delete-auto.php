<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
// También permiten el uso del método DELETE y gestionan los encabezados para las solicitudes.
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

// Obtener el parámetro 'id' de la URL.
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Verifica que el 'id' no esté vacío.
if (!empty($id)) {
    try {
        // Iniciar una transacción
        $db->beginTransaction();

        // Primera consulta: actualizar 'pedidos_automaticos'
        $query1 = "UPDATE pedidos_automaticos SET Visible = 0 WHERE ID = :id";
        $stmt1 = $db->prepare($query1);
        $stmt1->bindParam(":id", $id);
        $stmt1->execute();

        // Segunda consulta: actualizar 'pedidos' con Estado 'Agendado'
        $query2 = "UPDATE pedidos SET Visible = 0 WHERE IDPA = :id AND Estado = 'Agendado'";
        $stmt2 = $db->prepare($query2);
        $stmt2->bindParam(":id", $id);
        $stmt2->execute();

        // 3. GET fecha ulimo pedido entregado
        $query3 = " SELECT FechaEntrega
                    FROM pedidos
                    WHERE idpa = :id AND Estado = 'Entregado'
                    ORDER BY FechaEntrega DESC 
                    LIMIT 1";
        $stmt3 = $db->prepare($query3);
        $stmt3->bindParam(":id", $id);
        $stmt3->execute();

        // Almacenar el valor de la fecha en una variable
        $resultado = $stmt3->fetch(PDO::FETCH_ASSOC);
        $fecha = $resultado['FechaEntrega'];  // Aquí se almacena la fecha

        // 4. Actualizar UltimoPedido
        $query4 = "UPDATE pedidos_automaticos SET UltimoPedido = :fecha WHERE ID = :id";
        $stmt4 = $db->prepare($query4);
        $stmt4->bindParam(":fecha", $fecha);
        $stmt4->bindParam(":id", $id);
        $stmt4->execute();

        // Confirmar la transacción
        $db->commit();

        // Si ambas ejecuciones son exitosas, se establece el código de respuesta a 200 OK.
        http_response_code(200);
        echo json_encode(array("message" => "Las actualizaciones fueron exitosas para el ID " . $id));
    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        $db->rollBack();

        // Se establece el código de respuesta a 503 Service Unavailable.
        http_response_code(503);
        echo json_encode(array("message" => "Error al realizar las actualizaciones. Revisa el log para más información.", "error" => $e->getMessage()));
    }
} else {
    // Si el 'id' está vacío, se establece el código de respuesta a 400 Bad Request.
    http_response_code(400);
    echo json_encode(array("message" => "Falta el id de pedido automatico."));
}
?>

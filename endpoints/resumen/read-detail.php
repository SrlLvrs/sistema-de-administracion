<?php
// VISTA: Resumen.vue
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

//Parámetros por URL: id de repartidor
$idr = isset($_GET['idr']) ? $_GET['idr'] : ''; // NOT NULL

try {
    // Iniciar una transacción
    $db->beginTransaction();

    // 1. Total de dinero recibido
    $query = "  SELECT 
                    'Total de dinero recibido' AS Nombre, SUM(pp.Total) AS sumaTotal
                FROM 
                    pedidos p
                JOIN 
                    pedidos_productos pp ON pp.IDPedido = p.ID
                WHERE 
                    DATE(p.FechaEntrega) = CURDATE() 
                    AND p.Estado = 'Entregado' 
                    AND p.Pagado = 'Si' 
                    AND p.IDRepartidor = :idr";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':idr', $idr);
    $stmt->execute();

    // Verificar si la primera consulta se ejecutó correctamente
    if ($stmt->errorCode() !== '00000') {
        throw new PDOException($stmt->errorInfo()[2]);
    }

    // Almacenar el valor de la primera consulta en una variable
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $result['sumaTotal'];
    $nombre = $result['Nombre'];

    /************************************************************* */

    // 2. Total dinero recibido en efectivo
    $query2 = " SELECT
                    'Total de dinero recibido en efectivo' AS Nombre, SUM(pp.Total) AS totalEfectivo
                FROM
                    pedidos p
                    JOIN pedidos_productos pp ON pp.IDPedido = p.ID
                WHERE
                    DATE(p.FechaEntrega) = CURDATE()
                    AND p.Estado = 'Entregado'
                    AND p.Pagado = 'Si'
                    AND p.MedioPago = 'Efectivo'
                    AND p.IDRepartidor = :idr";
    $stmt2 = $db->prepare($query2);
    $stmt2->bindParam(':idr', $idr);
    $stmt2->execute();

    // Verificar si la segunda consulta se ejecutó correctamente
    if ($stmt2->errorCode() !== '00000') {
        throw new PDOException($stmt2->errorInfo()[2]);
    }

    // Almacenar el valor de la segunda consulta en una variable
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $total2 = $result2['totalEfectivo'];
    $nombre2 = $result2['Nombre'];

    /************************************************************* */

    // 3. Total de dinero recibido por transferencia
    $query3 = " SELECT
                    'Total de dinero recibido por transferencia' AS Nombre, SUM(pp.Total) AS totalTransferencia
                FROM
                    pedidos p
                    JOIN pedidos_productos pp ON pp.IDPedido = p.ID
                WHERE
                    DATE(p.FechaEntrega) = CURDATE()
                    AND p.Estado = 'Entregado'
                    AND p.Pagado = 'Si'
                    AND p.MedioPago = 'Transferencia'
                    AND p.IDRepartidor = :idr";
    $stmt3 = $db->prepare($query3);
    $stmt3->bindParam(':idr', $idr);
    $stmt3->execute();

    // Verificar si la tercera consulta se ejecutó correctamente
    if ($stmt3->errorCode() !== '00000') {
        throw new PDOException($stmt3->errorInfo()[2]);
    }

    // Almacenar el valor de la tercera consulta en una variable
    $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    $total3 = $result3['totalTransferencia'];
    $nombre3 = $result3['Nombre'];

    /************************************************************* */


    // Crear un array de objetos
    $data = array(
        array(
            "Nombre" => $nombre,
            "Valor" => $total
        ),
        array(
            "Nombre" => $nombre2,
            "Valor" => $total2
        ),
        array(
            "Nombre" => $nombre3,
            "Valor" => $total3
        )
    );

    // Imprimir el array de objetos en formato JSON
    echo json_encode($data);
} catch (PDOException $e) {
    // Si ocurre un error, revertir la transacción
    $db->rollBack();

    // Se establece el código de respuesta a 503 Service Unavailable
    http_response_code(503);

    // Se imprime un mensaje de error en formato JSON
    echo json_encode(array("message" => "Error: " . $e->getMessage()), JSON_PRETTY_PRINT);
    exit();
}

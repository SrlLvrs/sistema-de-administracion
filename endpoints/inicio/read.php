<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

try {
    // Iniciar una transacción
    $db->beginTransaction();

    // 1. Obtener total de clientes
    $query = "SELECT 'Total Clientes' AS Nombre, COUNT(c.ID) AS Clientes_Totales
FROM clientes c
WHERE c.Visible = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();

    // Verificar si la primera consulta se ejecutó correctamente
    if ($stmt->errorCode() !== '00000') {
        throw new PDOException($stmt->errorInfo()[2]);
    }

    // Almacenar el valor de la primera consulta en una variable
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $result['Clientes_Totales'];
    $nombre = $result['Nombre'];

    // 2. Obtener total de clientes deudores
    $query2 = "SELECT 'Clientes Deudores' AS Nombre, COUNT(c.ID) AS Clientes_Deudores
 FROM clientes c
 WHERE c.Visible = 1
 AND EXISTS (SELECT 1
             FROM pedidos
             WHERE IDCliente = c.ID
             AND Pagado = 'No'
             AND Estado = 'Entregado')";
    $stmt2 = $db->prepare($query2);
    $stmt2->execute();

    // Verificar si la segunda consulta se ejecutó correctamente
    if ($stmt2->errorCode() !== '00000') {
        throw new PDOException($stmt2->errorInfo()[2]);
    }

    // Almacenar el valor de la segunda consulta en una variable
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $total2 = $result2['Clientes_Deudores'];
    $nombre2 = $result2['Nombre'];

    // 3. Obtiene clientes con suscripción
    $query3 = "SELECT 'Clientes Suscripcion' AS Nombre, COUNT(c.ID) AS Clientes_Suscripcion
 FROM clientes c
 WHERE c.ID IN ( SELECT pa.IDCliente 
                 FROM pedidos_automaticos pa 
                 WHERE pa.Visible = 1)
 AND c.Visible = 1";
    $stmt3 = $db->prepare($query3);
    $stmt3->execute();

    // Verificar si la tercera consulta se ejecutó correctamente
    if ($stmt3->errorCode() !== '00000') {
        throw new PDOException($stmt3->errorInfo()[2]);
    }

    // Almacenar el valor de la tercera consulta en una variable
    $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    $total3 = $result3['Clientes_Suscripcion'];
    $nombre3 = $result3['Nombre'];

    // 4. Obtiene clientes libres
    $query4 = "SELECT 'Clientes No Suscripcion' AS Nombre, COUNT(DISTINCT c.ID) AS Clientes_No_Suscripcion
 FROM clientes c
 WHERE c.ID NOT IN ( SELECT pa.IDCliente 
                     FROM pedidos_automaticos pa 
                     WHERE pa.Visible = 1)
 AND c.Visible = 1";
    $stmt4 = $db->prepare($query4);
    $stmt4->execute();

    // Verificar si la cuarta consulta se ejecutó correctamente
    if ($stmt4->errorCode() !== '00000') {
        throw new PDOException($stmt4->errorInfo()[2]);
    }

    // Almacenar el valor de la cuarta consulta en una variable
    $result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
    $total4 = $result4['Clientes_No_Suscripcion'];
    $nombre4 = $result4['Nombre'];

    // 5. Obtiene cantidad de pedidos para hoy
    $query5 = "SELECT 'Pedidos agendados para hoy' AS Nombre, COUNT(*) AS Pedidos_agendados_hoy
FROM pedidos p
WHERE DATE(p.FechaEntrega) = CURDATE()";
    $stmt5 = $db->prepare($query5);
    $stmt5->execute();

    // Verificar si la quinta consulta se ejecutó correctamente
    if ($stmt5->errorCode() !== '00000') {
        throw new PDOException($stmt5->errorInfo()[2]);
    }

    // Almacenar el valor de la quinta consulta en una variable
    $result5 = $stmt5->fetch(PDO::FETCH_ASSOC);
    $total5 = $result5['Pedidos_agendados_hoy'];
    $nombre5 = $result5['Nombre'];


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
        ),
        array(
            "Nombre" => $nombre4,
            "Valor" => $total4
        ),
        array(
            "Nombre" => $nombre5,
            "Valor" => $total5
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

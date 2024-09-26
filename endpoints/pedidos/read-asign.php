<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

    // Consulta para obtener sectores donde se reparte hoy.
    $query = "  SELECT s.ID AS IDSector, s.NombreSector, s.Comuna, p.IDRepartidor, p.FechaEntrega, u.Username AS Repartidor
                FROM sector s
                JOIN clientes c ON c.IDSector = s.ID
                JOIN pedidos p ON p.IDCliente = c.ID
                JOIN usuarios u ON p.IDRepartidor = u.ID
                WHERE DATE(p.FechaEntrega) = CURDATE()
                GROUP BY s.ID";
    $stmt = $db->prepare($query);

    $stmt->execute();

    // Array para almacenar los resultados
    $resultados = array();

    // Recorrer todos los registros obtenidos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $row;
    }

    // Verifica si se encontraron registros
    if (count($resultados) > 0) {
        echo json_encode($resultados);
    } else {
        echo json_encode(array("message" => "No se encontraron usuarios."));
    }

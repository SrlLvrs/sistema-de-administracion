<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

//Parámetros por URL: username
$username = isset($_GET['username']) ? $_GET['username'] : '';

// Verifica si el username no está vacío
if (!empty($username)) {
    // Consulta para obtener el hash.
    $query = "  SELECT pass AS Hashed
                FROM usuarios
                WHERE username = :username";
    $stmt = $db->prepare($query);

    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
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
        echo json_encode(array("message" => "No se encontró ningún registro con el ID proporcionado."));
    }
} else {
    // Manejo de error: id no proporcionado
    echo json_encode(array("message" => "ID no proporcionado."));
    exit();
}

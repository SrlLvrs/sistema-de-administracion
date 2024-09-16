<?php
// log_functions.php

require_once '../config/db.php';

function logChange($action, $table, $recordId, $changes) {
    // Conectar a la base de datos
    include_once '../config/db.php';
    $database = new Database();
    $conn = $database->getConnection();

    // Preparar el log
    $stmt = $conn->prepare("INSERT INTO logs (action, table_name, record_id, changes) VALUES (:action, :table, :recordId, :changes)");

    // Vincular parámetros a la consulta
    $stmt->bindParam(':action', $action);
    $stmt->bindParam(':table', $table);
    $stmt->bindParam(':recordId', $recordId);
    $stmt->bindParam(':changes', $changes);

    // Ejecutar el query
    try {
        $stmt->execute();
        // Aquí puedes agregar código para manejar el éxito de la operación
    } catch (Exception $e) {
        echo 'Error al insertar el log: ' . $e->getMessage();
        return;
    }
}
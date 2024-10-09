<?php
// log_functions.php

require_once '../config/db.php';

function logChange($idpedido, $changes, $idrepartidor) {
    $database = new Database();
    $conn = $database->getConnection();

    // Preparar el log
    $stmt = $conn->prepare("INSERT INTO logs (idpedido, changes, idrepartidor) VALUES (:idpedido, :changes, :idrepartidor)");

    // Vincular parámetros a la consulta
    $stmt->bindParam(':idpedido', $idpedido);
    $stmt->bindParam(':changes', $changes);
    $stmt->bindParam(':idrepartidor', $idrepartidor);

    // Ejecutar el query
    try {
        $stmt->execute();
        // Aquí puedes agregar código para manejar el éxito de la operación
    } catch (Exception $e) {
        echo 'Error al insertar el log: ' . $e->getMessage();
        return;
    }
}
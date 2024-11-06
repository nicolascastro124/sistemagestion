<?php

require '../vendor/autoload.php'; 

use App\DatabaseConnection; 

// Probar la conexión
try {
    $connection = DatabaseConnection::getConnection();
    echo "Conexión exitosa a la base de datos";

} catch (\Exception $e) {
    echo ("Error en la conexión: " . $e->getMessage());
}

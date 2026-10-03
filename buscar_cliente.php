<?php

header('Content-Type: application/json');

require_once __DIR__ . '/config/database.php';

if(!$conn) {
    echo json_encode(['success' => false, 'message' => 'Error en conexion: ' . pg_last_error()]);
    exit;
}

if(isset($_GET['dni'])) {
    $dni = $_GET['dni'];
    $query = "SELECT * FROM mae_cliente WHERE dni = $1";
    $result = pg_query_params($conn, $query, array($dni)); 
    if ($result && pg_num_rows($result) > 0) {
        $arr = pg_fetch_array($result);
        $nombre_cliente = ($arr['nombre'] . ' ' . $arr['apellido']); 
        $nombre_cliente = mb_convert_case($nombre_cliente, MB_CASE_UPPER, "UTF-8");
        echo json_encode(['success' => true, 'nombre_cliente' => $nombre_cliente]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó DNI']);
}

pg_close($conn);
?>
<?php

header('Content-Type: application/json');

require_once __DIR__ . '/config/database.php';

if(!$conn) {
    echo json_encode(['success' => false, 'message' => 'Error en conexión: ' . pg_last_error()]);
    exit;
}

if(isset($_GET['codigo_venta'])){
    $codigo_venta = $_GET['codigo_venta'];
    $query = "SELECT * FROM mae_puntodeventa WHERE idptv = $1";
    $result = pg_query_params($conn, $query, array($codigo_venta));
    if($result && pg_num_rows($result) > 0){
        $arr = pg_fetch_array($result);
        $nombre_venta = $arr['nombre'];
        $direccion = $arr['direccion'];
        $ciudad = $arr['ciudad'];
        $ruc = $arr['ruc'];
        $email = $arr['email'];
        echo json_encode(['success' => true, 'nombre_venta' => $nombre_venta, 'direccion' => $direccion, 'ciudad' => $ciudad, 'ruc' => $ruc, 'email' => $email]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Punto de venta no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó código de punto de venta']);
}

pg_close($conn);
?>
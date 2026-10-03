<?php

header('Content-Type: application/json');

require_once __DIR__ . '/config/database.php';

if(!$conn) {
    echo json_encode(['success' => false, 'message' => 'Error en conexión: ' . pg_last_error()]);
    exit;
}

if(isset($_GET['codigo_articulo'])) {
    $codigo_articulo = $_GET['codigo_articulo'];
    $query = "SELECT * FROM mae_articulo WHERE idarticulo = $1";
    $result = pg_query_params($conn, $query, array($codigo_articulo));
    if($result && pg_num_rows($result) > 0) {
        $arr = pg_fetch_array($result);
        $nombre_articulo = mb_convert_case($arr['nombre'], MB_CASE_UPPER, "UTF-8");
        $preciounitario = 'S/.' . $arr['preciounitario'];
        echo json_encode(['success' => true, 'nombre_articulo' => $nombre_articulo, 'preciounitario' => $preciounitario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Artículo no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó código de artículo']);
}

pg_close($conn);
?>
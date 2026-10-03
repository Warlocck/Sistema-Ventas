<?php

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$usuario = $input['usuario'];
$pw = $input['pw'];

require_once __DIR__ . '/config/database.php';

if(!$conn) {
    echo json_encode(['success' => false, 'message' => 'Error en conexión: ' . pg_last_error()]);
    exit;
}

$query = "SELECT * FROM mae_vendedor WHERE nombre = $1 AND idvendedor = $2";
$result = pg_query_params($conn, $query, array($usuario, $pw));

if($result && pg_num_rows($result) > 0) {
    $arr = pg_fetch_array($result);
    $nombreVendedor = $arr['nombre'] . ' ' . $arr['apellido'];
    $nroCaja = $arr['nrocaja'];
    echo json_encode(['success' => true, 'nombreVendedor' => $nombreVendedor, 'nroCaja' => $nroCaja]);
} else {
    echo json_encode(['success' => false, 'message' => 'Vendedor no registrado']);
}

pg_close($conn);
?>
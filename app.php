<?php

require_once __DIR__ . '/config/database.php';

if(!$conn) {
    echo json_encode(['success' => false, 'message' => 'Error en conexión: ' . pg_last_error()]);
    exit;
}
$nombre_cliente = '';
$nombre_articulo = '';
$preciounitario = '';
$total_articulo = 0.00;
$cantidad_articulo = 1;
$codigo_articulo = '';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET['buscar-cliente'])) {
        $dni = $_GET['dni'];
        $query = "SELECT * FROM mae_cliente WHERE dni = $1";
        $result = pg_query_params($conn, $query, array($dni)); //realizar consulta a la bd
        if ($result && pg_num_rows($result) > 0) {
            $arr = pg_fetch_array($result); //Para obtener la fila de la tabla buscada
            $nombre_cliente = ($arr['nombre'] . ' ' . $arr['apellido']); 
            $nombre_cliente = mb_convert_case($nombre_cliente, MB_CASE_UPPER, "UTF-8"); //PARA COLOCAR EN MAYUSCULAS
        } else {
            error_log('Error de obtencion');
        }
    } else {
        echo "<script>console.log('Error de obtencion')</script>";
    }
    
    if(isset($_GET['buscar-articulo'])){
        $idarticulo = $_GET['codigo_articulo'];
        $query = "SELECT * FROM mae_articulo WHERE idarticulo = $1";
        $result = pg_query_params($conn, $query, array($idarticulo));
        if($result && pg_num_rows($result) > 0) {
            $arr = pg_fetch_array($result);
            $nombre_articulo = ($arr['nombre']);
            $nombre_articulo = mb_convert_case($nombre_articulo, MB_CASE_UPPER, "UTF-8");
            $preciounitario = ('S/.' . $arr['preciounitario']);
        } else {
            error_log('Error de obtencion');
        }
    } else {
        echo "<script>console.log('Error de obtencion');</script>";
    }

    if(isset($_GET['agregar-producto'])){
        $cantidad_articulo = isset($_GET['cantidad_articulo']) ? $_GET['cantidad_articulo'] : 1;
        $nombre_articulo = isset($_GET['nombre_articulo']) ? $_GET['nombre_articulo'] : '';
        $codigo_articulo = isset($_POST['codigo_articulo']) ? $_POST['codigo_articulo'] : '';
        $precio_articulo = isset($_POST['precio']) ? str_replace('s/.', '', $_POST['precio']) : 0;
        $total_articulo = number_format(floatval($precio_articulo) * intval($cantidad_articulo), 2);
    }
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Ticket de Venta</title>
    <link rel="preload" href="styles/style.css" as="style">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <header>
        <h1><i class="fas fa-receipt"></i> SISTEMA DE TICKET DE VENTA</h1>
    </header>
    <div class="container">
        <div class="left-column">
            <form class="section" method="get">   
                <h3><i class="fas fa-user"></i> Datos del cliente</h3>
                <label for="dni">DNI</label>
                <div class="input-group">
                    <input type="text" id="dni" name="dni" value="<?php echo htmlspecialchars($_GET['dni'] ?? ''); ?>">
                    <button id="buscar-cliente" name="buscar-cliente"><i class="fas fa-search"></i></button>
                </div>
                <input type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Cliente" value="<?php echo htmlspecialchars($nombre_cliente); ?>" readonly>
            </form>
            <form class="section" method="get">
                <h3><i class="fas fa-box"></i> Datos del artículo</h3>
                <label for="codigo-articulo">Código</label>
                <div class="input-group">
                    <input type="text" id="codigo-articulo" name="codigo_articulo" value="<?php echo htmlspecialchars($_GET['codigo_articulo'] ?? ''); ?>">
                    <button id="buscar-articulo" name="buscar-articulo"><i class="fas fa-search"></i></button>
                </div>
                <input type="text" id="nombre_articulo" name="nombre_articulo" placeholder="Producto" value="<?php echo htmlspecialchars($nombre_articulo); ?>" readonly>
                <label for="precio-articulo">Precio</label>
                <input type="text" id="precio-articulo" name="precio" placeholder="S/. 0.00" value="<?php echo htmlspecialchars($preciounitario); ?>" readonly>
                <label for="cantidad-articulo">Cantidad</label>
                <input type="number" id="cantidad-articulo" name="canidad_articulo" min="1" value="<?php echo htmlspecialchars($cantidad_articulo); ?>">
                <button type="submit" name="agregar-producto"><i class="fas fa-plus"></i> Agregar Producto</button>
                <?php if(isset($error_articulo)): ?>
                    <p class="error"><?php echo $error_articulo; ?></p>
                <?php endif; ?>
            </form>
            <form class="section">
                <h3><i class="fas fa-map-marker-alt"></i> Punto de venta</h3>
                <label for="codigo-venta">Código</label>
                <input type="text" id="codigo-venta" placeholder="00000000">
                <input type="text" placeholder="DIRECCIÓN A" readonly>
            </form>
        </div>
        <div class="right-column">
            <div class="section details">
                <h3><i class="fas fa-shopping-cart"></i> Detalles de la compra</h3>
                <div class="serial-number">
                    <label for="nro-serie">Nro. Serie</label>
                    <input type="text" id="nro-serie" placeholder="0000" readonly>
                </div>
                <table id="detalles-compra">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_GET['agregar-producto'])): ?>
                            <tr>
                                <td>1</td>
                                <td><?php echo htmlspecialchars($nombre_articulo); ?></td>
                                <td><?php echo htmlspecialchars($codigo_articulo); ?></td>
                                <td><?php echo htmlspecialchars($preciounitario); ?></td>
                                <td><?php echo htmlspecialchars($cantidad_articulo); ?></td>
                                <td>S/. <?php echo $total_articulo; ?></td>
                                <td><button type="button" class="remove-producto"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        <?php endif; ?>
                        <!-- Los productos se añadirán aquí dinámicamente -->
                    </tbody>
                </table>
                <div class="total">
                    <button class="generate"><i class="fas fa-ticket-alt"></i> Generar ticket</button>
                    <button class="cancel"><i class="fas fa-times"></i> Cancelar</button>
                    <span id="total-compra">Total: S/. <?php echo isset($total_articulo) ? $total_articulo : '0.00'; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="modal-confirmacion" class="modal">
        <div class="modal-content">
            <h2>Confirmar Compra</h2>
            <p>¿Estás seguro de que deseas proceder con el pago?</p>
            <div class="modal-buttons">
                <button id="confirmar-compra">Sí</button>
                <button id="cancelar-compra">No</button>
            </div>
        </div>
    </div>

    <script>

    // Función para mostrar el modal de confirmación
    function mostrarModalConfirmacion() {
        var modal = document.getElementById('modal-confirmacion');
        modal.style.display = 'block';
    }

    // Función para cerrar el modal de confirmación
    function cerrarModalConfirmacion() {
        var modal = document.getElementById('modal-confirmacion');
        modal.style.display = 'none';
    }

    // Event listener para el botón "Generar ticket"
    var generarTicketBtn = document.querySelector('.generate');
    generarTicketBtn.addEventListener('click', function() {
        mostrarModalConfirmacion();
    });

    // Event listener para el botón "Cancelar" del modal
    var cancelarCompraBtn = document.getElementById('cancelar-compra');
    cancelarCompraBtn.addEventListener('click', function() {
        cerrarModalConfirmacion();
    });

    // Event listener para el botón "Sí" del modal
    var confirmarCompraBtn = document.getElementById('confirmar-compra');
    confirmarCompraBtn.addEventListener('click', function() {
        // Obtener los datos del ticket
        var cliente = document.getElementById('nombre-cliente').value;
        var nroSerie = document.getElementById('nro-serie').value;
        var detalles = [];
        var total = document.getElementById('total-compra').textContent.replace('Total: S/. ', '');

        document.querySelectorAll('#detalles-compra tbody tr').forEach(function(row) {
            var cells = row.querySelectorAll('td');
            detalles.push({
                producto: cells[1].textContent,
                codigo: cells[2].textContent,
                precio: cells[3].textContent,
                cantidad: cells[4].textContent,
                total: cells[5].textContent
            });
        });

        // Redirigir a la página del ticket con los datos en el query string
        var url = `ticket.php?cliente=${encodeURIComponent(cliente)}&nroSerie=${encodeURIComponent(nroSerie)}&detalles=${encodeURIComponent(JSON.stringify(detalles))}&total=${encodeURIComponent(total)}`;
        window.location.href = url;
    });
    const modal = document.getElementById("modal");
    const generarButton = document.getElementById(".generate");
    const cancelarButton = document.getElementById(".cancel");
    const confirmarGenerar = document.getElementById("confirmar-generar");
        const cancelarGenerar = document.getElementById("cancelar-generar");

        generarButton.onclick = function() {
            modal.style.display = "block";
        }

        cancelarButton.onclick = function() {
            // Lógica para cancelar la operación
        }

        confirmarGenerar.onclick = function() {
            modal.style.display = "none";
            // Lógica para generar el ticket de venta
        }

        cancelarGenerar.onclick = function() {
            modal.style.display = "none";
        }

        // Lógica para buscar datos de cliente, producto y dirección
        document.getElementById('buscar-cliente').addEventListener('click', function() {
            // Aquí iría la lógica para buscar el cliente por DNI
            const dni = document.getElementById('dni').value;
            // Simulando una búsqueda de cliente
            document.getElementById('nombre-cliente').value = 'Cliente Ejemplo';
        });

        document.getElementById('buscar-producto').addEventListener('click', function() {
            // Aquí iría la lógica para buscar el producto por código
            const codigoProducto = document.getElementById('codigo-producto').value;
            // Simulando una búsqueda de producto
            document.getElementById('nombre-producto').value = 'Producto Ejemplo';
            document.getElementById('precio-producto').value = 'S/.50.00';
        });

        document.getElementById('buscar-direccion').addEventListener('click', function() {
            // Aquí iría la lógica para buscar la dirección del punto de venta
            const direccionPuntoVenta = document.getElementById('direccion-punto-venta').value;
            // Simulando una búsqueda de dirección
            document.getElementById('direccion').value = 'Dirección Ejemplo';
        });

        // Agregar evento de clic para eliminar productos de la lista
        do
</script>

</body>
</html>
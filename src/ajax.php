<?php
require_once "../conexion.php";
session_start();
if (isset($_GET['q'])) {
    $datos = array();
    // Asignar los parámetros GET a variables seguras
    $nombreSeguro = $_GET['q'];

    // Cambio: Usando prepared statements para prevenir inyección SQL
    $clienteSeguro = $conexion->prepare("SELECT * FROM cliente WHERE nombre LIKE ? AND estado = 1");
    $nombreLike = "%" . $nombreSeguro . "%";
    $clienteSeguro->bind_param("s", $nombreLike);
    $clienteSeguro->execute();
    $cliente = $clienteSeguro->get_result();

    // Asignar la variable segura a la variable original
    $nombre = $nombreSeguro;

    while ($row = mysqli_fetch_assoc($cliente)) {
        $data['id'] = $row['idcliente'];
        $data['label'] = $row['nombre'];
        $data['direccion'] = $row['direccion'];
        $data['telefono'] = $row['telefono'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['pro'])) {
    $datos = array();
    // Asignar los parámetros GET a variables seguras
    $nombreSeguro = $_GET['pro'];

    // Cambio: Usando prepared statements para prevenir inyección SQL
    $productoSeguro = $conexion->prepare("SELECT * FROM producto WHERE codigo LIKE ? OR descripcion LIKE ? AND estado = 1");
    $nombreLike = "%" . $nombreSeguro . "%";
    $productoSeguro->bind_param("ss", $nombreLike, $nombreLike);
    $productoSeguro->execute();
    $producto = $productoSeguro->get_result();

    // Asignar la variable segura a la variable original
    $nombre = $nombreSeguro;

    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['codproducto'];
        $data['label'] = $row['codigo'] . ' - ' . $row['descripcion'];
        $data['value'] = $row['descripcion'];
        $data['precio'] = $row['precio'];
        $data['existencia'] = $row['existencia'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalle'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_temp d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_usuario = $id");
    $sumar = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = $id");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['descripcion'] = $row['descripcion'];
        $data['cantidad'] = $row['cantidad'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['sub_total'] = number_format($row['precio_venta'] * $row['cantidad'], 2, '.', ',');
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['delete_detalle'])) {
    // Asignar los parámetros GET a variables seguras
    $id_detalleSeguro = $_GET['id'];

    // Cambio: Usando prepared statements para prevenir inyección SQL
    $verificarSeguro = $conexion->prepare("SELECT * FROM detalle_temp WHERE id = ?");
    $verificarSeguro->bind_param("i", $id_detalleSeguro);
    $verificarSeguro->execute();
    $verificar = $verificarSeguro->get_result();

    // Asignar la variable segura a la variable original
    $id_detalle = $id_detalleSeguro;

    $datos = mysqli_fetch_assoc($verificar);
    if ($datos['cantidad'] > 1) {
        $cantidad = $datos['cantidad'] - 1;
        // Cambio: Usando prepared statements para prevenir inyección SQL
        $querySeguro = $conexion->prepare("UPDATE detalle_temp SET cantidad = ? WHERE id = ?");
        $querySeguro->bind_param("ii", $cantidad, $id_detalleSeguro);
        $querySeguro->execute();

        if ($querySeguro->affected_rows > 0) {
            $msg = "restado";
        } else {
            $msg = "Error";
        }
    } else {
        // Cambio: Usando prepared statements para prevenir inyección SQL
        $querySeguro = $conexion->prepare("DELETE FROM detalle_temp WHERE id = ?");
        $querySeguro->bind_param("i", $id_detalleSeguro);
        $querySeguro->execute();

        if ($querySeguro->affected_rows > 0) {
            $msg = "ok";
        } else {
            $msg = "Error";
        }
    }
    echo $msg;
    die();
} else if (isset($_GET['procesarVenta'])) {
    // Asignar los parámetros GET a variables seguras
    $id_clienteSeguro = $_GET['id'];
    $id_userSeguro = $_SESSION['idUser'];

    // Cambio: Usando prepared statements para prevenir inyección SQL
    $consultaSeguro = $conexion->prepare("SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = ?");
    $consultaSeguro->bind_param("i", $id_userSeguro);
    $consultaSeguro->execute();
    $consulta = $consultaSeguro->get_result();

    // Asignar las variables seguras a las variables originales
    $id_cliente = $id_clienteSeguro;
    $id_user = $id_userSeguro;

    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];

    // Cambio: Usando prepared statements para prevenir inyección SQL
    $insertarSeguro = $conexion->prepare("INSERT INTO ventas(id_cliente, total, id_usuario) VALUES (?, ?, ?)");
    $insertarSeguro->bind_param("idi", $id_clienteSeguro, $total, $id_userSeguro);
    $insertarSeguro->execute();

    if ($insertarSeguro->affected_rows > 0) {
        // Cambio: Usando prepared statements para prevenir inyección SQL
        $id_maximoSeguro = $conexion->prepare("SELECT MAX(id) AS total FROM ventas");
        $id_maximoSeguro->execute();
        $id_maximo = $id_maximoSeguro->get_result();
        $resultId = mysqli_fetch_assoc($id_maximo);
        $ultimoId = $resultId['total'];

        // Consulta segura para obtener los detalles
        $consultaDetalleSeguro = $conexion->prepare("SELECT * FROM detalle_temp WHERE id_usuario = ?");
        $consultaDetalleSeguro->bind_param("i", $id_userSeguro);
        $consultaDetalleSeguro->execute();
        $consultaDetalle = $consultaDetalleSeguro->get_result();

        while ($row = mysqli_fetch_assoc($consultaDetalle)) {
            $id_producto = $row['id_producto'];
            $cantidad = $row['cantidad'];
            $precio = $row['precio_venta'];

            // Cambio: Usando prepared statements para prevenir inyección SQL
            $insertarDetSeguro = $conexion->prepare("INSERT INTO detalle_venta(id_producto, id_venta, cantidad, precio) VALUES (?, ?, ?, ?)");
            $insertarDetSeguro->bind_param("iiid", $id_producto, $ultimoId, $cantidad, $precio);
            $insertarDetSeguro->execute();

            // Cambio: Usando prepared statements para prevenir inyección SQL
            $stockActualSeguro = $conexion->prepare("SELECT * FROM producto WHERE codproducto = ?");
            $stockActualSeguro->bind_param("i", $id_producto);
            $stockActualSeguro->execute();
            $stockActual = $stockActualSeguro->get_result();

            $stockNuevo = mysqli_fetch_assoc($stockActual);
            $stockTotal = $stockNuevo['existencia'] - $cantidad;

            // Cambio: Usando prepared statements para prevenir inyección SQL
            $stockSeguro = $conexion->prepare("UPDATE producto SET existencia = ? WHERE codproducto = ?");
            $stockSeguro->bind_param("ii", $stockTotal, $id_producto);
            $stockSeguro->execute();
        } 
        if ($insertarDetSeguro->affected_rows > 0) {
            // Cambio: Usando prepared statements para prevenir inyección SQL
            $eliminarSeguro = $conexion->prepare("DELETE FROM detalle_temp WHERE id_usuario = ?");
            $eliminarSeguro->bind_param("i", $id_userSeguro);
            $eliminarSeguro->execute();

            $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
        } 
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
}
if (isset($_POST['action'])) {
    // Asignar los parámetros POST a variables seguras
    $idSeguro = $_POST['id'];
    $cantSeguro = $_POST['cant'];
    $precioSeguro = $_POST['precio'];
    $id_userSeguro = $_SESSION['idUser'];
    $totalSeguro = $precioSeguro * $cantSeguro;

    // Cambio: Usando prepared statements para prevenir inyección SQL
    $verificarSeguro = $conexion->prepare("SELECT * FROM detalle_temp WHERE id_producto = ? AND id_usuario = ?");
    $verificarSeguro->bind_param("ii", $idSeguro, $id_userSeguro);
    $verificarSeguro->execute();
    $verificar = $verificarSeguro->get_result();

    // Asignar las variables seguras a las variables originales
    $id = $idSeguro;
    $cant = $cantSeguro;
    $precio = $precioSeguro;
    $id_user = $id_userSeguro;
    $total = $totalSeguro;

    $result = mysqli_num_rows($verificar);
    $datos = mysqli_fetch_assoc($verificar);
    if ($result > 0) {
        $cantidad = $datos['cantidad'] + 1;
        $total_precio = $cantidad * $total;

        // Cambio: Usando prepared statements para prevenir inyección SQL
        $querySeguro = $conexion->prepare("UPDATE detalle_temp SET cantidad = ?, total = ? WHERE id_producto = ? AND id_usuario = ?");
        $querySeguro->bind_param("idii", $cantidad, $total_precio, $idSeguro, $id_userSeguro);
        $querySeguro->execute();

        if ($querySeguro->affected_rows > 0) {
            $msg = "actualizado";
        } else {
            $msg = "Error al ingresar";
        }
    } else {
        // Cambio: Usando prepared statements para prevenir inyección SQL
        $querySeguro = $conexion->prepare("INSERT INTO detalle_temp(id_usuario, id_producto, cantidad, precio_venta, total) VALUES (?, ?, ?, ?, ?)");
        $querySeguro->bind_param("iiidi", $id_userSeguro, $idSeguro, $cantSeguro, $precioSeguro, $totalSeguro);
        $querySeguro->execute();

        if ($querySeguro->affected_rows > 0) {
            $msg = "registrado";
        } else {
            $msg = "Error al ingresar";
        }
    }
    echo json_encode($msg);
    die();
}
if (isset($_POST['cambio'])) {
    if (empty($_POST['actual']) || empty($_POST['nueva'])) {
        $msg = 'Los campos estan vacios';
    } else {
        // Asignar los parámetros POST a variables seguras
        $idSeguro = $_SESSION['idUser'];
        $actualSeguro = md5($_POST['actual']);
        $nuevaSeguro = md5($_POST['nueva']);

        // Cambio: Usando prepared statements para prevenir inyección SQL
        $consultaSeguro = $conexion->prepare("SELECT * FROM usuario WHERE clave = ? AND idusuario = ?");
        $consultaSeguro->bind_param("si", $actualSeguro, $idSeguro);
        $consultaSeguro->execute();
        $consulta = $consultaSeguro->get_result();

        // Asignar las variables seguras a las variables originales
        $id = $idSeguro;
        $actual = $actualSeguro;
        $nueva = $nuevaSeguro;

        $result = mysqli_num_rows($consulta);
        if ($result == 1) {
            // Cambio: Usando prepared statements para prevenir inyección SQL
            $querySeguro = $conexion->prepare("UPDATE usuario SET clave = ? WHERE idusuario = ?");
            $querySeguro->bind_param("si", $nuevaSeguro, $idSeguro);
            $querySeguro->execute();

            if ($querySeguro->affected_rows > 0) {
                $msg = 'ok';
            } else {
                $msg = 'error';
            }
        } else {
            $msg = 'dif';
        }
    }
    echo $msg;
    die();
}
?>

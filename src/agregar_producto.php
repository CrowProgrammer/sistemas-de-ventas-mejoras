<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (empty($_GET['id'])) {
    header("Location: productos.php");
} else {
    $id_producto_seguro = $_GET['id'];
    if (!is_numeric($id_producto_seguro)) {
        header("Location: productos.php");
    }
    // Cambio: Usando prepared statements para prevenir inyección SQL
    $consulta_segura = $conexion->prepare("SELECT * FROM producto WHERE codproducto = ?");
    $consulta_segura->bind_param("i", $id_producto_seguro);
    $consulta_segura->execute();
    $consulta = $consulta_segura->get_result();
    $data_producto = mysqli_fetch_assoc($consulta);

    // Asignar la variable segura a la variable original
    $id_producto = $id_producto_seguro;
}
if (!empty($_POST)) {
    $alert = "";
    if (!empty($_POST['cantidad']) || !empty($_POST['precio'])) {
        $precio_seguro = $_POST['precio'];
        $cantidad_segura = $_POST['cantidad'];
        $producto_id_seguro = $id_producto_seguro;

        // Asignar las variables seguras a las variables originales
        $precio = $precio_seguro;
        $cantidad = $cantidad_segura;
        $producto_id = $producto_id_seguro;

        $total = $cantidad + $data_producto['existencia'];
        // Cambio: Usando prepared statements para prevenir inyección SQL
        $query_insert_seguro = $conexion->prepare("UPDATE producto SET existencia = ? WHERE codproducto = ?");
        $query_insert_seguro->bind_param("ii", $total, $producto_id_seguro);
        $query_insert_seguro->execute();

        if ($query_insert_seguro->affected_rows > 0) {
            $alert = '<div class="alert alert-success" role="alert">
                        Stock actualizado
                    </div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                        Error al ingresar la cantidad
                    </div>';
        }
        mysqli_close($conexion);
    } else {
        $alert = '<div class="alert alert-danger" role="alert">
                        Todo los campos son obligatorios
                    </div>';
    }
}
?>
<div class="row">
    <div class="col-lg-6 m-auto">
        <div class="card">
            <div class="card-header bg-primary">
                Agregar Producto
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="precio">Precio Actual</label>
                        <input type="text" class="form-control" value="<?php echo $data_producto['precio']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="precio">Cantidad de productos Disponibles</label>
                        <input type="number" class="form-control" value="<?php echo $data_producto['existencia']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="precio">Nuevo Precio</label>
                        <input type="text" placeholder="Ingrese nombre del precio" name="precio" class="form-control" value="<?php echo $data_producto['precio']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Agregar Cantidad</label>
                        <input type="number" placeholder="Ingrese cantidad" name="cantidad" id="cantidad" class="form-control">
                    </div>

                    <input type="submit" value="Actualizar" class="btn btn-primary">
                    <a href="productos.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>

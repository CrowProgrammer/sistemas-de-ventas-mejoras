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
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['codigo']) || empty($_POST['producto']) || empty($_POST['precio'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    // Asignar los parámetros POST a variables seguras
    $codproductoSeguro = $_GET['id'];
    $codigoSeguro = $_POST['codigo'];
    $productoSeguro = $_POST['producto'];
    $precioSeguro = $_POST['precio'];

    // Realizar la consulta segura
    $query_update_seguro = $conexion->prepare("UPDATE producto SET codigo = ?, descripcion = ?, precio = ? WHERE codproducto = ?");
    $query_update_seguro->bind_param("ssdi", $codigoSeguro, $productoSeguro, $precioSeguro, $codproductoSeguro);
    $query_update_seguro->execute();

    // Asignar las variables seguras a las variables originales
    $codproducto = $codproductoSeguro;
    $codigo = $codigoSeguro;
    $producto = $productoSeguro;
    $precio = $precioSeguro;

    if ($query_update_seguro->affected_rows > 0) {
      $alert = '<div class="alert alert-primary" role="alert">
              Producto Modificado
            </div>';
    } else {
      $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
    }
  }
}

// Validar producto

if (empty($_REQUEST['id'])) {
  header("Location: productos.php");
} else {
  // Asignar el parámetro REQUEST a una variable segura
  $id_productoSeguro = $_REQUEST['id'];
  if (!is_numeric($id_productoSeguro)) {
    header("Location: productos.php");
  }
  
  // Realizar la consulta segura
  $query_producto_seguro = $conexion->prepare("SELECT * FROM producto WHERE codproducto = ?");
  $query_producto_seguro->bind_param("i", $id_productoSeguro);
  $query_producto_seguro->execute();
  $result_producto_seguro = $query_producto_seguro->get_result();

  // Asignar la variable segura a la variable original
  $id_producto = $id_productoSeguro;

  if ($result_producto_seguro->num_rows > 0) {
    $data_producto = $result_producto_seguro->fetch_assoc();
  } else {
    header("Location: productos.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar producto
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="codigo">Código de Barras</label>
            <input type="text" placeholder="Ingrese código de barras" name="codigo" id="codigo" class="form-control" value="<?php echo $data_producto['codigo']; ?>">
          </div>
          <div class="form-group">
            <label for="producto">Producto</label>
            <input type="text" class="form-control" placeholder="Ingrese nombre del producto" name="producto" id="producto" value="<?php echo $data_producto['descripcion']; ?>">

          </div>
          <div class="form-group">
            <label for="precio">Precio</label>
            <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio" value="<?php echo $data_producto['precio']; ?>">

          </div>
          <input type="submit" value="Actualizar Producto" class="btn btn-primary">
          <a href="productos.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>

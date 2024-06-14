<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        // Asignar los parámetros POST a variables seguras
        $idclienteSeguro = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
        $nombreSeguro = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
        $telefonoSeguro = htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8');
        $direccionSeguro = htmlspecialchars($_POST['direccion'], ENT_QUOTES, 'UTF-8');

        // Cambio: Usando prepared statements para prevenir inyección SQL
        $sql_update_seguro = $conexion->prepare("UPDATE cliente SET nombre = ?, telefono = ?, direccion = ? WHERE idcliente = ?");
        $sql_update_seguro->bind_param("sssi", $nombreSeguro, $telefonoSeguro, $direccionSeguro, $idclienteSeguro);
        $sql_update_seguro->execute();

        if ($sql_update_seguro->affected_rows > 0) {
            $alert = '<div class="alert alert-success" role="alert">Cliente Actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Cliente</div>';
        }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: clientes.php");
}

// Asignar el parámetro REQUEST a una variable segura
$idclienteSeguro = htmlspecialchars($_REQUEST['id'], ENT_QUOTES, 'UTF-8');

// Cambio: Usando prepared statements para prevenir inyección SQL
$sql_seguro = $conexion->prepare("SELECT * FROM cliente WHERE idcliente = ?");
$sql_seguro->bind_param("i", $idclienteSeguro);
$sql_seguro->execute();
$result = $sql_seguro->get_result();

if ($result->num_rows == 0) {
    header("Location: clientes.php");
} else {
    $data = $result->fetch_assoc();
    $idcliente = $data['idcliente'];
    $nombre = htmlspecialchars($data['nombre'], ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($data['telefono'], ENT_QUOTES, 'UTF-8');
    $direccion = htmlspecialchars($data['direccion'], ENT_QUOTES, 'UTF-8');
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Cliente
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" placeholder="Ingrese Nombre" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="number" placeholder="Ingrese Teléfono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" placeholder="Ingrese Direccion" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Cliente</button>
                        <a href="clientes.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>

<?php include_once "includes/header.php";
require "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        // Asignar los parámetros POST a variables seguras
        $idusuarioSeguro = $_GET['id'];
        $nombreSeguro = $_POST['nombre'];
        $correoSeguro = $_POST['correo'];
        $usuarioSeguro = $_POST['usuario'];

        // Cambio: Usando prepared statements para prevenir inyección SQL
        $sql_update_seguro = $conexion->prepare("UPDATE usuario SET nombre = ?, correo = ?, usuario = ? WHERE idusuario = ?");
        $sql_update_seguro->bind_param("sssi", $nombreSeguro, $correoSeguro, $usuarioSeguro, $idusuarioSeguro);
        $sql_update_seguro->execute();

        // Asignar las variables seguras a las variables originales
        $idusuario = $idusuarioSeguro;
        $nombre = $nombreSeguro;
        $correo = $correoSeguro;
        $usuario = $usuarioSeguro;

        if ($sql_update_seguro->affected_rows > 0) {
            $alert = '<div class="alert alert-success" role="alert">Usuario Actualizado</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Usuario</div>';
        }
    }
}

// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: usuarios.php");
}

// Asignar el parámetro REQUEST a una variable segura
$idusuarioSeguro = $_REQUEST['id'];

// Cambio: Usando prepared statements para prevenir inyección SQL
$sql_seguro = $conexion->prepare("SELECT * FROM usuario WHERE idusuario = ?");
$sql_seguro->bind_param("i", $idusuarioSeguro);
$sql_seguro->execute();
$sql = $sql_seguro->get_result();

// Asignar la variable segura a la variable original
$idusuario = $idusuarioSeguro;

$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: usuarios.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idusuario = $data['idusuario'];
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $usuario = $data['usuario'];
    }
}
?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Modificar Usuario
            </div>
            <div class="card-body">
                <form class="" action="" method="post">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($idusuario, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" placeholder="Ingrese nombre" class="form-control" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="text" placeholder="Ingrese correo" class="form-control" name="correo" id="correo" value="<?php echo htmlspecialchars($correo, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" placeholder="Ingrese usuario" class="form-control" name="usuario" id="usuario" value="<?php echo htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i></button>
                    <a href="usuarios.php" class="btn btn-danger">Atras</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>

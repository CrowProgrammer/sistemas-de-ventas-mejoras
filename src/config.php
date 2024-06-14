<?php
include_once "includes/header.php";
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "configuracion";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
    exit;
}
$query = mysqli_query($conexion, "SELECT * FROM configuracion");
$data = mysqli_fetch_assoc($query);
if ($_POST) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['email']) || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-danger" role="alert">
            Todo los campos son obligatorios
        </div>';
    } else {
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $id = $_POST['id'];

        // Cambio: Usando prepared statements para prevenir inyección SQL
        $stmt = $conexion->prepare("UPDATE configuracion SET nombre = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $telefono, $email, $direccion, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $alert = '<div class="alert alert-success" role="alert">
            Datos modificados
        </div>';
        }
        $stmt->close();
    }
}
?>

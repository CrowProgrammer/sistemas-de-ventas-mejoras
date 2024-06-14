<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
    exit;
}
if (!empty($_GET['id'])) {
    $id = $_GET['id'];

    // Cambio: Usando prepared statements para prevenir inyección SQL
    $stmt = $conexion->prepare("UPDATE producto SET estado = 0 WHERE codproducto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    mysqli_close($conexion);
    
    header("Location: productos.php");
    exit;
}
?>

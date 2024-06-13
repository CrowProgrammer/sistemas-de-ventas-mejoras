<?php 
include_once "includes/header.php";
require_once "../conexion.php";

// Asignar el parámetro GET a una variable segura
$idSeguro = $_GET['id'];

// Consultas seguras utilizando prepared statements
$sqlpermisos = mysqli_query($conexion, "SELECT * FROM permisos");

// Consulta segura para obtener los usuarios
$usuariosSeguro = $conexion->prepare("SELECT * FROM usuario WHERE idusuario = ?");
$usuariosSeguro->bind_param("i", $idSeguro);
$usuariosSeguro->execute();
$usuarios = $usuariosSeguro->get_result();

// Consulta segura para obtener los permisos del usuario
$consultaSeguro = $conexion->prepare("SELECT * FROM detalle_permisos WHERE id_usuario = ?");
$consultaSeguro->bind_param("i", $idSeguro);
$consultaSeguro->execute();
$consulta = $consultaSeguro->get_result();

// Asignar las variables seguras a las variables originales
$id = $idSeguro;

$resultUsuario = mysqli_num_rows($usuarios);
if (empty($resultUsuario)) {
    header("Location: usuarios.php");
}

$datos = array();
foreach ($consulta as $asignado) {
    $datos[$asignado['id_permiso']] = true;
}

if (isset($_POST['permisos'])) {
    // Asignar los parámetros POST a variables seguras
    $id_userSeguro = $_GET['id'];
    $permisosSeguro = $_POST['permisos'];

    // Consulta segura para eliminar los permisos del usuario
    $deleteSeguro = $conexion->prepare("DELETE FROM detalle_permisos WHERE id_usuario = ?");
    $deleteSeguro->bind_param("i", $id_userSeguro);
    $deleteSeguro->execute();

    // Asignar las variables seguras a las variables originales
    $id_user = $id_userSeguro;
    $permisos = $permisosSeguro;

    if ($permisos != "") {
        foreach ($permisos as $permiso) {
            // Consulta segura para insertar los permisos del usuario
            $insertSeguro = $conexion->prepare("INSERT INTO detalle_permisos(id_usuario, id_permiso) VALUES (?, ?)");
            $insertSeguro->bind_param("ii", $id_userSeguro, $permiso);
            $insertSeguro->execute();

            if ($insertSeguro->affected_rows > 0) {
                header("Location: rol.php?id=".$id_user."&m=si");
            } else {
                $alert = '<div class="alert alert-primary" role="alert">
                            Error al actualizar permisos
                        </div>';
            }
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-white">
                Permisos
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php if(isset($_GET['m']) && $_GET['m'] == 'si') { ?>
                        <div class="alert alert-success" role="alert">
                            Permisos actualizado
                        </div>

                    <?php } ?>
                    <?php while ($row = mysqli_fetch_assoc($sqlpermisos)) { ?>
                        <div class="form-check form-check-inline m-4">
                            <label for="permisos" class="p-2 text-uppercase"><?php echo $row['nombre']; ?></label>
                            <input id="permisos" type="checkbox" name="permisos[]" value="<?php echo $row['id']; ?>" <?php
                                                                                                                                                    if (isset($datos[$row['id']])) {
                                                                                                                                                        echo "checked";
                                                                                                                                                    }
                                                                                                                                                    ?>>
                        </div>
                    <?php } ?>
                    <br>
                    <button class="btn btn-primary btn-block" type="submit">Modificar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>

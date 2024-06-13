<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave'])) {
        $alert = '<div class="alert alert-danger" role="alert">
        Todo los campos son obligatorios
        </div>';
    } else {
        // Asignar los parámetros POST a variables seguras
        $nombreSeguro = $_POST['nombre'];
        $emailSeguro = $_POST['correo'];
        $userSeguro = $_POST['usuario'];
        $claveSeguro = md5($_POST['clave']);

        // Cambio: Usando prepared statements para prevenir inyección SQL
        $query_seguro = $conexion->prepare("SELECT * FROM usuario WHERE correo = ?");
        $query_seguro->bind_param("s", $emailSeguro);
        $query_seguro->execute();
        $result_seguro = $query_seguro->get_result();

        // Asignar las variables seguras a las variables originales
        $nombre = $nombreSeguro;
        $email = $emailSeguro;
        $user = $userSeguro;
        $clave = $claveSeguro;

        if ($result_seguro->num_rows > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
                        El correo ya existe
                    </div>';
        } else {
            // Cambio: Usando prepared statements para prevenir inyección SQL
            $query_insert_seguro = $conexion->prepare("INSERT INTO usuario(nombre, correo, usuario, clave) VALUES (?, ?, ?, ?)");
            $query_insert_seguro->bind_param("ssss", $nombreSeguro, $emailSeguro, $userSeguro, $claveSeguro);
            $query_insert_seguro->execute();

            if ($query_insert_seguro->affected_rows > 0) {
                $alert = '<div class="alert alert-primary" role="alert">
                            Usuario registrado
                        </div>';
                header("Location: usuarios.php");
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                        Error al registrar
                    </div>';
            }
        }
    }
}
?>
<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#nuevo_usuario"><i class="fas fa-plus"></i></button>
<div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Usuario</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="correo" id="correo">
                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" class="form-control" placeholder="Ingrese Usuario" name="usuario" id="usuario">
                    </div>
                    <div class="form-group">
                        <label for="clave">Contraseña</label>
                        <input type="password" class="form-control" placeholder="Ingrese Contraseña" name="clave" id="clave">
                    </div>
                    <input type="submit" value="Registrar" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php ?>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM usuario ORDER BY estado DESC");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    if ($data['estado'] == 1) {
                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                    } else {
                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                    }
            ?>
                    <tr>

                        <td><?php echo $data['idusuario']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['correo']; ?></td>
                        <td><?php echo $data['usuario']; ?></td>
                        <td><?php echo $estado; ?></td>
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                <a href="rol.php?id=<?php echo $data['idusuario']; ?>" class="btn btn-warning"><i class='fas fa-key'></i></a>
                                <a href="editar_usuario.php?id=<?php echo $data['idusuario']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <form action="eliminar_usuario.php?id=<?php echo $data['idusuario']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<?php include_once "includes/footer.php"; ?>

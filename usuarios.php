<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
include("conexion.php");

// Guardar usuario
if (isset($_POST['guardar'])) {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $clave = mysqli_real_escape_string($conexion, $_POST['clave']);

    // prevenir duplicados simples
    $check = mysqli_query($conexion, "SELECT id FROM usuarios WHERE usuario='$usuario' LIMIT 1");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conexion, "INSERT INTO usuarios (usuario, clave) VALUES ('$usuario','$clave')");
    }
    header("Location: usuarios.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM usuarios WHERE id=$id");
    header("Location: usuarios.php");
    exit();
}

// Cargar para editar
$usuario_editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id=$id LIMIT 1");
    if ($res && mysqli_num_rows($res)>0) $usuario_editar = mysqli_fetch_assoc($res);
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $clave = mysqli_real_escape_string($conexion, $_POST['clave']);
    mysqli_query($conexion, "UPDATE usuarios SET usuario='$usuario', clave='$clave' WHERE id=$id");
    header("Location: usuarios.php");
    exit();
}

// Obtener usuarios
$resultado = mysqli_query($conexion, "SELECT * FROM usuarios ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Usuarios - Papelería Inventario</title>
<link rel="icon" href="img/milogo.png">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
body{ margin:0; font-family:Arial, sans-serif; background:#f8f9fa; transition:background-color .3s,color .3s; }
.sidebar{ width:240px; background:#198754; color:#fff; position:fixed; top:0; bottom:0; padding:25px 18px; display:flex; flex-direction:column; align-items:center; }
.sidebar img{ width:90px; height:90px; border-radius:50%; margin-bottom:12px; object-fit:cover;}
.sidebar h4{ color:#fff; margin-bottom:18px; text-align:center; }
.sidebar a{ color:#fff; text-decoration:none; width:100%; padding:10px 12px; border-radius:8px; display:flex; gap:10px; margin-bottom:6px; font-weight:600; align-items:center; }
.sidebar a:hover, .sidebar a.active{ background:#157347; }
.main-content{ margin-left:260px; padding:28px; }
.header h1{ color:#198754; margin:0 0 18px 0; }

/* forms */
.forms-row{ display:flex; gap:20px; margin-bottom:20px; flex-wrap:wrap; }
.form-card{ background:#fff; padding:12px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.06); min-width:260px; flex:1; }

/* table */
.table-wrap{ background:#fff; padding:12px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.06); }
table{ width:100%; border-collapse:collapse; text-align:center; }
th{ background:#198754; color:#fff; padding:10px; }
td{ padding:9px; border-bottom:1px solid #eee; }
tr:hover{ background:#f6f8f9; }

/* botones */
.btn{ border-radius:6px; }
.btn-success{ background:#198754; border:none; }
.btn-danger{ background:#dc3545; border:none; }
.acciones{ display:flex; justify-content:center; gap:8px; }

/* dark */
.dark-mode{ background:#121212; color:#e6e6e6; }
.dark-mode .sidebar{ background:#0f3d28; }
.dark-mode .form-card, .dark-mode .table-wrap{ background:#222; color:#e6e6e6; box-shadow:none; }
.dark-mode th{ background:#14502a; }

/* toggle */
#modoBtn{ position:fixed; top:18px; right:18px; z-index:1000; background:#198754; color:#fff; border:none; padding:9px 12px; border-radius:8px; cursor:pointer; font-weight:700; }
#modoBtn:hover{ background:#157347; }
</style>
</head>
<body>

<button id="modoBtn">🌙 Modo oscuro</button>

<div class="sidebar">
    <img src="img/milogo.png" alt="Logo">
    <h4>Papelería Inventario</h4>
    <a href="inicio.php"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="productos.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="usuarios.php" class="active"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="nosotros.php"><i class="bi bi-person-badge-fill"></i> Nosotros</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Gestión de Usuarios</h1>
    </div>

    <div class="forms-row">
        <div class="form-card">
            <form method="POST" class="row g-2">
                <div class="col-md-6">
                    <input class="form-control" name="usuario" placeholder="Usuario" required>
                </div>
                <div class="col-md-6">
                    <input class="form-control" name="clave" placeholder="Clave" required>
                </div>
                <div class="col-12 text-end mt-2">
                    <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>

        <?php if ($usuario_editar): ?>
        <div class="form-card">
            <form method="POST" class="row g-2">
                <input type="hidden" name="id" value="<?php echo intval($usuario_editar['id']); ?>">
                <div class="col-12"><strong>Editar usuario ID: <?php echo intval($usuario_editar['id']); ?></strong></div>
                <div class="col-md-6">
                    <input class="form-control" name="usuario" value="<?php echo htmlspecialchars($usuario_editar['usuario']); ?>" required>
                </div>
                <div class="col-md-6">
                    <input class="form-control" name="clave" value="<?php echo htmlspecialchars($usuario_editar['clave']); ?>" required>
                </div>
                <div class="col-12 text-end mt-2">
                    <button type="submit" name="actualizar" class="btn btn-success">Actualizar</button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>Usuario</th><th>Clave</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo intval($fila['id']); ?></td>
                    <td><?php echo htmlspecialchars($fila['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($fila['clave']); ?></td>
                    <td class="acciones">
                        <a class="btn btn-success btn-sm" href="usuarios.php?editar=<?php echo intval($fila['id']); ?>">Editar</a>
                        <a class="btn btn-danger btn-sm" href="usuarios.php?eliminar=<?php echo intval($fila['id']); ?>" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const btn = document.getElementById('modoBtn');
    const body = document.body;
    const saved = localStorage.getItem('modoTema');
    if (saved==='oscuro'){ body.classList.add('dark-mode'); btn.textContent='🌞 Modo claro'; }
    btn.addEventListener('click', ()=>{
        body.classList.toggle('dark-mode');
        if (body.classList.contains('dark-mode')){ btn.textContent='🌞 Modo claro'; localStorage.setItem('modoTema','oscuro'); }
        else { btn.textContent='🌙 Modo oscuro'; localStorage.setItem('modoTema','claro'); }
    });
});
</script>
</body>
</html>

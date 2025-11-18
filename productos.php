<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
include("conexion.php");

// --- Guardar nuevo producto ---
if (isset($_POST['guardar'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);

    $sql = "INSERT INTO productos (nombre, descripcion, categoria, precio) 
            VALUES ('$nombre','$descripcion','$categoria','$precio')";
    mysqli_query($conexion, $sql);
    header("Location: productos.php");
    exit();
}

// --- Eliminar producto ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM productos WHERE id=$id");
    header("Location: productos.php");
    exit();
}

// --- Cargar producto para editar ---
$producto_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = intval($_GET['editar']);
    $res = mysqli_query($conexion, "SELECT * FROM productos WHERE id = $id_editar LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $producto_editar = mysqli_fetch_assoc($res);
    }
}

// --- Actualizar producto ---
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);

    $sql = "UPDATE productos SET 
                nombre='$nombre',
                descripcion='$descripcion',
                categoria='$categoria',
                precio='$precio'
            WHERE id=$id";
    mysqli_query($conexion, $sql);
    header("Location: productos.php");
    exit();
}

// --- Obtener productos ---
$resultado = mysqli_query($conexion, "SELECT * FROM productos ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Productos - Papelería Inventario</title>
<link rel="icon" href="img/milogo.png">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
/* Base y layout */
body { margin:0; font-family: Arial, sans-serif; transition: background-color .3s, color .3s; background-color:#f8f9fa; }
.sidebar { width:240px; background:#198754; color:#fff; position:fixed; top:0; bottom:0; padding:25px 18px; display:flex; flex-direction:column; align-items:center; }
.sidebar img{ width:90px; height:90px; border-radius:50%; margin-bottom:12px; object-fit:cover; }
.sidebar h4{ color:#fff; margin-bottom:18px; text-align:center; }
.sidebar a{ color:#fff; text-decoration:none; width:100%; padding:10px 12px; border-radius:8px; display:flex; gap:10px; align-items:center; font-weight:600; margin-bottom:6px; }
.sidebar a:hover, .sidebar a.active{ background:#157347; }
.main-content{ margin-left:260px; padding:28px; }

/* Header */
.header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.header h1{ color:#198754; margin:0; }

/* Forms layout */
.forms-row { display:flex; gap:20px; margin-bottom:20px; flex-wrap:wrap; }
.form-card { background:#fff; padding:15px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.06); flex:1; min-width:260px; }

/* Table styling */
.table-wrap { background:#fff; padding:12px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.06); }
table { width:100%; border-collapse:collapse; text-align:center; }
th { background:#198754; color:#fff; padding:10px; }
td { padding:9px; border-bottom:1px solid #eee; }
tr:hover { background:#f6f8f9; }

/* Buttons */
.btn { border-radius:6px; }
.btn-success { background:#198754; border:none; }
.btn-success:hover { background:#157347; }
.btn-danger { background:#dc3545; border:none; }
.btn-danger:hover { background:#b02a37; }

/* Actions center */
.acciones { display:flex; justify-content:center; gap:8px; }

/* Dark mode */
.dark-mode { background:#121212; color:#e6e6e6; }
.dark-mode .sidebar { background:#0f3d28; }
.dark-mode .main-content { background:#171717; color:#e6e6e6; }
.dark-mode .form-card, .dark-mode .table-wrap { background:#222; color:#e6e6e6; box-shadow:none; }
.dark-mode th { background:#14502a; }

/* Dark mode toggle */
#modoBtn { position:fixed; top:18px; right:18px; z-index:1000; background:#198754; color:#fff; border:none; padding:9px 12px; border-radius:8px; cursor:pointer; font-weight:700; }
#modoBtn:hover{ background:#157347; }

/* PDF Button */
.btn-pdf {
  background-color: #dc3545;
  color: white;
  padding: 10px 18px;
  border-radius: 6px;
  font-weight: bold;
  text-decoration: none;
  display: inline-block;
  margin-top: 20px;
  transition: background 0.3s;
}
.btn-pdf:hover {
  background-color: #b02a37;
}

/* Responsive */
@media (max-width:900px){
  .forms-row { flex-direction:column; }
  .main-content { margin-left:0; padding:20px; }
  .sidebar { position:relative; width:100%; height:auto; flex-direction:row; gap:12px; padding:12px; justify-content:flex-start; }
  .sidebar img{ width:50px; height:50px; }
  .sidebar h4{ display:none; }
}
</style>
</head>
<body>

<button id="modoBtn">🌙 Modo oscuro</button>

<div class="sidebar">
    <img src="img/milogo.png" alt="Logo">
    <h4>Papelería Inventario</h4>
    <a href="inicio.php"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="productos.php" class="active"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="nosotros.php"><i class="bi bi-person-badge-fill"></i> Nosotros</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Gestión de Productos</h1>
    </div>

    <!-- Forms: agregar y editar -->
    <div class="forms-row">
        <div class="form-card">
            <form method="POST" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input class="form-control" type="text" name="nombre" placeholder="Nombre" required>
                </div>
                <div class="col-md-5">
                    <input class="form-control" type="text" name="descripcion" placeholder="Descripción">
                </div>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="categoria" placeholder="Categoración">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="precio" placeholder="Precio" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="guardar" class="btn btn-success w-100">Guardar</button>
                </div>
            </form>
        </div>

        <?php if ($producto_editar): ?>
        <div class="form-card">
            <form method="POST" class="row g-2">
                <input type="hidden" name="id" value="<?php echo intval($producto_editar['id']); ?>">
                <div class="col-12"><strong>Editar producto ID: <?php echo intval($producto_editar['id']); ?></strong></div>
                <div class="col-md-6">
                    <input class="form-control" type="text" name="nombre" value="<?php echo htmlspecialchars($producto_editar['nombre']); ?>" required>
                </div>
                <div class="col-md-6">
                    <input class="form-control" type="text" name="descripcion" value="<?php echo htmlspecialchars($producto_editar['descripcion']); ?>">
                </div>
                <div class="col-md-4">
                    <input class="form-control mt-2" type="text" name="categoria" value="<?php echo htmlspecialchars($producto_editar['categoria']); ?>">
                </div>
                <div class="col-md-4">
                    <input class="form-control mt-2" type="text" name="precio" value="<?php echo htmlspecialchars($producto_editar['precio']); ?>" required>
                </div>
                <div class="col-md-4 text-end mt-2">
                    <button type="submit" name="actualizar" class="btn btn-success">Actualizar</button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Tabla -->
    <div class="table-wrap text-center">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo intval($fila['id']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($fila['categoria']); ?></td>
                    <td>$<?php echo number_format($fila['precio'], 0, ',', '.'); ?></td>
                    <td class="acciones">
                        <a class="btn btn-success btn-sm" href="productos.php?editar=<?php echo intval($fila['id']); ?>">Editar</a>
                        <a class="btn btn-danger btn-sm" href="productos.php?eliminar=<?php echo intval($fila['id']); ?>" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- 📄 Botón Generar PDF -->
        <div style="text-align:center;">
            <a href="reporte_productos.php" target="_blank" class="btn-pdf">📄 Generar Reporte PDF</a>
        </div>
    </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('modoBtn');
    const body = document.body;
    const saved = localStorage.getItem('modoTema');
    if (saved === 'oscuro') {
        body.classList.add('dark-mode');
        btn.textContent = '🌞 Modo claro';
    }
    btn.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        if (body.classList.contains('dark-mode')) {
            btn.textContent = '🌞 Modo claro';
            localStorage.setItem('modoTema','oscuro');
        } else {
            btn.textContent = '🌙 Modo oscuro';
            localStorage.setItem('modoTema','claro');
        }
    });
});
</script>
</body>
</html>

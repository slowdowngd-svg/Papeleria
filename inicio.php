<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// 🔹 Consultas para estadísticas
$queryProductos = "SELECT COUNT(*) AS totalProductos FROM productos";
$queryUsuarios = "SELECT COUNT(*) AS totalUsuarios FROM usuarios";

$resultProductos = mysqli_query($conexion, $queryProductos);
$resultUsuarios = mysqli_query($conexion, $queryUsuarios);

$totalProductos = mysqli_fetch_assoc($resultProductos)['totalProductos'] ?? 0;
$totalUsuarios = mysqli_fetch_assoc($resultUsuarios)['totalUsuarios'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - Papelería Inventario</title>
  <link rel="icon" href="img/milogo.png">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      min-height: 100vh;
      margin: 0;
      transition: background-color 0.3s, color 0.3s;
      font-family: "Poppins", sans-serif;
    }

    /* 🟩 Barra lateral */
    .sidebar {
      width: 240px;
      background-color: #198754;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: start;
      padding: 25px 20px;
      position: fixed;
      top: 0;
      bottom: 0;
      transition: background-color 0.3s;
    }

    .sidebar img {
      width: 90px;
      margin: 0 auto 15px;
      display: block;
      border-radius: 50%;
    }

    .sidebar h4 {
      text-align: center;
      color: white;
      margin-bottom: 25px;
      font-weight: bold;
    }

    .sidebar a {
      text-decoration: none;
      color: white;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 10px 12px;
      border-radius: 8px;
      transition: background 0.2s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #157347;
    }

    /* 🟦 Contenido principal */
    .main-content {
      margin-left: 260px;
      padding: 30px 40px;
      width: calc(100% - 260px);
      transition: background-color 0.3s, color 0.3s;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .header h1 {
      color: #198754;
      font-weight: bold;
    }

    /* 📊 Tarjetas estadísticas */
    .stats {
      display: flex;
      gap: 25px;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    .stat-card {
      background-color: white;
      flex: 1;
      min-width: 250px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: transform 0.3s, background-color 0.3s, color 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-icon {
      font-size: 2.5rem;
      color: #198754;
    }

    .stat-info h5 {
      font-weight: 700;
      color: #198754;
      margin: 0;
    }

    .stat-info p {
      font-size: 1.8rem;
      font-weight: bold;
      margin: 0;
      color: #333;
    }

    /* 📦 Tarjetas inferiores */
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: background-color 0.3s, color 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      transition: transform 0.3s;
    }

    /* 🌙 Modo oscuro */
    .dark-mode {
      background-color: #121212;
      color: #e4e4e4;
    }
    .dark-mode .sidebar {
      background-color: #0f3d28;
    }
    .dark-mode .main-content {
      background-color: #181818;
      color: #e4e4e4;
    }
    .dark-mode .card, 
    .dark-mode .stat-card {
      background-color: #232323;
      color: #e4e4e4;
      box-shadow: none;
    }
    .dark-mode .header h1 {
      color: #7bf5a4;
    }

    /* 🌗 Botón modo oscuro */
    #modoBtn {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #198754;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
      z-index: 1000;
    }

    #modoBtn:hover {
      background-color: #157347;
    }
  </style>
</head>
<body>

<!-- 🌗 Botón de modo claro/oscuro -->
<button id="modoBtn">🌙 Modo oscuro</button>

<!-- 🟩 Barra lateral -->
<div class="sidebar">
  <img src="img/milogo.png" alt="Logo">
  <h4>Menú Principal</h4>
  <a href="inicio.php" class="active"><i class="bi bi-house-door-fill"></i> Inicio</a>
  <a href="productos.php"><i class="bi bi-box-seam"></i> Productos</a>
  <a href="usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
  <a href="nosotros.php"><i class="bi bi-person-badge-fill"></i> Nosotros</a>
  <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

<!-- 🟦 Contenido principal -->
<div class="main-content">
  <div class="header">
    <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?> 👋</h1>
  </div>

  <!-- 📊 Sección de estadísticas -->
  <div class="stats">
    <div class="stat-card">
      <div class="stat-info">
        <h5>Total de Productos</h5>
        <p><?php echo $totalProductos; ?></p>
      </div>
      <i class="bi bi-box-seam stat-icon"></i>
    </div>

    <div class="stat-card">
      <div class="stat-info">
        <h5>Total de Usuarios</h5>
        <p><?php echo $totalUsuarios; ?></p>
      </div>
      <i class="bi bi-people-fill stat-icon"></i>
    </div>

    <div class="stat-card">
      <div class="stat-info">
        <h5>Fecha Actual</h5>
        <p><?php echo date("d/m/Y"); ?></p>
      </div>
      <i class="bi bi-calendar-date stat-icon"></i>
    </div>
  </div>

  <!-- 🔹 Sección de acceso rápido -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-4">
        <h4><i class="bi bi-box-seam"></i> Gestión de Productos</h4>
        <p>Agrega, edita o elimina productos de la papelería fácilmente.</p>
        <a href="productos.php" class="btn btn-success">Ir a productos</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-4">
        <h4><i class="bi bi-people-fill"></i> Gestión de Usuarios</h4>
        <p>Consulta y administra los usuarios registrados en el sistema.</p>
        <a href="usuarios.php" class="btn btn-success">Ir a usuarios</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-4">
        <h4><i class="bi bi-info-circle-fill"></i> Sobre Nosotros</h4>
        <p>Conoce más acerca de los creadores de este proyecto.</p>
        <a href="nosotros.php" class="btn btn-success">Ver más</a>
      </div>
    </div>
  </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
  // 🌙 Alternar modo claro / oscuro
  document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("modoBtn");
    const body = document.body;

    const savedMode = localStorage.getItem("modoTema");
    if (savedMode === "oscuro") {
      body.classList.add("dark-mode");
      btn.textContent = "🌞 Modo claro";
    }

    btn.addEventListener("click", () => {
      body.classList.toggle("dark-mode");

      if (body.classList.contains("dark-mode")) {
        btn.textContent = "🌞 Modo claro";
        localStorage.setItem("modoTema", "oscuro");
      } else {
        btn.textContent = "🌙 Modo oscuro";
        localStorage.setItem("modoTema", "claro");
      }
    });
  });
</script>

</body>
</html>

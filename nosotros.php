<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nosotros - Papelería Inventario</title>
<link rel="icon" href="img/milogo.png">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
body{ margin:0; font-family:Arial, sans-serif; background:#f8f9fa; transition:background .3s,color .3s; }
.sidebar{ width:240px; background:#198754; color:#fff; position:fixed; top:0; bottom:0; padding:25px 18px; display:flex; flex-direction:column; align-items:center; }
.sidebar img{ width:90px; height:90px; border-radius:50%; margin-bottom:12px; object-fit:cover;}
.sidebar h4{ color:#fff; margin-bottom:18px; text-align:center; }
.sidebar a{ color:#fff; text-decoration:none; width:100%; padding:10px 12px; border-radius:8px; display:flex; gap:10px; margin-bottom:6px; font-weight:600; align-items:center; }
.sidebar a:hover, .sidebar a.active{ background:#157347; }
.main-content{ margin-left:260px; padding:28px; }
.header h1{ color:#198754; margin:0 0 18px 0; }
.section{ background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.06); margin-bottom:18px; }
.team{ display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:18px; }
.card-member{ text-align:center; padding:18px; border-radius:10px; background:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.06); }
.card-member img{ width:100px; height:100px; border-radius:50%; object-fit:cover; margin-bottom:10px; }

/* dark */
.dark-mode{ background:#121212; color:#e6e6e6; }
.dark-mode .sidebar{ background:#0f3d28; }
.dark-mode .section, .dark-mode .card-member{ background:#222; color:#e6e6e6; box-shadow:none; }
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
    <a href="usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="nosotros.php" class="active"><i class="bi bi-person-badge-fill"></i> Nosotros</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

<div class="main-content">
    <div class="header"><h1>Sobre Nosotros</h1></div>

    <div class="section">
        <h4>¿Qué es Papelería Inventario?</h4>
        <p>Es un sistema desarrollado para gestionar productos, usuarios y reportes de una papelería. Permite controlar inventario y administrar accesos.</p>
    </div>

    <div class="section">
        <h4>Equipo</h4>
        <div class="team">
            <div class="card-member">
                <img src="img/josesito.jpg" alt="Jose">
                <h5>José Navarro</h5>
                <p>Desarrollador Backend</p>
            </div>
            <div class="card-member">
                <img src="img/marca andres.jpg" alt="Andres">
                <h5>Andrés Simanca</h5>
                <p>Diseñador Frontend</p>
            </div>
            <div class="card-member">
                <img src="img/danisito.jpg" alt="Danny">
                <h5>Danny Arciniegas</h5>
                <p>DBA / QA</p>
            </div>
            <div class="card-member">
                <img src="img/ritmic.png" alt="JoseV">
                <h5>José Varón</h5>
                <p>Documentación y Pruebas</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h4>Contacto</h4>
        <p>contacto@papeleriainventario.com — +57 320 000 0000 — Bogotá, Colombia</p>
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
        if(body.classList.contains('dark-mode')){ btn.textContent='🌞 Modo claro'; localStorage.setItem('modoTema','oscuro'); }
        else { btn.textContent='🌙 Modo oscuro'; localStorage.setItem('modoTema','claro'); }
    });
});
</script>
</body>
</html>

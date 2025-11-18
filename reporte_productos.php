<?php
include("conexion.php");

// Obtener los productos
$resultado = mysqli_query($conexion, "SELECT * FROM productos ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reporte de Productos</title>
<link rel="icon" href="img/milogo.png">
<link rel="stylesheet" href="css/bootstrap.min.css">
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f8f9fa;
        color: #212529;
        padding: 40px;
        transition: background-color 0.3s, color 0.3s;
    }

    .container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 900px;
        margin: 0 auto;
    }

    h1 {
        text-align: center;
        color: #198754;
        margin-bottom: 25px;
        font-weight: 700;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 10px;
    }

    th {
        background-color: #198754;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .botones {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    button, a {
        background-color: #198754;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.3s;
    }

    button:hover, a:hover {
        background-color: #157347;
    }

    footer {
        text-align: center;
        font-size: 0.9rem;
        color: #777;
        margin-top: 40px;
    }

    /* 🌙 Modo oscuro */
    .dark-mode {
        background-color: #121212;
        color: white;
    }

    .dark-mode .container {
        background-color: #1f1f1f;
    }

    .dark-mode th {
        background-color: #157347;
    }

    .dark-mode tr:nth-child(even) {
        background-color: #2a2a2a;
    }

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
    }

    #modoBtn:hover {
        background-color: #157347;
    }

    @media print {
        #modoBtn, .botones {
            display: none;
        }
        body {
            background: white;
            color: black;
        }
    }
</style>
</head>
<body>

<button id="modoBtn">🌙 Modo oscuro</button>

<div class="container">
    <h1>📋 Reporte de Productos</h1>

    <div class="botones">
        <button onclick="window.print()">🖨️ Imprimir</button>
        <a href="#" onclick="descargarPDF()">📄 Descargar PDF</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?= $fila['id'] ?></td>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                <td><?= htmlspecialchars($fila['categoria']) ?></td>
                <td>$<?= number_format($fila['precio'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <footer>
        <p>📅 Generado automáticamente el <?= date("d/m/Y H:i") ?> — Papelería Inventario</p>
    </footer>
</div>

<script>
    // 🌙 Alternar modo oscuro / claro
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

    // 📄 Descargar PDF desde impresión
    function descargarPDF() {
        window.print();
    }
</script>

</body>
</html>

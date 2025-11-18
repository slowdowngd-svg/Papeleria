<?php
include("conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Verificar si el usuario ya existe
    $checkUser = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    $resultCheck = mysqli_query($conexion, $checkUser);

    if (mysqli_num_rows($resultCheck) > 0) {
        $error = "El usuario ya existe. Intenta con otro.";
    } else {
        // Insertar el nuevo usuario
        $query = "INSERT INTO usuarios (usuario, clave) VALUES ('$usuario', '$clave')";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado) {
            echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location='index.php';</script>";
            exit();
        } else {
            $error = "Error al registrar el usuario. Inténtalo nuevamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de usuario</title>
    <link rel="icon" href="img/milogo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        body {
            background-image: url('img/azul.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            padding: 30px 40px;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .login-card img {
            width: 110px;
            height: 110px;
            margin-bottom: 15px;
        }

        .login-card h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #198754;
        }

        .login-card h2 {
            font-size: 1.1rem;
            font-weight: 400;
            color: #6c757d;
            margin-bottom: 25px;
        }

        .btn-success {
            width: 100%;
            font-size: 1rem;
            padding: 10px;
        }

        .login-card a {
            text-decoration: none;
            color: #198754;
        }

        .login-card a:hover {
            text-decoration: underline;
        }

        /* 🌙 Modo oscuro */
        .dark-mode {
            background-color: #121212 !important;
            color: white !important;
        }

        .dark-mode .login-card {
            background-color: rgba(30, 30, 30, 0.95);
            color: white;
        }

        .dark-mode .login-card h1 {
            color: #7bf5a4;
        }

        .dark-mode .login-card a {
            color: #7bf5a4;
        }

        /* 🔘 Botón de cambio de modo */
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

        footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: white;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- 🌗 Botón de modo claro/oscuro -->
    <button id="modoBtn">🌙 Modo oscuro</button>

    <main>
        <div class="login-card">
            <img src="img/milogo.png" alt="Logo" class="rounded-circle">
            <h1>PAPELERÍA INVENTARIO</h1>
            <h2>Registro de usuario</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="usuario" required id="floatingUser" placeholder="Nombre de usuario">
                    <label for="floatingUser">Usuario</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="clave" required id="floatingPassword" placeholder="Contraseña">
                    <label for="floatingPassword">Contraseña</label>
                </div>

                <button class="btn btn-success" type="submit">Registrar</button>

                <p class="text-center mt-3">
                    <a href="index.php">¿Ya tienes cuenta? Inicia sesión aquí</a>
                </p>
            </form>
        </div>
    </main>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // 🌙 Alternar modo oscuro / claro
        document.addEventListener("DOMContentLoaded", function () {
            const btn = document.getElementById("modoBtn");
            const body = document.body;

            // Verificar modo guardado
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

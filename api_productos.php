<?php
// api_productos.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once __DIR__ . '/../conexion.php'; // ajusta la ruta si es necesario

$method = $_SERVER['REQUEST_METHOD'];

// Leer entrada JSON (POST/PUT)
$input = json_decode(file_get_contents('php://input'), true);

// Helper para enviar respuesta JSON
function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'OPTIONS') {
    respond(['message' => 'ok']);
}

try {
    if ($method === 'GET') {
        // GET /api_productos.php           -> lista todos
        // GET /api_productos.php?id=5      -> devuelve 1
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conexion->prepare("SELECT id, nombre, descripcion, categoria, precio FROM productos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row) respond($row);
            else respond(['error' => 'Producto no encontrado'], 404);
        } else {
            $result = $conexion->query("SELECT id, nombre, descripcion, categoria, precio FROM productos ORDER BY id DESC");
            $data = [];
            while ($r = $result->fetch_assoc()) $data[] = $r;
            respond($data);
        }
    }

    if ($method === 'POST') {
        // Crear nuevo producto
        // Body JSON: { "nombre":"xxx", "descripcion":"...", "categoria":"...", "precio": 12345 }
        if (!isset($input['nombre']) || !isset($input['precio'])) {
            respond(['error' => 'Campos obligatorios: nombre, precio'], 400);
        }
        $nombre = trim($input['nombre']);
        $descripcion = isset($input['descripcion']) ? trim($input['descripcion']) : '';
        $categoria = isset($input['categoria']) ? trim($input['categoria']) : '';
        $precio = floatval($input['precio']);

        $stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, categoria, precio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $nombre, $descripcion, $categoria, $precio);
        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            respond(['mensaje' => 'Producto creado', 'id' => $id], 201);
        } else {
            respond(['error' => 'No se pudo crear producto'], 500);
        }
    }

    if ($method === 'PUT') {
        // Actualizar producto
        // Body JSON: { "id": 5, "nombre":"xxx", "descripcion":"...", "categoria":"...", "precio": 123 }
        if (!isset($input['id'])) respond(['error' => 'Falta id'], 400);
        $id = intval($input['id']);
        // Campos opcionales
        $nombre = isset($input['nombre']) ? trim($input['nombre']) : null;
        $descripcion = isset($input['descripcion']) ? trim($input['descripcion']) : null;
        $categoria = isset($input['categoria']) ? trim($input['categoria']) : null;
        $precio = isset($input['precio']) ? floatval($input['precio']) : null;

        // Construir query dinámicamente
        $fields = [];
        $types = '';
        $values = [];
        if ($nombre !== null) { $fields[] = "nombre = ?"; $types .= 's'; $values[] = $nombre; }
        if ($descripcion !== null) { $fields[] = "descripcion = ?"; $types .= 's'; $values[] = $descripcion; }
        if ($categoria !== null) { $fields[] = "categoria = ?"; $types .= 's'; $values[] = $categoria; }
        if ($precio !== null) { $fields[] = "precio = ?"; $types .= 'd'; $values[] = $precio; }

        if (empty($fields)) respond(['error' => 'No hay campos para actualizar'], 400);

        $sql = "UPDATE productos SET " . implode(", ", $fields) . " WHERE id = ?";
        $types .= 'i';
        $values[] = $id;

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) respond(['mensaje' => 'Producto actualizado']);
            else respond(['mensaje' => 'No hubo cambios o producto no existe'], 200);
        } else {
            respond(['error' => 'No se pudo actualizar'], 500);
        }
    }

    if ($method === 'DELETE') {
        // DELETE con id por query string o JSON
        // DELETE /api_productos.php?id=5
        if (isset($_GET['id'])) $id = intval($_GET['id']);
        else if (isset($input['id'])) $id = intval($input['id']);
        else respond(['error' => 'Falta id'], 400);

        $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) respond(['mensaje' => 'Producto eliminado']);
            else respond(['error' => 'Producto no encontrado'], 404);
        } else {
            respond(['error' => 'No se pudo eliminar'], 500);
        }
    }

    // Si llega aquí: método no soportado
    respond(['error' => 'Método no soportado'], 405);

} catch (Exception $e) {
    respond(['error' => 'Excepción: '.$e->getMessage()], 500);
}

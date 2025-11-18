<?php
$base = "http://localhost/proyecto/apis/api_productos.php";

function call($method, $url, $data=null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($data) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return [$info['http_code'], $res];
}

echo "1) Listar productos (GET)\n";
list($code, $res) = call('GET', $base);
echo "HTTP $code\n$res\n\n";

echo "2) Crear producto (POST)\n";
list($code, $res) = call('POST', $base, ['nombre'=>'Test API','descripcion'=>'Prueba','categoria'=>'Test','precio'=>1000]);
echo "HTTP $code\n$res\n\n";
$data = json_decode($res, true);
$id = $data['id'] ?? null;

if ($id) {
    echo "3) Actualizar producto (PUT)\n";
    list($code, $res) = call('PUT', $base, ['id'=>$id,'precio'=>1500]);
    echo "HTTP $code\n$res\n\n";

    echo "4) Eliminar producto (DELETE)\n";
    list($code, $res) = call('DELETE', $base . '?id=' . $id);
    echo "HTTP $code\n$res\n\n";
} else {
    echo "No se obtuvo id del POST, revisa la respuesta anterior.\n";
}

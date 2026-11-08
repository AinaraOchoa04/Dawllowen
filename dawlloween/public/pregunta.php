<?php
include_once 'VarGlobales.php';

header('Content-Type: application/json; charset=utf-8');
$usuario = trim($_GET['usuario'] ?? '');
$pregunta = '';

foreach ($_SESSION['mimatriz'] as $fila) {
    if (normalizarTexto($fila['Usuario']) === normalizarTexto($usuario)) {
        $pregunta = $fila['Pregunta'];
        break;
    }
}

echo json_encode(['pregunta' => $pregunta], JSON_UNESCAPED_UNICODE);

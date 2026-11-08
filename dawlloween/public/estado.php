<?php
include_once 'VarGlobales.php';

header('Content-Type: application/json; charset=utf-8');
$usuario = $_SESSION['usuarioLogueado'];
$logueado = is_array($usuario);

echo json_encode([
    'logueado' => $logueado,
    'usuario' => $logueado ? $usuario['Usuario'] : '',
    'perfil' => $logueado ? $usuario['Perfil'] : '',
], JSON_UNESCAPED_UNICODE);

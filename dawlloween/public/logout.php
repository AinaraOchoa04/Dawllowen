<?php
include_once 'VarGlobales.php';

// No se destruye la sesión: la matriz tiene que seguir disponible.
foreach ($_SESSION['mimatriz'] as $indice => $fila) {
    $_SESSION['mimatriz'][$indice]['UsuarioActual'] = 'no';
}
$_SESSION['usuarioLogueado'] = null;
redirect('index.html');

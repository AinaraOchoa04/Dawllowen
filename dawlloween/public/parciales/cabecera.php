<?php
include_once __DIR__ . '/../VarGlobales.php';
if (!isset($titulo)) {
    $titulo = 'Mansión Umbría';
}
if (!isset($pagina)) {
    $pagina = '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($titulo); ?> · Mansión Umbría</title>
    <link rel="icon" href="img/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="vendor/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <a class="saltar" href="#contenido">Saltar al contenido</a>
    <header class="cabecera">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand marca" href="index.html">Mansión Umbría</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir el menú">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="menuPrincipal">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="<?php echo clase_nav('inicio'); ?>" href="index.html"<?php echo aria_nav('inicio'); ?>>Inicio</a></li>
                        <li class="nav-item"><a class="<?php echo clase_nav('recetas'); ?>" href="recetas.html"<?php echo aria_nav('recetas'); ?>>Recetas</a></li>
                        <li class="nav-item"><a class="<?php echo clase_nav('juego'); ?>" href="juego.html"<?php echo aria_nav('juego'); ?>>La cripta</a></li>
                        <li class="nav-item"><a class="<?php echo clase_nav('grupo'); ?>" href="aboutus.html"<?php echo aria_nav('grupo'); ?>>El grupo</a></li>
                        <li class="nav-item"><a class="<?php echo clase_nav('correo'); ?>" href="correo.php"<?php echo aria_nav('correo'); ?>>Escribe a Jack</a></li>
                        <li class="nav-item">
                            <?php if (is_array($_SESSION['usuarioLogueado'])): ?>
                                <a class="nav-link" id="enlace-cuenta" href="logout.php">Salir (<?php echo h($_SESSION['usuarioLogueado']['Usuario']); ?>)</a>
                            <?php else: ?>
                                <a class="<?php echo clase_nav('login'); ?>" id="enlace-cuenta" href="login.php"<?php echo aria_nav('login'); ?>>Entrar</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main id="contenido" class="container pagina">

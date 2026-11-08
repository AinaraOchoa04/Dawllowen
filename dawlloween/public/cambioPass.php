<?php
include_once 'VarGlobales.php';

$pagina = 'login';
$titulo = 'Cambiar contraseña';
$error = '';
$usuario = '';
$scripts = ['js/cuenta.js'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $respuesta = trim($_POST['respuesta'] ?? '');
    $nueva = $_POST['contrasena'] ?? '';
    $nueva2 = $_POST['contrasena2'] ?? '';

    if ($usuario === '' || $respuesta === '' || $nueva === '') {
        $error = 'Hacen falta el usuario, la respuesta de seguridad y la contraseña nueva.';
    } elseif (strlen($nueva) < 4) {
        $error = 'La contraseña nueva necesita al menos 4 caracteres.';
    } elseif ($nueva !== $nueva2) {
        $error = 'Las dos contraseñas nuevas no coinciden.';
    } else {
        $resultado = cambioContrasena($usuario, $respuesta, $nueva);
        if ($resultado === true) {
            redirect('login.php?pass=1');
        }
        $error = $resultado;
    }
}

include 'parciales/cabecera.php';
?>
        <p class="sobre-titulo">Entorno servidor</p>
        <h1>Cambiar contraseña</h1>
        <p class="bajada">Escribe el usuario. Si existe, debajo aparece su pregunta. La respuesta no distingue mayúsculas.</p>

        <div class="dos-columnas">
            <form class="formulario panel" method="post" action="cambioPass.php">
                <?php if ($error !== ''): ?>
                    <p class="alerta" role="alert"><?php echo h($error); ?></p>
                <?php endif; ?>

                <label for="usuario">Usuario
                    <input id="usuario" name="usuario" type="text" autocomplete="username" required maxlength="16" value="<?php echo h($usuario); ?>">
                </label>
                <p id="texto-pregunta" aria-live="polite"></p>
                <label for="respuesta">Respuesta de seguridad
                    <input id="respuesta" name="respuesta" type="text" required maxlength="40">
                </label>
                <label for="contrasena">Contraseña nueva
                    <input id="contrasena" name="contrasena" type="password" autocomplete="new-password" required minlength="4">
                </label>
                <label for="contrasena2">Repite la contraseña nueva
                    <input id="contrasena2" name="contrasena2" type="password" autocomplete="new-password" required minlength="4">
                </label>
                <button class="btn-lapida" type="submit">Guardar contraseña</button>
                <p><a href="login.php">Volver a entrar</a></p>
            </form>

            <aside class="panel">
                <h2>Respuestas de prueba</h2>
                <div class="tabla-caja">
                    <table class="tabla-pruebas">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Pregunta</th>
                                <th>Respuesta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>jack</td>
                                <td>¿Cómo se llama la colina de Halloween?</td>
                                <td>espiral</td>
                            </tr>
                            <tr>
                                <td>sally</td>
                                <td>¿De qué está hecha Sally?</td>
                                <td>retazos</td>
                            </tr>
                            <tr>
                                <td>cero</td>
                                <td>¿Qué le falta a Zero en la cara?</td>
                                <td>nariz</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </aside>
        </div>
<?php include 'parciales/pie.php'; ?>

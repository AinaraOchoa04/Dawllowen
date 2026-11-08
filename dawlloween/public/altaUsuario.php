<?php
include_once 'VarGlobales.php';

$pagina = 'login';
$titulo = 'Registrar usuario';
$error = '';
$usuario = '';
$email = '';
$pregunta = '';
$respuesta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $contrasena2 = $_POST['contrasena2'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $pregunta = trim($_POST['pregunta'] ?? '');
    $respuesta = trim($_POST['respuesta'] ?? '');

    if ($usuario === '' || $contrasena === '' || $email === '' || $pregunta === '' || $respuesta === '') {
        $error = 'Rellena todos los campos. El perfil no se pide: la cuenta queda como user.';
    } elseif (!usuarioValido($usuario)) {
        $error = 'El usuario debe tener de 3 a 16 letras, números o guion bajo, sin espacios.';
    } elseif (strlen($contrasena) < 4) {
        $error = 'La contraseña necesita al menos 4 caracteres.';
    } elseif ($contrasena !== $contrasena2) {
        $error = 'Las dos contraseñas no coinciden.';
    } elseif (!correoValido($email)) {
        $error = 'El correo no tiene una forma válida.';
    } elseif (strlen($pregunta) < 5 || strlen($respuesta) < 2) {
        $error = 'Escribe una pregunta de seguridad y una respuesta que puedas recordar.';
    } else {
        // El perfil no viene del formulario. AltaUsuario lo fuerza a "user".
        $resultado = AltaUsuario([
            'Usuario' => $usuario,
            'Contraseña' => $contrasena,
            'Email' => $email,
            'Perfil' => 'user',
            'Pregunta' => $pregunta,
            'Respuesta' => $respuesta,
            'UsuarioActual' => 'no',
        ]);

        if ($resultado === true) {
            redirect('login.php?alta=1');
        }
        $error = $resultado;
    }
}

include 'parciales/cabecera.php';
?>
        <p class="sobre-titulo">Entorno servidor</p>
        <h1>Registrar usuario</h1>
        <p class="bajada">La cuenta se guarda en la sesión y en un fichero fuera de la carpeta pública. No hace falta elegir perfil.</p>

        <form class="formulario panel" method="post" action="altaUsuario.php">
            <?php if ($error !== ''): ?>
                <p class="alerta" role="alert"><?php echo h($error); ?></p>
            <?php endif; ?>

            <label for="usuario">Usuario
                <input id="usuario" name="usuario" type="text" autocomplete="username" required maxlength="16" value="<?php echo h($usuario); ?>">
            </label>
            <label for="contrasena">Contraseña
                <input id="contrasena" name="contrasena" type="password" autocomplete="new-password" required minlength="4">
            </label>
            <label for="contrasena2">Repite la contraseña
                <input id="contrasena2" name="contrasena2" type="password" autocomplete="new-password" required minlength="4">
            </label>
            <label for="email">Email
                <input id="email" name="email" type="email" autocomplete="email" required maxlength="80" value="<?php echo h($email); ?>">
            </label>
            <label for="pregunta">Pregunta de seguridad
                <input id="pregunta" name="pregunta" type="text" required maxlength="120" value="<?php echo h($pregunta); ?>">
            </label>
            <label for="respuesta">Respuesta
                <input id="respuesta" name="respuesta" type="text" required maxlength="40" value="<?php echo h($respuesta); ?>">
            </label>
            <button class="btn-lapida" type="submit">Crear cuenta</button>
            <p><a href="login.php">Volver a entrar</a></p>
        </form>
<?php include 'parciales/pie.php'; ?>

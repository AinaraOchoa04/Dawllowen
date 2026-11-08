<?php
include_once 'VarGlobales.php';

function guardarCopia($nombre, $email, $asunto, $mensaje) {
    $dir = dirname(__DIR__) . '/buzon';
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        return false;
    }
    $bloque = "----\n";
    $bloque .= 'Fecha: ' . date('Y-m-d H:i:s') . "\n";
    $bloque .= 'De: ' . $nombre . ' <' . $email . ">\n";
    $bloque .= 'Asunto: ' . $asunto . "\n";
    $bloque .= $mensaje . "\n\n";
    return file_put_contents($dir . '/cartas.txt', $bloque, FILE_APPEND | LOCK_EX) !== false;
}

$pagina = 'correo';
$titulo = 'Escribe a Jack';
$error = '';
$ok = '';
$asunto = '';
$mensaje = '';
$logueado = $_SESSION['usuarioLogueado'];
$haySesion = is_array($logueado) && !empty($logueado['Email']);
$email = $haySesion ? $logueado['Email'] : '';

if ($haySesion && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $asunto = trim($_POST['asunto'] ?? '');
    $emailForm = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($asunto === '' || $emailForm === '' || $mensaje === '') {
        $error = 'Rellena el asunto, el correo y el mensaje.';
    } elseif (!correoValido($emailForm)) {
        $error = 'El correo no tiene una forma válida.';
    } elseif (normalizarTexto($emailForm) !== normalizarTexto($logueado['Email'])) {
        $error = 'El correo tiene que ser el de la cuenta con la que has entrado.';
    } elseif (strlen($asunto) > 120) {
        $error = 'El asunto es demasiado largo.';
    } elseif (strlen($mensaje) < 10) {
        $error = 'Cuenta la pesadilla con un poco más de detalle.';
    } elseif (strlen($mensaje) > 2000) {
        $error = 'El mensaje pasa de 2000 caracteres.';
    } else {
        $asuntoLimpio = str_replace(["\r", "\n"], '', $asunto);
        $from = str_replace(["\r", "\n"], '', $logueado['Email']);
        $cuerpo = "Pesadilla enviada desde la Mansión Umbría\n";
        $cuerpo .= 'Usuario: ' . $logueado['Usuario'] . "\n\n";
        $cuerpo .= $mensaje . "\n";

        $cabeceras = 'From: ' . $from . "\r\n";
        $cabeceras .= 'Reply-To: ' . $from . "\r\n";
        $cabeceras .= "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $copia = guardarCopia($logueado['Usuario'], $from, $asuntoLimpio, $mensaje);
        // mail() avisa si XAMPP no tiene SMTP. El @ evita el warning en pantalla.
        $enviado = @mail(CORREO_JACK, $asuntoLimpio, $cuerpo, $cabeceras);

        if ($enviado) {
            $ok = 'Jack ha recibido el correo y también quedó una copia en el buzón de la mansión.';
            $asunto = '';
            $mensaje = '';
        } elseif ($copia) {
            $ok = 'La carta quedó guardada en el buzón. El servidor todavía no pudo entregarla a Yahoo: revisa la guía de despliegue.';
            $asunto = '';
            $mensaje = '';
        } else {
            $error = 'No se pudo guardar la carta ni enviarla. Revisa los permisos de la carpeta buzon.';
        }
    }
}

include 'parciales/cabecera.php';
?>
        <p class="sobre-titulo">Entorno servidor</p>
        <h1>Escribe a Jack</h1>

        <?php if (!$haySesion): ?>
            <p class="alerta" role="alert">Para contar una pesadilla tienes que entrar. El remitente del correo es el email de tu cuenta.</p>
            <p><a class="btn-lapida" href="login.php">Entrar</a></p>
        <?php else: ?>
            <p class="bajada">Se envía a <?php echo h(CORREO_JACK); ?>. El FROM es <?php echo h($logueado['Email']); ?>, el correo de <?php echo h($logueado['Usuario']); ?>.</p>

            <form class="formulario panel" method="post" action="correo.php">
                <?php if ($error !== ''): ?>
                    <p class="alerta" role="alert"><?php echo h($error); ?></p>
                <?php endif; ?>
                <?php if ($ok !== ''): ?>
                    <p class="alerta-ok" role="status"><?php echo h($ok); ?></p>
                <?php endif; ?>

                <label for="asunto">Asunto
                    <input id="asunto" name="asunto" type="text" required maxlength="120" value="<?php echo h($asunto); ?>">
                </label>
                <label for="email">Email
                    <input id="email" name="email" type="email" required maxlength="80" value="<?php echo h($email); ?>">
                </label>
                <label for="mensaje">Tu peor pesadilla
                    <textarea id="mensaje" name="mensaje" required maxlength="2000"><?php echo h($mensaje); ?></textarea>
                </label>
                <button class="btn-lapida" type="submit">Enviar a Jack</button>
            </form>
        <?php endif; ?>
<?php include 'parciales/pie.php'; ?>

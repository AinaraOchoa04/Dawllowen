<?php
include_once 'VarGlobales.php';

$pagina = 'login';
$titulo = 'Entrar';
$error = '';
$ok = '';
$usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($usuario === '' || $contrasena === '') {
        $error = 'Escribe el usuario y la contraseña.';
    } else {
        $acierto = null;
        foreach ($_SESSION['mimatriz'] as $fila) {
            $mismoUsuario = normalizarTexto($fila['Usuario']) === normalizarTexto($usuario);
            $mismaClave = $fila['Contraseña'] === $contrasena;
            if ($mismoUsuario && $mismaClave) {
                $acierto = $fila;
                break;
            }
        }

        if ($acierto !== null) {
            currentUser($acierto);
            redirect('index.html');
        }
        $error = 'El nombre o la contraseña es incorrecta.';
    }
} else {
    if (isset($_GET['alta'])) {
        $ok = 'Cuenta creada. Ya puedes entrar.';
    }
    if (isset($_GET['pass'])) {
        $ok = 'Contraseña cambiada. Entra con la nueva.';
    }
}

include 'parciales/cabecera.php';
?>
        <p class="sobre-titulo">Entorno servidor</p>
        <h1>Entrar</h1>
        <p class="bajada">Con la cuenta se abre el correo a Jack. Las recetas y la cripta se pueden mirar sin entrar.</p>

        <?php if (is_array($_SESSION['usuarioLogueado'])): ?>
            <p class="alerta-ok">Sesión abierta como <?php echo h($_SESSION['usuarioLogueado']['Usuario']); ?>. Si entras con otra cuenta, se cambia la sesión.</p>
        <?php endif; ?>

        <div class="dos-columnas">
            <form class="formulario panel" method="post" action="login.php">
                <?php if ($error !== ''): ?>
                    <p class="alerta" role="alert"><?php echo h($error); ?></p>
                <?php endif; ?>
                <?php if ($ok !== ''): ?>
                    <p class="alerta-ok" role="status"><?php echo h($ok); ?></p>
                <?php endif; ?>

                <label for="usuario">Usuario
                    <input id="usuario" name="usuario" type="text" autocomplete="username" required maxlength="16" value="<?php echo h($usuario); ?>">
                </label>
                <label for="contrasena">Contraseña
                    <input id="contrasena" name="contrasena" type="password" autocomplete="current-password" required>
                </label>
                <button class="btn-lapida" type="submit">Entrar</button>
                <p><a href="altaUsuario.php">Registrar usuario</a></p>
                <p><a href="cambioPass.php">Cambiar contraseña</a></p>
            </form>

            <aside class="panel">
                <h2>Cuentas de prueba</h2>
                <p>Son las iniciales. Si las cambias, estas ya no valen.</p>
                <div class="tabla-caja">
                    <table class="tabla-pruebas">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Contraseña</th>
                                <th>Perfil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>jack</td>
                                <td>calabaza</td>
                                <td>admin</td>
                            </tr>
                            <tr>
                                <td>sally</td>
                                <td>retazos</td>
                                <td>user</td>
                            </tr>
                            <tr>
                                <td>cero</td>
                                <td>nariz</td>
                                <td>user</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </aside>
        </div>
<?php include 'parciales/pie.php'; ?>

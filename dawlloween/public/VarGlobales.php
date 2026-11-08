<?php
/**
 * VarGlobales.php
 *
 * Guarda la matriz de usuarios y quién ha entrado.
 * Todo vive en $_SESSION para que login, alta, cambio de
 * contraseña y correo compartan los mismos datos.
 *
 * Las contraseñas van en claro porque la práctica pide
 * compararlas directamente con lo que llega del formulario.
 * No haríamos esto en una web real.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cuenta de Jack. Cámbiala por el Yahoo del grupo antes de probar el envío.
define('CORREO_JACK', 'jack.skellington@yahoo.com');

// Copia de la matriz fuera de public/, por si se cierra el navegador.
define('RUTA_USUARIOS', dirname(__DIR__) . '/datos/usuarios.json');

/**
 * Usuarios de prueba. La columna UsuarioActual marca quién
 * tiene la sesión abierta: "si" o "no".
 * Respuesta no sale en el enunciado con ese nombre, pero hace
 * falta para comprobar la pregunta de seguridad.
 */
function usuariosIniciales() {
    return [
        [
            'Usuario' => 'jack',
            'Contraseña' => 'calabaza',
            'Email' => 'jack@mansionumbria.dev',
            'Perfil' => 'admin',
            'Pregunta' => '¿Cómo se llama la colina de Halloween?',
            'Respuesta' => 'espiral',
            'UsuarioActual' => 'no',
        ],
        [
            'Usuario' => 'sally',
            'Contraseña' => 'retazos',
            'Email' => 'sally@mansionumbria.dev',
            'Perfil' => 'user',
            'Pregunta' => '¿De qué está hecha Sally?',
            'Respuesta' => 'retazos',
            'UsuarioActual' => 'no',
        ],
        [
            'Usuario' => 'cero',
            'Contraseña' => 'nariz',
            'Email' => 'cero@mansionumbria.dev',
            'Perfil' => 'user',
            'Pregunta' => '¿Qué le falta a Zero en la cara?',
            'Respuesta' => 'nariz',
            'UsuarioActual' => 'no',
        ],
    ];
}

function h($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function normalizarTexto($texto) {
    $texto = trim((string) $texto);
    if (function_exists('mb_strtolower')) {
        return mb_strtolower($texto, 'UTF-8');
    }
    return strtolower($texto);
}

function usuarioValido($usuario) {
    return preg_match('/^[a-zA-Z0-9_]{3,16}$/', $usuario) === 1;
}

function correoValido($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function matrizValida($filas) {
    if (!is_array($filas) || count($filas) === 0) {
        return false;
    }
    foreach ($filas as $fila) {
        if (!is_array($fila) || !isset($fila['Usuario'], $fila['Contraseña'], $fila['Email'])) {
            return false;
        }
    }
    return true;
}

function guardarMatriz() {
    $dir = dirname(RUTA_USUARIOS);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        return false;
    }
    $json = json_encode($_SESSION['mimatriz'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }
    return file_put_contents(RUTA_USUARIOS, $json, LOCK_EX) !== false;
}

function cargarMatriz() {
    if (!is_file(RUTA_USUARIOS)) {
        return null;
    }
    $leido = json_decode((string) file_get_contents(RUTA_USUARIOS), true);
    if (!matrizValida($leido)) {
        return null;
    }
    foreach ($leido as $indice => $fila) {
        $leido[$indice]['UsuarioActual'] = 'no';
        if (!isset($leido[$indice]['Perfil'])) {
            $leido[$indice]['Perfil'] = 'user';
        }
        if (!isset($leido[$indice]['Pregunta'])) {
            $leido[$indice]['Pregunta'] = '';
        }
        if (!isset($leido[$indice]['Respuesta'])) {
            $leido[$indice]['Respuesta'] = '';
        }
    }
    return $leido;
}

if (!isset($_SESSION['mimatriz'])) {
    $guardada = cargarMatriz();
    if ($guardada === null) {
        $_SESSION['mimatriz'] = usuariosIniciales();
        guardarMatriz();
    } else {
        $_SESSION['mimatriz'] = $guardada;
    }
}

if (!isset($_SESSION['usuarioLogueado'])) {
    $_SESSION['usuarioLogueado'] = null;
}

function clase_nav($id) {
    global $pagina;
    if (isset($pagina) && $pagina === $id) {
        return 'nav-link activo';
    }
    return 'nav-link';
}

function aria_nav($id) {
    global $pagina;
    if (isset($pagina) && $pagina === $id) {
        return ' aria-current="page"';
    }
    return '';
}

/**
 * AltaUsuario
 * Añade un usuario al final de la matriz. El perfil lo ponemos
 * nosotros: siempre "user", aunque el formulario intente otra cosa.
 */
function AltaUsuario($nuevoUser) {
    foreach ($_SESSION['mimatriz'] as $fila) {
        if (normalizarTexto($fila['Usuario']) === normalizarTexto($nuevoUser['Usuario'])) {
            return 'Ese nombre de usuario ya existe. Elige otro.';
        }
        if (normalizarTexto($fila['Email']) === normalizarTexto($nuevoUser['Email'])) {
            return 'Ese correo ya está registrado.';
        }
    }

    $nuevoUser['Perfil'] = 'user';
    $nuevoUser['UsuarioActual'] = 'no';

    // Enunciado: $usuarios[] = $nuevoUser;
    $_SESSION['mimatriz'][] = $nuevoUser;
    guardarMatriz();
    return true;
}

/**
 * cambioContraseña (enunciado).
 * El nombre va sin eñe para que el fichero no dependa de la codificación.
 * Si el usuario existe y la respuesta coincide, se actualiza la contraseña.
 */
function cambioContrasena($usuario, $respuesta, $nuevaPass) {
    foreach ($_SESSION['mimatriz'] as $indice => $fila) {
        if (normalizarTexto($fila['Usuario']) !== normalizarTexto($usuario)) {
            continue;
        }
        if (normalizarTexto($fila['Respuesta']) !== normalizarTexto($respuesta)) {
            return 'La respuesta de seguridad no es correcta.';
        }
        $_SESSION['mimatriz'][$indice]['Contraseña'] = $nuevaPass;
        guardarMatriz();
        return true;
    }
    return 'No hay ningún usuario con ese nombre.';
}

/**
 * currentUser
 * Marca la fila que ha entrado y guarda nombre, correo y perfil
 * para usarlos luego (el FROM del correo sale de aquí).
 */
function currentUser($datosUsuario) {
    foreach ($_SESSION['mimatriz'] as $indice => $fila) {
        $esEste = normalizarTexto($fila['Usuario']) === normalizarTexto($datosUsuario['Usuario']);
        $_SESSION['mimatriz'][$indice]['UsuarioActual'] = $esEste ? 'si' : 'no';
    }

    $_SESSION['usuarioLogueado'] = [
        'Usuario' => $datosUsuario['Usuario'],
        'Email' => $datosUsuario['Email'],
        'Perfil' => $datosUsuario['Perfil'],
    ];
}

function redirect($url) {
    header('Location: ' . $url);
    die();
}

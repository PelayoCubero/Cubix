<?php
// Ajustes de seguridad del lado servidor. Se incluye al principio de cada página/endpoint
// EN LUGAR de session_start():   require_once __DIR__ . '/modulos/seguridad.php';

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');    // no acepta IDs de sesión inventados
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,                     // el JS no puede leer la cookie de sesión
        'samesite' => 'Lax',                    // no se envía en peticiones POST desde otras webs
    ]);
    session_start();
}

if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');                      // evita que otra web meta Cubix en un iframe
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// ---------- CSRF ----------
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

// Vale el token enviado como campo "csrf" (formularios) o como cabecera X-CSRF-Token (fetch)
function csrf_valido(): bool {
    $enviado = $_POST['csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    return is_string($enviado) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $enviado);
}

function csrf_campo(): string {
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}

// Registra el error real en el log del servidor (no se lo enseñamos al usuario)
function registrar_error(Throwable $e): void {
    error_log('[Cubix] ' . get_class($e) . ': ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
}

<?php
require_once __DIR__ . '/connection/db.php';

// Recruiters go back to the public site; super admins back to the admin login.
$next = is_super_admin() ? 'admin_login.php' : '../index.php';

$_SESSION = [];
if (ini_get('session.use_cookies')) {
  $p = session_get_cookie_params();
  setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();
redirect($next);

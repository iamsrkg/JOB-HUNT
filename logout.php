<?php
require_once __DIR__ . '/connection/db.php';

// End the session completely: data, cookie and ID.
$_SESSION = [];
if (ini_get('session.use_cookies')) {
  $p = session_get_cookie_params();
  setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();
redirect('index.php');

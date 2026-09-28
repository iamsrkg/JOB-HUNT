<?php
// Delete a job-provider account. Super admins only; the link carries a CSRF token.
require_once __DIR__ . '/connection/db.php';
require_admin(true);
require_csrf();

$id = (int) ($_GET['del'] ?? 0);
if ($id === (int) $_SESSION['admin_id']) {
  exit('You cannot delete your own account. <a href="Customers.php">Back</a>');
}
db("DELETE FROM admin_login WHERE id = ?", [$id]);
redirect('Customers.php');

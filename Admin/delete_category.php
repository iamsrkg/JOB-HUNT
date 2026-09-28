<?php
// Delete a job category. Super admins only; the link carries a CSRF token.
require_once __DIR__ . '/connection/db.php';
require_admin(true);
require_csrf();

db("DELETE FROM job_category WHERE id = ?", [(int) ($_GET['del'] ?? 0)]);
redirect('category.php');

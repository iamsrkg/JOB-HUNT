<?php
// Delete a company. Super admins only; the link carries a CSRF token.
require_once __DIR__ . '/connection/db.php';
require_admin(true);
require_csrf();

db("DELETE FROM company WHERE company_id = ?", [(int) ($_GET['del'] ?? 0)]);
redirect('create_company.php');

<?php
// Delete a job. Recruiters can only delete their own; super admins can delete any.
require_once __DIR__ . '/connection/db.php';
require_admin();
require_csrf();

$id = (int) ($_GET['del'] ?? 0);
if (is_super_admin()) {
  db("DELETE FROM all_jobs WHERE job_id = ?", [$id]);
} else {
  db("DELETE FROM all_jobs WHERE job_id = ? AND customer_email = ?", [$id, current_admin_email()]);
}
redirect('job_create.php');

<?php
// Router for PHP's built-in server:  php -S localhost:8000 router.php
// Apache enforces the same rules through the .htaccess files; this mirrors them for local runs.
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$denied = [
    '#^/files/#',                        // resumes: only via Admin/download_resume.php
    '#^/(lib|connection)/#',             // shared code
    '#^/Admin/(connection|email)/#',     // admin code and the mail library
    '#^/profile_img/.+\.(php\d?|phtml|phar)$#i',
    '#/\.#',                             // dotfiles (.git, .htaccess, .env)
    '#\.(sql|md|config)$#i',
];
foreach ($denied as $rule) {
    if (preg_match($rule, $path)) {
        http_response_code(403);
        exit('Forbidden');
    }
}

return false; // let the built-in server handle everything else normally

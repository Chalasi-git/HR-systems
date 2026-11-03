<?php
// Run once if login fails: resets the 3 demo passwords to "password". Delete afterwards.
require __DIR__ . '/config/config.php';
require __DIR__ . '/core/Database.php';
Database::run('UPDATE users SET password_hash=?', [password_hash('password', PASSWORD_DEFAULT)]);
echo 'Demo passwords reset to "password". Delete setup.php now.';

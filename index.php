<?php
// Front controller: index.php?c=<controller>&a=<action>
session_start();
define('ROOT', __DIR__);
require ROOT . '/config/config.php';
require ROOT . '/core/helpers.php';
require ROOT . '/core/Database.php';
require ROOT . '/core/Controller.php';

$allowed = ['auth', 'dashboard', 'employees', 'leaves', 'risk'];
$c = $_GET['c'] ?? 'dashboard';
$a = $_GET['a'] ?? 'index';
if (!in_array($c, $allowed, true) || !preg_match('/^[a-z]+$/i', $a)) {
    http_response_code(404); exit('Page not found');
}
$class = ucfirst($c) . 'Controller';
require ROOT . "/controllers/$class.php";
$ctrl = new $class();
if (!method_exists($ctrl, $a) || !(new ReflectionMethod($ctrl, $a))->isPublic()) {
    http_response_code(404); exit('Page not found');
}
$ctrl->$a();

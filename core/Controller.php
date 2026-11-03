<?php
class Controller {
    protected function view($name, $data = []) {
        extract($data);
        $user = $_SESSION['user'] ?? null;
        require ROOT . '/views/layout/header.php';
        require ROOT . '/views/' . $name . '.php';
        require ROOT . '/views/layout/footer.php';
    }
    protected function redirect($c, $a = 'index', $p = []) { header('Location: ' . url($c, $a, $p)); exit; }
    protected function requireLogin() { if (empty($_SESSION['user'])) $this->redirect('auth', 'login'); }
    protected function requireRole(...$roles) {
        $this->requireLogin();
        if (!in_array($_SESSION['user']['role'], $roles, true)) { http_response_code(403); exit('Access denied.'); }
    }
    protected function checkCsrf() {
        if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(400); exit('Invalid request. Go back and try again.'); }
    }
}

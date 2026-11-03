<?php
class AuthController extends Controller {
    public function login() {
        if (!empty($_SESSION['user'])) $this->redirect('dashboard');
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $u = Database::one('SELECT * FROM users WHERE username = ?', [trim($_POST['username'] ?? '')]);
            if ($u && password_verify($_POST['password'] ?? '', $u['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = ['id' => $u['id'], 'username' => $u['username'], 'role' => $u['role'], 'employee_id' => $u['employee_id']];
                $this->redirect($u['role'] === 'employee' ? 'leaves' : 'dashboard');
            }
            $error = 'Wrong username or password.';
        }
        require ROOT . '/views/auth/login.php';
    }
    public function logout() { session_destroy(); header('Location: ' . url('auth', 'login')); exit; }
}

<?php
require_once ROOT . '/models/Employee.php';
require_once ROOT . '/models/Leave.php';
class LeavesController extends Controller {
    public function __construct() { $this->requireLogin(); }
    private function isHr() { return in_array($_SESSION['user']['role'], ['admin', 'hr'], true); }
    public function index() {
        $status = $_GET['status'] ?? '';
        if (!in_array($status, ['Pending', 'Approved', 'Rejected'], true)) $status = '';
        $own = $this->isHr() ? null : (int)$_SESSION['user']['employee_id'];
        $this->view('leaves/index', ['rows' => Leave::list($own, $status), 'status' => $status, 'isHr' => $this->isHr()]);
    }
    public function form() {
        $errors = []; $in = ['employee_id' => '', 'leave_type' => 'Annual', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'), 'reason' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $in = array_merge($in, array_intersect_key($_POST, $in));
            $emp = $this->isHr() ? (int)$in['employee_id'] : (int)$_SESSION['user']['employee_id'];
            if (!$emp) $errors[] = 'Choose an employee.';
            if (!in_array($in['leave_type'], ['Annual', 'Casual', 'Sick'], true)) $errors[] = 'Choose a leave type.';
            $s = strtotime($in['start_date']); $e = strtotime($in['end_date']);
            if (!$s || !$e || $e < $s) $errors[] = 'End date must be on or after the start date.';
            if (!$errors) {
                Leave::create($emp, $in['leave_type'], $in['start_date'], $in['end_date'], mb_substr(trim($in['reason']), 0, 200));
                flash('Leave request submitted.');
                $this->redirect('leaves');
            }
        }
        $this->view('leaves/form', ['in' => $in, 'errors' => $errors, 'isHr' => $this->isHr(), 'employees' => $this->isHr() ? Employee::active() : []]);
    }
    public function decide() {
        $this->requireRole('admin', 'hr');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('leaves');
        $this->checkCsrf();
        $st = $_POST['decision'] ?? '';
        if (in_array($st, ['Approved', 'Rejected'], true)) { Leave::decide((int)($_POST['id'] ?? 0), $st); flash("Leave $st."); }
        $this->redirect('leaves');
    }
}

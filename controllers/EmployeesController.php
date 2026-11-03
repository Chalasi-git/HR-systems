<?php
require_once ROOT . '/models/Employee.php';
class EmployeesController extends Controller {
    public function __construct() { $this->requireRole('admin', 'hr'); }
    public function index() {
        $q = trim($_GET['q'] ?? ''); $dept = (int)($_GET['dept'] ?? 0); $page = max(1, (int)($_GET['page'] ?? 1));
        [$rows, $total] = Employee::page($q, $dept, $page);
        $this->view('employees/index', ['rows' => $rows, 'total' => $total, 'q' => $q, 'dept' => $dept, 'page' => $page,
                                        'pages' => max(1, (int)ceil($total / Employee::PER)), 'departments' => Employee::departments()]);
    }
    public function form() {
        $id = (int)($_GET['id'] ?? 0);
        $emp = $id ? Employee::find($id) : null;
        if ($id && !$emp) { http_response_code(404); exit('Employee not found'); }
        $data = $emp ?: ['full_name' => '', 'gender' => 'Male', 'age' => 30, 'email' => '', 'department_id' => '', 'job_role' => '',
            'hire_date' => date('Y-m-d'), 'monthly_income' => 100000, 'overtime' => 'No', 'job_satisfaction' => 3, 'work_life_balance' => 3,
            'performance_rating' => 3, 'distance_from_home' => 5, 'absent_days' => 0, 'training_hours' => 0, 'attrition' => 'No'];
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            [$data, $errors] = $this->validate($_POST);
            if (!$errors) {
                Employee::save($data, $id ?: null);
                flash($id ? 'Employee updated.' : 'Employee added.');
                $this->redirect('employees');
            }
        }
        $this->view('employees/form', ['data' => $data, 'errors' => $errors, 'id' => $id, 'departments' => Employee::departments()]);
    }
    public function delete() {
        $this->requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('employees');
        $this->checkCsrf();
        Employee::delete((int)($_POST['id'] ?? 0));
        flash('Employee deleted.');
        $this->redirect('employees');
    }
    private function validate($in) {
        $d = []; $err = [];
        $d['full_name'] = trim($in['full_name'] ?? ''); if ($d['full_name'] === '') $err['full_name'] = 'Enter the full name.';
        $d['gender'] = $in['gender'] ?? ''; if (!in_array($d['gender'], ['Male', 'Female'], true)) $err['gender'] = 'Choose a gender.';
        $d['age'] = (int)($in['age'] ?? 0); if ($d['age'] < 18 || $d['age'] > 65) $err['age'] = 'Age must be 18-65.';
        $d['email'] = trim($in['email'] ?? ''); if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $err['email'] = 'Enter a valid email.';
        $d['department_id'] = (int)($in['department_id'] ?? 0); if ($d['department_id'] < 1) $err['department_id'] = 'Choose a department.';
        $d['job_role'] = trim($in['job_role'] ?? ''); if ($d['job_role'] === '') $err['job_role'] = 'Enter the job role.';
        $d['hire_date'] = $in['hire_date'] ?? ''; $t = strtotime($d['hire_date']);
        if (!$t || $t > time()) $err['hire_date'] = 'Enter a valid past hire date.';
        $d['monthly_income'] = (int)($in['monthly_income'] ?? 0); if ($d['monthly_income'] < 1) $err['monthly_income'] = 'Enter the monthly income.';
        $d['overtime'] = $in['overtime'] ?? ''; if (!in_array($d['overtime'], ['Yes', 'No'], true)) $err['overtime'] = 'Choose Yes or No.';
        foreach (['job_satisfaction', 'work_life_balance', 'performance_rating'] as $k) {
            $d[$k] = (int)($in[$k] ?? 0); if ($d[$k] < 1 || $d[$k] > 4) $err[$k] = 'Choose 1-4.';
        }
        $d['distance_from_home'] = (int)($in['distance_from_home'] ?? 0); if ($d['distance_from_home'] < 0 || $d['distance_from_home'] > 200) $err['distance_from_home'] = '0-200 km.';
        $d['absent_days'] = (int)($in['absent_days'] ?? 0); if ($d['absent_days'] < 0 || $d['absent_days'] > 100) $err['absent_days'] = '0-100 days.';
        $d['training_hours'] = (int)($in['training_hours'] ?? 0); if ($d['training_hours'] < 0 || $d['training_hours'] > 500) $err['training_hours'] = '0-500 hours.';
        $d['attrition'] = $in['attrition'] ?? ''; if (!in_array($d['attrition'], ['Yes', 'No'], true)) $err['attrition'] = 'Choose a status.';
        return [$d, $err];
    }
}

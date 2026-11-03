<?php
class Leave {
    public static function list($empId = null, $status = '') {
        $w = ['1=1']; $p = [];
        if ($empId) { $w[] = 'l.employee_id = ?'; $p[] = $empId; }
        if ($status) { $w[] = 'l.status = ?'; $p[] = $status; }
        return Database::all("SELECT l.*, e.full_name, e.emp_code FROM leave_requests l JOIN employees e ON e.id = l.employee_id
            WHERE " . implode(' AND ', $w) . " ORDER BY (l.status = 'Pending') DESC, l.start_date DESC LIMIT 200", $p);
    }
    public static function create($emp, $type, $start, $end, $reason) {
        $days = (new DateTime($start))->diff(new DateTime($end))->days + 1;
        Database::run("INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, days, reason, status) VALUES (?,?,?,?,?,?,'Pending')",
            [$emp, $type, $start, $end, $days, $reason]);
    }
    public static function decide($id, $status) {
        Database::run("UPDATE leave_requests SET status = ? WHERE id = ? AND status = 'Pending'", [$status, $id]);
    }
}

<?php
class Employee {
    const PER = 15;
    const COLS = ['full_name','gender','age','email','department_id','job_role','hire_date','monthly_income','years_at_company',
                  'overtime','job_satisfaction','work_life_balance','performance_rating','distance_from_home','absent_days','training_hours','attrition'];
    public static function page($q, $dept, $page) {
        $w = ['1=1']; $p = [];
        if ($q !== '') { $w[] = '(e.full_name LIKE ? OR e.emp_code LIKE ? OR e.job_role LIKE ?)'; array_push($p, "%$q%", "%$q%", "%$q%"); }
        if ($dept) { $w[] = 'e.department_id = ?'; $p[] = $dept; }
        $where = implode(' AND ', $w);
        $total = Database::one("SELECT COUNT(*) c FROM employees e WHERE $where", $p)['c'];
        $off = max(0, $page - 1) * self::PER;
        $rows = Database::all("SELECT e.*, d.name department FROM employees e JOIN departments d ON d.id=e.department_id
                               WHERE $where ORDER BY e.id DESC LIMIT " . self::PER . " OFFSET " . (int)$off, $p);
        return [$rows, (int)$total];
    }
    public static function find($id) { return Database::one('SELECT * FROM employees WHERE id=?', [$id]); }
    public static function departments() { return Database::all('SELECT * FROM departments ORDER BY name'); }
    public static function active() { return Database::all("SELECT id, emp_code, full_name FROM employees WHERE attrition='No' ORDER BY full_name"); }
    public static function save($d, $id = null) {
        $d['years_at_company'] = max(0, (int)floor((time() - strtotime($d['hire_date'])) / 31557600));
        $vals = array_map(function ($c) use ($d) { return $d[$c]; }, self::COLS);
        if ($id) {
            Database::run('UPDATE employees SET ' . implode('=?,', self::COLS) . '=? WHERE id=?', array_merge($vals, [$id]));
            return $id;
        }
        Database::run('INSERT INTO employees (emp_code,' . implode(',', self::COLS) . ') VALUES (?' . str_repeat(',?', count(self::COLS)) . ')',
            array_merge(['T' . uniqid()], $vals));
        $nid = Database::pdo()->lastInsertId();
        Database::run('UPDATE employees SET emp_code=? WHERE id=?', ['EMP' . str_pad($nid, 4, '0', STR_PAD_LEFT), $nid]);
        return $nid;
    }
    public static function delete($id) { Database::run('DELETE FROM employees WHERE id=?', [$id]); }
}

<?php
// Rule-based attrition risk score (0-100). Transparent: every point has a reason.
class RiskModel {
    public static function score($e) {
        $s = 0; $why = [];
        if ($e['overtime'] === 'Yes') { $s += 25; $why[] = 'Works overtime'; }
        if ($e['job_satisfaction'] <= 2) { $s += $e['job_satisfaction'] == 1 ? 20 : 12; $why[] = 'Low job satisfaction'; }
        if ($e['work_life_balance'] <= 2) { $s += 15; $why[] = 'Poor work-life balance'; }
        if ($e['role_avg'] > 0 && $e['monthly_income'] < 0.85 * $e['role_avg']) { $s += 15; $why[] = 'Paid below role average'; }
        if ($e['years_at_company'] <= 2) { $s += 10; $why[] = 'New joiner (0-2 yrs)'; }
        if ($e['absent_days'] >= 12) { $s += 10; $why[] = 'High absence'; }
        if ($e['distance_from_home'] >= 20) { $s += 5; $why[] = 'Long commute'; }
        return [min(100, $s), $why];
    }
    public static function level($s) { return $s >= 60 ? 'High' : ($s >= 35 ? 'Medium' : 'Low'); }
    public static function all() {
        $rows = Database::all("SELECT e.*, d.name AS department,
            (SELECT AVG(x.monthly_income) FROM employees x WHERE x.job_role = e.job_role) AS role_avg
            FROM employees e JOIN departments d ON d.id = e.department_id");
        foreach ($rows as &$r) {
            [$r['score'], $r['why']] = self::score($r);
            $r['level'] = self::level($r['score']);
        }
        return $rows;
    }
}

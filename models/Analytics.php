<?php
class Analytics {
    public function kpis() {
        $k = Database::one("SELECT COUNT(*) total, SUM(attrition='No') active, SUM(attrition='Yes') left_count,
            ROUND(100*SUM(attrition='Yes')/COUNT(*),1) rate, ROUND(AVG(monthly_income)) avg_income,
            ROUND(AVG(CASE WHEN attrition='No' THEN job_satisfaction END),2) avg_sat,
            SUM(attrition='No' AND overtime='Yes') overtime_active FROM employees");
        $k['pending'] = Database::one("SELECT COUNT(*) c FROM leave_requests WHERE status='Pending'")['c'];
        return $k;
    }
    // Attrition rate (%) grouped by one dimension
    public function rateBy($key) {
        $defs = [
            'dept' => ["d.name", "label"],
            'overtime' => ["e.overtime", "label"],
            'satisfaction' => ["CONCAT('Level ', e.job_satisfaction)", "MIN(e.job_satisfaction)"],
            'age' => ["CASE WHEN e.age<26 THEN 'Under 26' WHEN e.age<36 THEN '26-35' WHEN e.age<46 THEN '36-45' ELSE '46+' END", "MIN(e.age)"],
            'tenure' => ["CASE WHEN e.years_at_company<=2 THEN '0-2 yrs' WHEN e.years_at_company<=5 THEN '3-5 yrs' WHEN e.years_at_company<=10 THEN '6-10 yrs' ELSE '10+ yrs' END", "MIN(e.years_at_company)"],
        ];
        [$expr, $order] = $defs[$key];
        return Database::all("SELECT $expr AS label, COUNT(*) total, SUM(e.attrition='Yes') left_count,
            ROUND(100*SUM(e.attrition='Yes')/COUNT(*),1) rate
            FROM employees e JOIN departments d ON d.id=e.department_id
            GROUP BY label ORDER BY $order");
    }
}

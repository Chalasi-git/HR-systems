<?php
require_once ROOT . '/models/RiskModel.php';
class RiskController extends Controller {
    public function __construct() { $this->requireRole('admin', 'hr'); }
    public function index() {
        $all = RiskModel::all();
        $active = array_values(array_filter($all, function ($r) { return $r['attrition'] === 'No'; }));
        usort($active, function ($a, $b) { return $b['score'] <=> $a['score']; });
        $count = ['High' => 0, 'Medium' => 0, 'Low' => 0];
        foreach ($active as $r) $count[$r['level']]++;
        $avg = function ($flag) use ($all) {
            $s = array_column(array_filter($all, function ($r) use ($flag) { return $r['attrition'] === $flag; }), 'score');
            return $s ? round(array_sum($s) / count($s), 1) : 0;
        };
        $this->view('risk/index', ['top' => array_slice($active, 0, 25), 'count' => $count, 'active' => count($active),
                                   'avgLeft' => $avg('Yes'), 'avgStayed' => $avg('No')]);
    }
}

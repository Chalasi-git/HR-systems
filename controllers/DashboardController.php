<?php
require_once ROOT . '/models/Analytics.php';
class DashboardController extends Controller {
    public function __construct() { $this->requireRole('admin', 'hr'); }
    public function index() {
        $m = new Analytics();
        $this->view('dashboard/index', [
            'kpi' => $m->kpis(),
            'charts' => ['dept' => $m->rateBy('dept'), 'overtime' => $m->rateBy('overtime'), 'age' => $m->rateBy('age'),
                         'satisfaction' => $m->rateBy('satisfaction'), 'tenure' => $m->rateBy('tenure')],
        ]);
    }
}

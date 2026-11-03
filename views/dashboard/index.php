<?php
$d = $charts['dept']; usort($d, function ($a, $b) { return $b['rate'] <=> $a['rate']; });
$ot = array_column($charts['overtime'], 'rate', 'label');
$ten = $charts['tenure'][0] ?? null;
?>
<h1 class="h3 mb-1">Workforce overview</h1>
<p class="text-muted">Who is leaving, where, and why. Based on <?= (int)$kpi['total'] ?> employee records.</p>

<section class="kpis mb-4">
  <div><span><?= (int)$kpi['active'] ?></span>Active employees</div>
  <div><span class="<?= $kpi['rate'] > 15 ? 'warn' : '' ?>"><?= e($kpi['rate']) ?>%</span>Attrition rate (<?= (int)$kpi['left_count'] ?> left)</div>
  <div><span><?= e(number_format($kpi['avg_income'])) ?></span>Average monthly income (LKR)</div>
  <div><span><?= e($kpi['avg_sat']) ?>/4</span>Job satisfaction, active staff</div>
  <div><a href="<?= e(url('leaves', 'index', ['status' => 'Pending'])) ?>"><span><?= (int)$kpi['pending'] ?></span>Leave requests waiting</a></div>
</section>

<section class="findings mb-4">
  <h2 class="h6">What the data says</h2>
  <ul class="mb-0">
    <li><b><?= e($d[0]['label']) ?></b> has the highest attrition (<?= e($d[0]['rate']) ?>%); <b><?= e(end($d)['label']) ?></b> has the lowest (<?= e(end($d)['rate']) ?>%).</li>
    <li>Employees who work overtime leave at <b><?= e($ot['Yes'] ?? 0) ?>%</b>, against <b><?= e($ot['No'] ?? 0) ?>%</b> for those who do not.</li>
    <?php if ($ten): ?><li>Staff in their first two years leave at <b><?= e($ten['rate']) ?>%</b>. Onboarding is where retention is won.</li><?php endif; ?>
    <li><b><?= (int)$kpi['overtime_active'] ?></b> active employees currently work overtime. See <a href="<?= e(url('risk')) ?>">Attrition risk</a> for who to talk to first.</li>
  </ul>
</section>

<div class="row g-3">
  <div class="col-lg-4"><div class="panel"><h2>Overall outcome</h2><canvas id="cOverall"></canvas></div></div>
  <div class="col-lg-8"><div class="panel"><h2>Attrition rate by department</h2><canvas id="cDept"></canvas></div></div>
  <div class="col-md-6 col-lg-3"><div class="panel"><h2>By overtime</h2><canvas id="cOt"></canvas></div></div>
  <div class="col-md-6 col-lg-3"><div class="panel"><h2>By job satisfaction</h2><canvas id="cSat"></canvas></div></div>
  <div class="col-md-6 col-lg-3"><div class="panel"><h2>By age group</h2><canvas id="cAge"></canvas></div></div>
  <div class="col-md-6 col-lg-3"><div class="panel"><h2>By years at company</h2><canvas id="cTen"></canvas></div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const C = <?= json_encode($charts) ?>;
function bar(id, rows, color) {
  new Chart(document.getElementById(id), {
    type: 'bar',
    data: { labels: rows.map(r => r.label), datasets: [{ data: rows.map(r => +r.rate), backgroundColor: color, borderRadius: 3 }] },
    options: { plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => c.parsed.y + '% attrition' } } },
               scales: { y: { beginAtZero: true, ticks: { callback: v => v + '%' } } } }
  });
}
bar('cDept', C.dept, '#0E7C7B'); bar('cOt', C.overtime, '#D69E2E'); bar('cSat', C.satisfaction, '#0E7C7B');
bar('cAge', C.age, '#102A43'); bar('cTen', C.tenure, '#C0392B');
new Chart(document.getElementById('cOverall'), {
  type: 'doughnut',
  data: { labels: ['Stayed', 'Left'], datasets: [{ data: [<?= (int)$kpi['active'] ?>, <?= (int)$kpi['left_count'] ?>], backgroundColor: ['#0E7C7B', '#C0392B'] }] },
  options: { plugins: { legend: { position: 'bottom' } } }
});
</script>

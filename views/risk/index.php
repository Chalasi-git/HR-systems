<h1 class="h3 mb-1">Attrition risk</h1>
<p class="text-muted">Active employees ranked by risk of leaving. The score adds up simple, visible factors, so each flag has a reason you can act on.</p>
<section class="kpis mb-3">
  <div><span class="warn"><?= (int)$count['High'] ?></span>High risk (60+)</div>
  <div><span><?= (int)$count['Medium'] ?></span>Medium risk (35-59)</div>
  <div><span><?= (int)$count['Low'] ?></span>Low risk (below 35)</div>
  <div><span><?= e($avgLeft) ?> vs <?= e($avgStayed) ?></span>Average score: people who left vs stayed</div>
</section>
<p class="small text-muted">Model check: people who actually left scored higher on average than people who stayed, so the score points at real patterns. It is a conversation starter, not a verdict on any person.</p>
<div class="table-responsive panel p-0">
<table class="table align-middle mb-0">
  <thead><tr><th>Employee</th><th>Department</th><th>Role</th><th style="width:180px">Risk score</th><th>Why flagged</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($top as $r): ?>
    <tr>
      <td><?= e($r['full_name']) ?><br><small class="text-muted"><?= e($r['emp_code']) ?></small></td>
      <td><?= e($r['department']) ?></td><td><?= e($r['job_role']) ?></td>
      <td><div class="d-flex align-items-center gap-2"><div class="bar"><i class="lvl-<?= strtolower($r['level']) ?>" style="width:<?= (int)$r['score'] ?>%"></i></div><b><?= (int)$r['score'] ?></b></div></td>
      <td><?php foreach ($r['why'] as $w): ?><span class="tag"><?= e($w) ?></span> <?php endforeach; ?></td>
      <td><a class="btn btn-sm btn-outline-dark" href="<?= e(url('employees', 'form', ['id' => $r['id']])) ?>">Open</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table></div>

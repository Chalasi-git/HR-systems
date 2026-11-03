<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <h1 class="h3 mb-0">Employees <small class="text-muted fs-6"><?= (int)$total ?> found</small></h1>
  <a class="btn btn-teal" href="<?= e(url('employees', 'form')) ?>">Add employee</a>
</div>
<form method="get" class="row g-2 mb-3">
  <input type="hidden" name="c" value="employees">
  <div class="col-md-5"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Search name, code or job role"></div>
  <div class="col-md-4"><select class="form-select" name="dept"><option value="0">All departments</option>
    <?php foreach ($departments as $dp): ?><option value="<?= (int)$dp['id'] ?>" <?= $dept == $dp['id'] ? 'selected' : '' ?>><?= e($dp['name']) ?></option><?php endforeach; ?>
  </select></div>
  <div class="col-md-3"><button class="btn btn-outline-dark w-100">Search</button></div>
</form>
<div class="table-responsive panel p-0">
<table class="table table-hover align-middle mb-0">
  <thead><tr><th>Code</th><th>Name</th><th>Department</th><th>Role</th><th class="text-end">Income</th><th>Overtime</th><th>Status</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= e($r['emp_code']) ?></td><td><?= e($r['full_name']) ?></td><td><?= e($r['department']) ?></td><td><?= e($r['job_role']) ?></td>
      <td class="text-end"><?= e(number_format($r['monthly_income'])) ?></td><td><?= e($r['overtime']) ?></td>
      <td><span class="badge <?= $r['attrition'] === 'Yes' ? 'text-bg-secondary' : 'text-bg-success' ?>"><?= $r['attrition'] === 'Yes' ? 'Left' : 'Active' ?></span></td>
      <td class="text-end text-nowrap">
        <a class="btn btn-sm btn-outline-dark" href="<?= e(url('employees', 'form', ['id' => $r['id']])) ?>">Edit</a>
        <?php if (($user['role'] ?? '') === 'admin'): ?>
        <form method="post" action="<?= e(url('employees', 'delete')) ?>" class="d-inline" onsubmit="return confirm('Delete <?= e($r['full_name']) ?>? This cannot be undone.')">
          <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn btn-sm btn-outline-danger">Delete</button>
        </form><?php endif; ?>
      </td>
    </tr>
  <?php endforeach; if (!$rows): ?><tr><td colspan="8" class="text-center text-muted py-4">No employees match. Try a different search.</td></tr><?php endif; ?>
  </tbody>
</table></div>
<?php if ($pages > 1): ?>
<nav class="mt-3 d-flex gap-1 flex-wrap">
  <?php for ($i = 1; $i <= $pages; $i++): ?>
    <a class="btn btn-sm <?= $i == $page ? 'btn-teal' : 'btn-outline-dark' ?>" href="<?= e(url('employees', 'index', ['q' => $q, 'dept' => $dept, 'page' => $i])) ?>"><?= $i ?></a>
  <?php endfor; ?>
</nav><?php endif; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <h1 class="h3 mb-0">Leave requests</h1>
  <a class="btn btn-teal" href="<?= e(url('leaves', 'form')) ?>">Request leave</a>
</div>
<div class="d-flex gap-1 mb-3 flex-wrap">
  <?php foreach (['' => 'All', 'Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected'] as $v => $t): ?>
    <a class="btn btn-sm <?= $status === $v ? 'btn-teal' : 'btn-outline-dark' ?>" href="<?= e(url('leaves', 'index', ['status' => $v])) ?>"><?= e($t) ?></a>
  <?php endforeach; ?>
</div>
<div class="table-responsive panel p-0">
<table class="table align-middle mb-0">
  <thead><tr><th>Employee</th><th>Type</th><th>From</th><th>To</th><th>Days</th><th>Reason</th><th>Status</th><?php if ($isHr): ?><th></th><?php endif; ?></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= e($r['full_name']) ?> <small class="text-muted"><?= e($r['emp_code']) ?></small></td><td><?= e($r['leave_type']) ?></td>
      <td><?= e($r['start_date']) ?></td><td><?= e($r['end_date']) ?></td><td><?= (int)$r['days'] ?></td><td><?= e($r['reason']) ?></td>
      <td><span class="badge <?= ['Pending' => 'text-bg-warning', 'Approved' => 'text-bg-success', 'Rejected' => 'text-bg-danger'][$r['status']] ?>"><?= e($r['status']) ?></span></td>
      <?php if ($isHr): ?><td class="text-end text-nowrap">
        <?php if ($r['status'] === 'Pending'): ?>
        <form method="post" action="<?= e(url('leaves', 'decide')) ?>" class="d-inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
          <button name="decision" value="Approved" class="btn btn-sm btn-teal">Approve</button>
          <button name="decision" value="Rejected" class="btn btn-sm btn-outline-danger">Reject</button></form>
        <?php endif; ?></td><?php endif; ?>
    </tr>
  <?php endforeach; if (!$rows): ?><tr><td colspan="8" class="text-center text-muted py-4">No leave requests here yet.</td></tr><?php endif; ?>
  </tbody>
</table></div>

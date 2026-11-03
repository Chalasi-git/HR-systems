<h1 class="h3 mb-3">Request leave</h1>
<?php foreach ($errors as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" class="panel" style="max-width:640px">
  <?= csrf_field() ?>
  <?php if ($isHr): ?>
  <label class="form-label">Employee</label>
  <select class="form-select mb-3" name="employee_id"><option value="">Choose...</option>
    <?php foreach ($employees as $e2): ?><option value="<?= (int)$e2['id'] ?>" <?= $in['employee_id'] == $e2['id'] ? 'selected' : '' ?>><?= e($e2['full_name']) ?> (<?= e($e2['emp_code']) ?>)</option><?php endforeach; ?>
  </select>
  <?php endif; ?>
  <label class="form-label">Leave type</label>
  <select class="form-select mb-3" name="leave_type">
    <?php foreach (['Annual', 'Casual', 'Sick'] as $t): ?><option <?= $in['leave_type'] === $t ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?>
  </select>
  <div class="row g-3 mb-3">
    <div class="col"><label class="form-label">From</label><input class="form-control" type="date" name="start_date" value="<?= e($in['start_date']) ?>"></div>
    <div class="col"><label class="form-label">To</label><input class="form-control" type="date" name="end_date" value="<?= e($in['end_date']) ?>"></div>
  </div>
  <label class="form-label">Reason</label>
  <input class="form-control mb-4" name="reason" maxlength="200" value="<?= e($in['reason']) ?>">
  <button class="btn btn-teal">Submit request</button>
  <a class="btn btn-outline-dark" href="<?= e(url('leaves')) ?>">Cancel</a>
</form>

<?php
$inp = function ($n, $label, $type = 'text') use ($data, $errors) { ?>
  <div class="col-md-4"><label class="form-label"><?= e($label) ?></label>
    <input class="form-control <?= isset($errors[$n]) ? 'is-invalid' : '' ?>" type="<?= e($type) ?>" name="<?= e($n) ?>" value="<?= e($data[$n]) ?>">
    <div class="invalid-feedback"><?= e($errors[$n] ?? '') ?></div></div>
<?php };
$sel = function ($n, $label, $opts) use ($data, $errors) { ?>
  <div class="col-md-4"><label class="form-label"><?= e($label) ?></label>
    <select class="form-select <?= isset($errors[$n]) ? 'is-invalid' : '' ?>" name="<?= e($n) ?>">
      <?php foreach ($opts as $v => $t): ?><option value="<?= e($v) ?>" <?= (string)$data[$n] === (string)$v ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?>
    </select><div class="invalid-feedback"><?= e($errors[$n] ?? '') ?></div></div>
<?php };
$deptOpts = ['' => 'Choose...']; foreach ($departments as $dp) $deptOpts[$dp['id']] = $dp['name'];
$scale = [1 => '1 (low)', 2 => '2', 3 => '3', 4 => '4 (high)'];
?>
<h1 class="h3 mb-3"><?= $id ? 'Edit employee' : 'Add employee' ?></h1>
<form method="post" class="panel">
  <?= csrf_field() ?>
  <div class="row g-3">
    <?php $inp('full_name', 'Full name'); $inp('email', 'Email', 'email'); $sel('gender', 'Gender', ['Male' => 'Male', 'Female' => 'Female']);
    $sel('department_id', 'Department', $deptOpts); $inp('job_role', 'Job role'); $inp('age', 'Age', 'number');
    $inp('hire_date', 'Hire date', 'date'); $inp('monthly_income', 'Monthly income (LKR)', 'number'); $sel('overtime', 'Works overtime', ['No' => 'No', 'Yes' => 'Yes']);
    $sel('job_satisfaction', 'Job satisfaction', $scale); $sel('work_life_balance', 'Work-life balance', $scale); $sel('performance_rating', 'Performance rating', $scale);
    $inp('distance_from_home', 'Distance from home (km)', 'number'); $inp('absent_days', 'Absent days this year', 'number'); $inp('training_hours', 'Training hours', 'number');
    $sel('attrition', 'Status', ['No' => 'Active', 'Yes' => 'Left the company']); ?>
  </div>
  <div class="mt-4 d-flex gap-2">
    <button class="btn btn-teal">Save employee</button>
    <a class="btn btn-outline-dark" href="<?= e(url('employees')) ?>">Cancel</a>
  </div>
</form>

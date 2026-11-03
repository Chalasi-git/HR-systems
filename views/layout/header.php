<?php $cur = $_GET['c'] ?? 'dashboard'; $role = $user['role'] ?? ''; $hr = in_array($role, ['admin', 'hr'], true); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PeoplePulse HR</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet">
</head>
<body>
<header class="topbar">
  <div class="container-xl d-flex align-items-center gap-4 flex-wrap py-2">
    <a class="brand" href="<?= e(url($hr ? 'dashboard' : 'leaves')) ?>">PeoplePulse HR</a>
    <nav class="d-flex gap-1 flex-wrap flex-grow-1">
      <?php if ($hr): ?>
        <a class="<?= $cur === 'dashboard' ? 'on' : '' ?>" href="<?= e(url('dashboard')) ?>">Dashboard</a>
        <a class="<?= $cur === 'employees' ? 'on' : '' ?>" href="<?= e(url('employees')) ?>">Employees</a>
        <a class="<?= $cur === 'risk' ? 'on' : '' ?>" href="<?= e(url('risk')) ?>">Attrition risk</a>
      <?php endif; ?>
      <a class="<?= $cur === 'leaves' ? 'on' : '' ?>" href="<?= e(url('leaves')) ?>">Leave</a>
    </nav>
    <div class="who"><?= e($user['username'] ?? '') ?> (<?= e($role) ?>) &nbsp;<a href="<?= e(url('auth', 'logout')) ?>">Sign out</a></div>
  </div>
</header>
<main class="container-xl py-4">
<?php if ($f = flash()): ?><div class="alert alert-<?= $f[1] === 'success' ? 'success' : 'danger' ?> py-2"><?= e($f[0]) ?></div><?php endif; ?>

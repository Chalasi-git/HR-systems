<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign in - PeoplePulse HR</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet"></head>
<body class="login-page">
<form method="post" class="login-box">
  <h1>PeoplePulse HR</h1>
  <p class="text-muted">Sign in to manage your workforce.</p>
  <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
  <?= csrf_field() ?>
  <label class="form-label">Username</label>
  <input class="form-control mb-3" name="username" autofocus required>
  <label class="form-label">Password</label>
  <input class="form-control mb-3" type="password" name="password" required>
  <button class="btn btn-teal w-100">Sign in</button>
  <p class="small text-muted mt-3 mb-0">Demo logins (password: <b>password</b>): admin, hr, employee</p>
</form>
</body></html>

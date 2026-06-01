<?php
require_once __DIR__ . '/includes/init.php';

if (isAdmin()) {
    header('Location: ' . adminUrl('dashboard.php'));
    exit;
}

$error = '';
if (!empty($_GET['timeout'])) {
    $error = 'Session expired. Please log in again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfForm();
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = !empty($_POST['remember']);

    if (isLoginLocked($username)) {
        $error = 'Too many failed attempts. Try again in 15 minutes.';
    } elseif (adminLogin($username, $password, $remember)) {
        header('Location: ' . adminUrl('dashboard.php'));
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Journey Karo</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= adminAsset('admin.css') ?>">
  <style>
    body { display:flex;align-items:center;justify-content:center;min-height:100vh;background:linear-gradient(135deg,#081d4e,#0d2b6e); }
    .login-card { background:#fff;border-radius:16px;padding:2.5rem;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,0.25); }
    .login-card h1 { margin:0 0 0.25rem;font-size:1.5rem;color:#0d2b6e; }
    .login-card p { margin:0 0 1.5rem;color:#64748b;font-size:0.875rem; }
  </style>
</head>
<body>
  <div class="login-card">
    <h1>✈️ Journey Karo</h1>
    <p>Admin panel sign in</p>
    <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="POST">
      <?= csrfField() ?>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control" required autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
      </div>
      <div class="form-group">
        <label><input type="checkbox" name="remember" value="1"> Remember me for 30 days</label>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.75rem">Sign In</button>
    </form>
    <p style="margin-top:1.5rem;font-size:0.75rem;color:#94a3b8;text-align:center">
      <a href="<?= APP_URL ?>" style="color:#0d2b6e">← Back to website</a>
    </p>
  </div>
</body>
</html>

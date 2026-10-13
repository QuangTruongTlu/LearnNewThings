<?php
$db = get_db();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đủ username và password.';
    } else {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username đã tồn tại.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            header('Location: /?page=login');
            exit;
        }
    }
}

$page_title = 'Đăng ký';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title">Đăng ký tài khoản</h1>
        <p class="card-subtitle">Bắt đầu hành trình của bạn tại đây! ⸜(｡˃ ᵕ ˂ )⸝♡</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form class="form" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input class="form-control" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" id="password" type="password" name="password" required>
        </div>

        <div style="display:flex; gap:0.75rem; margin-top:0.5rem;">
            <button class="btn btn-primary" type="submit">Đăng ký</button>
            <a class="btn btn-ghost" href="/?page=login">Đã có tài khoản</a>
        </div>
    </form>
</div>
<?php
include __DIR__ . '/../includes/footer.php';

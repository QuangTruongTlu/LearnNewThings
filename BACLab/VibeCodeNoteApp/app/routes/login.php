<?php
$db = get_db();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $error = 'Sai username hoặc password.';
    } else {
        $_SESSION['user'] = $user;
        header('Location: /?page=home');
        exit;
    }
}

$page_title = 'Đăng nhập';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title">Đăng nhập</h1>
        <p class="card-subtitle">Sử dụng tài khoản của bạn để truy cập NoteApp.</p>
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
            <button class="btn btn-primary" type="submit">Đăng nhập</button>
            <a class="btn btn-ghost" href="/?page=registration">Tạo tài khoản mới</a>
        </div>
    </form>
</div>
<?php
include __DIR__ . '/../includes/footer.php';

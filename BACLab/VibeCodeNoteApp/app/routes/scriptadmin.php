<?php
// app/routes/scriptadmin.php
require_login();

// Đọc key thật từ file (đặt ở app/admin.key)
$key_file = __DIR__ . '/../admin.key';   // <<-- SỬA DÒNG NÀY
$real_key = trim(@file_get_contents($key_file));

// Key user gửi lên (qua GET hoặc POST đều được)
$user_key = $_GET['key'] ?? '';

if (!$real_key || $user_key !== $real_key) {
    http_response_code(403);
    echo "<h1 style='color:#f87171;'>403 Forbidden</h1>";
    echo "<p>Invalid access key.</p>";
    exit;
}

$db   = get_db();
$user = current_user();

$cmd    = $_POST['cmd'] ?? '';
$output = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // rất nguy hiểm, cố ý cho lab
    $cmdLine = 'php -r ' . escapeshellarg($cmd);
    $output  = shell_exec($cmdLine . ' 2>&1');
}

$page_title = 'Admin Script Panel';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title">Admin Script Panel</h1>
        <p class="card-subtitle">
            Chạy thử đoạn PHP trên server.
        </p>
    </div>

    <form class="form" method="post">
        <div class="form-group">
            <label for="cmd">PHP snippet (php -r "...")</label>
            <textarea class="form-textarea" id="cmd" name="cmd" rows="8"
                      placeholder='echo "Hello from PHP\n";'><?= htmlspecialchars($cmd) ?></textarea>
        </div>

        <button class="btn btn-primary" type="submit">RUN</button>
    </form>

    <?php if ($output !== ''): ?>
        <div style="margin-top:1rem;">
            <div class="section-title" style="font-size:1rem;">Output</div>
            <pre class="note-content" style="margin-top:0.5rem;"><?= htmlspecialchars($output) ?></pre>
        </div>
    <?php endif; ?>
</div>
<?php
include __DIR__ . '/../includes/footer.php';

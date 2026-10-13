<?php
require_login();
$db   = get_db();
$user = current_user();

$noteId = (int)($_GET['noteid'] ?? 0);
$stmt   = $db->prepare("SELECT * FROM notes WHERE id = ?");
$stmt->execute([$noteId]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note || $note['owner_uid'] != $user['id']) {
    http_response_code(404);
    die("Note không tồn tại hoặc không thuộc về bạn.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $error = 'Vui lòng nhập đầy đủ tiêu đề và nội dung.';
    } else {
        $stmt = $db->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $noteId]);
        header('Location: /?page=note');
        exit;
    }
}

$page_title = 'Sửa note';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
    <div class="topbar">
        <div class="section-title">Sửa ghi chú #<?= (int)$note['id'] ?></div>
        <div class="nav">
            <a href="/?page=note">note</a>
            <a href="/?page=home">home</a>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form class="form" method="post">
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input class="form-control" id="title" name="title"
                   value="<?= htmlspecialchars($note['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea class="form-textarea" id="content" name="content" required><?= htmlspecialchars($note['content']) ?></textarea>
        </div>

        <button class="btn btn-primary" type="submit">Lưu</button>
    </form>
</div>
<?php
include __DIR__ . '/../includes/footer.php';

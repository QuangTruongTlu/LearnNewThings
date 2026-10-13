<?php
require_login();
$db   = get_db();
// user hiện tại không được dùng để filter → IDOR
$uid  = (int)($_GET['uid'] ?? 0);

$stmt = $db->prepare("SELECT * FROM notes WHERE owner_uid = ? ORDER BY id ASC");
$stmt->execute([$uid]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Xem note của uid=$uid";
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
    <div class="topbar">
        <div class="section-title">Ghi chú của uid = <?= $uid ?></div>
        <div class="nav">
            <a href="/?page=note">note</a>
            <a href="/?page=home">home</a>
        </div>
    </div>

    <?php if (!$notes): ?>
        <p style="font-size:0.9rem;">Không tìm thấy ghi chú nào cho uid này.</p>
    <?php else: ?>
        <?php foreach ($notes as $n): ?>
            <div class="note-view-item">
                <strong>#<?= (int)$n['id'] ?> - <?= htmlspecialchars($n['title']) ?></strong>
                <div class="uid">owner_uid = <?= (int)$n['owner_uid'] ?></div>
                <div class="note-content"><?= htmlspecialchars($n['content']) ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php
include __DIR__ . '/../includes/footer.php';

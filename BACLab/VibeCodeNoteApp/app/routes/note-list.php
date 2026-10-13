<?php
require_login();
$db   = get_db();
$user = current_user();

$stmt = $db->prepare("SELECT * FROM notes WHERE owner_uid = ? ORDER BY id ASC");
$stmt->execute([$user['id']]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Notes';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
    <div class="topbar">
        <div class="section-title">Ghi chú của bạn</div>
        <div class="nav">
            <a href="/?page=note-create">+ new note</a>
            <a href="/?page=home">/home</a>
        </div>
    </div>

    <?php if (!$notes): ?>
        <p style="font-size:0.9rem;">Bạn chưa có ghi chú nào.</p>
    <?php else: ?>
        <div class="note-main">
            <div>
                <div class="note-list">
                    <?php foreach ($notes as $n): ?>
                        <div class="note-item">
                            <div>
                                <div class="note-title">
                                    <?= htmlspecialchars($n['title']) ?>
                                </div>
                                <div class="uid">
                                    owner_uid = <?= (int)$n['owner_uid'] ?>
                                </div>
                                <div class="note-actions">
                                    <a href="/?page=note-view&uid=<?= (int)$n['owner_uid'] ?>" class="note-view-link">view</a>
                                    <a href="/?page=note-edit&noteid=<?= (int)$n['id'] ?>">edit</a>
                            </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
include __DIR__ . '/../includes/footer.php';

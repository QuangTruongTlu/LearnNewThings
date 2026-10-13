<?php
// app/includes/header.php
$user = current_user();
if (!isset($page_title)) {
    $page_title = 'NoteApp BAC → Reverse Shell Lab';
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-left">
        <div class="navbar-brand">NoteApp Lab</div>
        <?php if ($user): ?>
            <div class="navbar-user">
                Xin chào, <strong><?= htmlspecialchars($user['username']) ?></strong>
                (uid: <?= (int)$user['id'] ?>)
            </div>
        <?php endif; ?>
    </div>
    <div class="navbar-links">
    <a href="/?page=home" class="nav-pill">Home</a>

    <?php if ($user): ?>
        <a href="/?page=note" class="nav-pill nav-pill-primary">Notes</a>
        <a href="/?page=logout" class="nav-pill nav-pill-danger">Logout</a>
    <?php else: ?>
        <a href="/?page=login" class="nav-pill nav-pill-primary">Đăng nhập</a>
        <a href="/?page=registration" class="nav-pill nav-pill-outline">Đăng ký</a>
    <?php endif; ?>
</div>
</nav>
<main class="page">

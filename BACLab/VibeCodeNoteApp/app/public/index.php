<?php
// app/public/index.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';

$page = $_GET['page'] ?? 'home';

$routes = [
    'home'          => 'home.php',
    'login'         => 'login.php',
    'logout'        => 'logout.php',
    'registration'  => 'registration.php',
    'note'          => 'note-list.php',
    'note-create'   => 'note-create.php',
    'note-edit'     => 'note-edit.php',
    'note-view'     => 'note-view.php',
    'scriptadmin'   => 'scriptadmin.php',
];

if (!isset($routes[$page])) {
    http_response_code(404);
    $page_title = '404 Not Found';
    include __DIR__ . '/../includes/header.php';
    ?>
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">404 Not Found</h1>
            <p class="card-subtitle">Trang bạn yêu cầu không tồn tại.</p>
        </div>
    </div>
    <?php
    include __DIR__ . '/../includes/footer.php';
    exit;
}

// Các route tự handle header/footer
require __DIR__ . '/../routes/' . $routes[$page];

<?php
// app/config/db.php

function get_db(): PDO
{
    static $db = null;
    if ($db !== null) {
        return $db;
    }

    $dbPath = __DIR__ . '/../../data/app.db';
    if (!is_dir(dirname($dbPath))) {
        mkdir(dirname($dbPath), 0777, true);
    }

    $needInit = !file_exists($dbPath);

    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($needInit) {
        init_db($db);
    }

    return $db;
}

function init_db(PDO $db): void
{
    // ===== Tạo bảng =====
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            username TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL
        );
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS notes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            owner_uid INTEGER NOT NULL,
            title TEXT NOT NULL,
            content TEXT NOT NULL
        );
    ");

    // ===== Sinh / đọc admin key =====
    $adminKeyFile = __DIR__ . '/../admin.key';

    if (!file_exists($adminKeyFile)) {
        try {
            $adminKey = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            $adminKey = bin2hex(openssl_random_pseudo_bytes(16));
        }
        file_put_contents($adminKeyFile, $adminKey);
    } else {
        $adminKey = trim(file_get_contents($adminKeyFile));
    }

    // ===== Random ID =====
    $used = [];

    $rootId = random_int(1, 1000);
    $adminId = random_int(1, 1000);
    while ($adminId === $rootId) {
        $adminId = random_int(1, 1000);
    }

    $used[$rootId]  = true;
    $used[$adminId] = true;

    // ===== Seed root & admin =====
    $rootPass  = password_hash('rootpass', PASSWORD_BCRYPT);
    $adminPass = password_hash('adminpass', PASSWORD_BCRYPT);

    $stmtUser = $db->prepare("INSERT OR IGNORE INTO users (id, username, password_hash) VALUES (?, ?, ?)");

    $stmtUser->execute([$rootId,  'root',  $rootPass]);
    $stmtUser->execute([$adminId, 'admin', $adminPass]);

    // ===== Thêm 3–4 user khác =====
    $extraNames = ['alice', 'bob', 'charlie', 'david'];

    foreach ($extraNames as $name) {
        do {
            $uid = random_int(1, 1000);
        } while (isset($used[$uid]));

        $used[$uid] = true;

        $pass = password_hash($name . "pass", PASSWORD_BCRYPT);
        $stmtUser->execute([$uid, $name, $pass]);
    }

    // ===== Seed notes =====
    $stmtNote = $db->prepare(
        "INSERT INTO notes (owner_uid, title, content) VALUES (?, ?, ?)"
    );

    // Admin note
    $stmtNote->execute([
        $adminId,
        'Admin note: debug route',
        "Có một panel script đặc biệt tại:\n\n/?page=scriptadmin&key={$adminKey}\n\nNhớ đừng để ai khác thấy!"
    ]);
    $stmtNote->execute([
        $adminId,
        'Bố note: debug route',
        "Bố già rồi , bố nhượng toàn quyền cho con. Mọi thứ con cần nằm trong note của bố - Root"
    ]);
    $stmtNote->execute([
        $adminId,
        'Admin note: flag1',
        "BACLab1{Admin_Nhung_Khong_phai_la_admin}"
    ]);

    // Root note
    $stmtNote->execute([
        $rootId,
        'Root note: backup script',
        "Có một script python chạy với quyền root ở đâu đó trong hệ thống...\n\nTên file: secret.py"
    ]);

    $stmtNote->execute([
        $rootId,
        'Root note: flag2',
        "BACLab2{Day_Moi_la_admin_that_su_ne!}"
    ]);

    // ===== Thêm note vui vui cho user thường =====

    $funNotes = [
        [
            "title" => "Nhật ký vui",
            "content" => "Hôm nay mở app lên thấy server không crash — đúng là một ngày đẹp trời!"
        ],
        [
            "title" => "Ghi chú bí mật",
            "content" => "Nếu ai đọc được note này... thì thôi, coi như không biết gì đi 😎"
        ],
        [
            "title" => "Suy nghĩ trong lúc đói",
            "content" => "Code lỗi thì fix được, bụng đói thì phải ăn trước đã."
        ],
        [
            "title" => "Nhật ký coder",
            "content" => "Debug 2 tiếng mới nhận ra thiếu dấu ;"
        ],
        [
            "title" => "Truyện ngắn 1 phút",
            "content" => "Có một lần tôi định ngủ sớm… và rồi tôi thức tới 3 giờ sáng vì bug."
        ]
    ];

    // Lấy user thường
    $stmtFetch = $db->prepare("SELECT id, username FROM users WHERE id NOT IN (?, ?)");
    $stmtFetch->execute([$rootId, $adminId]);
    $users = $stmtFetch->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $u) {
        $uid = $u['id'];
        $username = $u['username'];

        // 1–3 note mỗi user
        $noteCount = random_int(1, 3);

        for ($i = 0; $i < $noteCount; $i++) {
            $n = $funNotes[array_rand($funNotes)];

            $stmtNote->execute([
                $uid,
                $n["title"] . " của " . ucfirst($username),
                $n["content"] . "\n\n(owner: {$username}, uid: {$uid})"
            ]);
        }
    }
}

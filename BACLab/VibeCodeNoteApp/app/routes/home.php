<?php
$page_title = 'Home';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title">SecureNoteApp</h1>
        <p class="card-subtitle">
            Ứng dụng ghi chú đơn giản, an toàn và bảo mật cực cao ദ്ദി(˵ •̀ ᴗ - ˵ ) ✧
        </p>
    </div>

    <div class="hint-box" style="margin-bottom:1rem;">
        <ul style="list-style:disc; padding-left:1.1rem; font-size:0.85rem;">
            <li>Mỗi người dùng đều có không gian ghi chú riêng</li>
            <li>Sự riêng tư của mỗi người là ưu tiên hàng đầu của chúng tôi</li>
            <li>100% Siêu cấp vuýp pro Bảo Mật được chứng thực bởi Gia Phước Thịnh Corp.</li>
        </ul>
    </div>

    <?php if (current_user()): ?>   
        <p style="font-size:0.9rem;">
            Bạn đã đăng nhập. Chuyển tới <a href="/?page=note" class="nav-pill nav-pill-primary">note</a>.
        </p>
    <?php else: ?>
        <p style="font-size:0.9rem;">
            Hãy <a href="/?page=login" class="nav-pill nav-pill-primary">đăng nhập</a> hoặc
            <a href="/?page=registration" class="nav-pill nav-pill-primary">tạo tài khoản mới</a> để bắt đầu.
        </p>
    <?php endif; ?>
</div>
<?php
include __DIR__ . '/../includes/footer.php';

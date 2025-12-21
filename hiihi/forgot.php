<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
require 'db.php';
?>
<main class="max-w-3xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold text-blue-600">Quên mật khẩu</h2>
    <?php
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/src/Exception.php';
$config = require 'config_email.php';
$err = '';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email === '') $err = 'Vui lòng nhập email.';
    else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) $err = 'Không tìm thấy tài khoản với email này.';
        else {
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600);
            $upd = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
            $upd->execute([$token, $expires, $user['id']]);
            $resetLink = sprintf(
                "%s/reset.php?token=%s",
                (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']),
                $token
            );
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $config['smtp_host'];
                $mail->Port = $config['smtp_port'];
                $mail->SMTPAuth = true;
                $mail->Username = $config['smtp_username'];
                $mail->Password = $config['smtp_password'];
                $mail->SMTPSecure = $config['smtp_secure'];
                $mail->setFrom($config['from_email'], $config['from_name']);
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Yêu cầu đặt lại mật khẩu - Hanoi Rental';
                $mail->Body = 'Click link để đặt lại mật khẩu: <a href="' . $resetLink . '">' . $resetLink . '</a>';
                $mail->send();
                $msg = 'Một email đặt lại mật khẩu đã được gửi. Kiểm tra hộp thư của bạn.';
            } catch (Exception $e) {
                $msg = 'Không gửi được email ('.$e->getMessage().'). Link reset (local): <a href="' . $resetLink . '">' . $resetLink . '</a>';
            }
        }
    }
}
?>
<?php if ($err): ?><div class="text-sm text-red-600 mb-3"><?=htmlspecialchars($err)?></div><?php endif; ?>
<?php if ($msg): ?><div class="text-sm text-green-700 mb-3"><?=$msg?></div><?php endif; ?>
<form method="post">
  <label class="block text-sm">Email đã đăng ký</label>
  <input name="email" type="email" required class="w-full p-2 border rounded mb-4">
  <div class="flex justify-between">
    <button class="px-4 py-2 rounded text-white bg-blue-500" type="submit">Gửi yêu cầu</button>
    <a href="login.php" class="underline">Hủy</a>
  </div>
</form>

  </div>
</main>
<?php include 'footer.php'; ?>

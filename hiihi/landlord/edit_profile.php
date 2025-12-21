<?php
// landlord/edit_profile.php
require __DIR__ . '/../db.php';
require __DIR__ . '/../functions.php';
if (!isLoggedIn()) {
    header('Location: /auth.php?mode=login');
    exit;
}
$u = $_SESSION['user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? $u['name'];
    $fullname = $_POST['fullname'] ?? $u['fullname'];
    $phone = $_POST['phone'] ?? $u['phone'];
    $email = $_POST['email'] ?? $u['email'];
    // avatar upload
    if (!empty($_FILES['avatar']['tmp_name'])) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $dest = '/uploads/avatars/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__ . $dest);
        $avatar_sql = ", avatar = :avatar";
    } else {
        $avatar_sql = '';
    }
    $sql = "UPDATE users SET name = :name, fullname = :fullname, phone = :phone, email = :email $avatar_sql WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $params = [':name'=>$name, ':fullname'=>$fullname, ':phone'=>$phone, ':email'=>$email, ':id'=>$u['id']];
    if (isset($dest)) $params[':avatar'] = $dest;
    $stmt->execute($params);
    // refresh session user
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$u['id']]);
    $_SESSION['user'] = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Location: /profile.php?updated=1');
    exit;
}
?>
<!-- Simple form (keep your project's CSS/font) -->
<form method="post" enctype="multipart/form-data">
  <label>Username <input name="name" value="<?php echo htmlspecialchars($u['name']); ?>"></label><br>
  <label>Full name <input name="fullname" value="<?php echo htmlspecialchars($u['fullname']); ?>"></label><br>
  <label>Phone <input name="phone" value="<?php echo htmlspecialchars($u['phone']); ?>"></label><br>
  <label>Email <input name="email" value="<?php echo htmlspecialchars($u['email']); ?>"></label><br>
  <label>Avatar <input type="file" name="avatar"></label><br>
  <button type="submit">Save</button>
</form>

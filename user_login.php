<?php
require_once __DIR__ . '/db.php';
if (!empty($_SESSION['user'])) { header('Location: index.php'); exit; }
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([strtolower($email)]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($u && password_verify($password, $u['password_hash'])) {
        $_SESSION['user'] = ['id'=>$u['id'], 'full_name'=>$u['full_name'], 'email'=>$u['email']];
        session_regenerate_id(true);
        header('Location: index.php'); exit;
    } else {
        $err = 'Identifiants invalides';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion — Demande de congé</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <main class="container small">
    <h1>Connexion</h1>
    <?php if ($err): ?><div class="alert error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
    <form method="post" class="card">
      <div class="row">
        <label>Email</label>
        <input type="email" name="email" required autocomplete="username">
      </div>
      <div class="row">
        <label>Mot de passe</label>
        <input type="password" name="password" required autocomplete="current-password">
      </div>
      <div class="row">
        <button type="submit">Se connecter</button>
      </div>
    </form>
    <p class="muted">Pas encore de compte ? <a href="user_register.php">Créer un compte</a></p>
    <p class="muted"><a href="index.php">← Retour</a></p>
  </main>
</body>
</html>

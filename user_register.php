<?php
require_once __DIR__ . '/db.php';

// If already logged in, go home
if (!empty($_SESSION['user'])) { header('Location: index.php'); exit; }

$err = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(400); die('CSRF token invalide');
    }
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($full_name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6 || $password !== $password2) {
        $err = "Vérifiez les champs. Mot de passe min. 6 caractères et confirmation identique.";
    } else {
        try {
            $pdo = db();
            $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, created_at) VALUES (?,?,?,?)');
            $stmt->execute([$full_name, strtolower($email), password_hash($password, PASSWORD_DEFAULT), date('c')]);
            $ok = true;
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'UNIQUE') !== false) {
                $err = "Cet email est déjà utilisé.";
            } else {
                $err = "Erreur: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Créer un compte — Demande de congé</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <main class="container small">
    <h1>Créer un compte</h1>
    <?php if ($ok): ?>
      <div class="alert success">Compte créé. Vous pouvez maintenant vous connecter.</div>
      <p><a class="btn" href="user_login.php">Aller à la connexion</a></p>
    <?php else: ?>
      <?php if ($err): ?><div class="alert error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
      <form method="post" class="card">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="row">
          <label>Nom et prénom *</label>
          <input type="text" name="full_name" required>
        </div>
        <div class="row">
          <label>Email *</label>
          <input type="email" name="email" required>
        </div>
        <div class="row">
          <label>Mot de passe *</label>
          <input type="password" name="password" required>
        </div>
        <div class="row">
          <label>Confirmer le mot de passe *</label>
          <input type="password" name="password2" required>
        </div>
        <div class="row">
          <button type="submit">Créer le compte</button>
        </div>
      </form>
    <?php endif; ?>
    <p class="muted"><a href="index.php">← Retour</a></p>
  </main>
</body>
</html>

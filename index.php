<?php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo SITE_NAME; ?> — Demande de congé</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <header class="container">
    <h1><?php echo SITE_NAME; ?></h1>
    <p>Formulaire de demande de congé</p>
  </header>

  <main class="container">
    <nav style="margin-bottom:12px">
      <?php if (empty($_SESSION['user'])): ?>
        <a class="btn" href="user_login.php">Se connecter</a>
        <a class="btn outline" href="user_register.php">Créer un compte</a>
      <?php else: ?>
        <span class="muted">Connecté en tant que <strong><?php echo htmlspecialchars($_SESSION['user']['full_name']); ?></strong></span>
        <a class="btn outline" href="user_logout.php">Se déconnecter</a>
      <?php endif; ?>
      <span style="float:right" class="muted">Espace admin : <a href="admin/login.php">connexion</a></span>
    </nav>

    <?php if (!empty($_GET['ok'])): ?>
      <div class="alert success">Votre demande a bien été envoyée. Un email de confirmation vous a été adressé.</div>
    <?php elseif (!empty($_GET['err'])): ?>
      <div class="alert error">Une erreur est survenue lors de l'envoi. Merci de réessayer.</div>
    <?php endif; ?>

    <?php if (empty($_SESSION['user'])): ?>
      <div class="card">
        <p><strong>Veuillez vous connecter</strong> ou <strong>créer un compte</strong> pour soumettre une demande.</p>
        <p><a class="btn" href="user_login.php">Se connecter</a> <a class="btn outline" href="user_register.php">Créer un compte</a></p>
      </div>
    <?php else: ?>
      <form method="post" action="submit.php" class="card">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="row">
          <label>Demandeur</label>
          <input type="text" value="<?php echo htmlspecialchars($_SESSION['user']['full_name'].' — '.$_SESSION['user']['email']); ?>" readonly>
        </div>
        <div class="row grid2">
          <div>
            <label>Début *</label>
            <input type="date" name="start_date" required>
          </div>
          <div>
            <label>Fin *</label>
            <input type="date" name="end_date" required>
          </div>
        </div>
        <div class="row">
          <label>Motif (optionnel)</label>
          <textarea name="reason" rows="4" placeholder="Ex.: congés payés, RTT, absence exceptionnelle…"></textarea>
        </div>
        <div class="row">
          <button type="submit">Envoyer la demande</button>
        </div>
      </form>
    <?php endif; ?>
  </main>

  <footer class="container">
    <small>&copy; <?php echo date('Y'); ?> — <?php echo SITE_NAME; ?></small>
  </footer>
</body>
</html>

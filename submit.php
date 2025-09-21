<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

if (empty($_SESSION['user'])) { header('Location: index.php?err=1'); exit; }

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    http_response_code(400); die('CSRF token invalide');
}

$user = $_SESSION['user'];
$full_name = $user['full_name'];
$email = $user['email'];
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$reason = trim($_POST['reason'] ?? '');

if (!$start_date || !$end_date) {
    header('Location: index.php?err=1'); exit;
}

$pdo = db();
$stmt = $pdo->prepare('INSERT INTO requests (user_id, full_name, email, start_date, end_date, reason, status, created_at) VALUES (?,?,?,?,?,?,"en_attente",?)');
$stmt->execute([$user['id'], $full_name, $email, $start_date, $end_date, $reason, date('c')]);

// Email à l'admin
$req_id = $pdo->lastInsertId();
$subjectAdmin = "Nouvelle demande de congé (#{$req_id}) — {$full_name}";
$link = BASE_URL . "/admin/login.php";
$htmlAdmin = "<p>Nouvelle demande de congé:</p>
<ul>
<li><strong>Employé&nbsp;:</strong> ".htmlspecialchars($full_name)."</li>
<li><strong>Email&nbsp;:</strong> ".htmlspecialchars($email)."</li>
<li><strong>Période&nbsp;:</strong> ".htmlspecialchars($start_date)." → ".htmlspecialchars($end_date)."</li>
<li><strong>Motif&nbsp;:</strong> ".nl2br(htmlspecialchars($reason))."</li>
</ul>
<p>Consulter et valider: <a href=\"$link\">$link</a></p>";
send_mail(ADMIN_EMAIL, $subjectAdmin, $htmlAdmin);

// Accusé à l'employé
$subjectUser = "Confirmation — demande de congé enregistrée (#{$req_id})";
$htmlUser = "<p>Bonjour ".htmlspecialchars($full_name).",</p>
<p>Votre demande a bien été enregistrée pour la période <strong>".htmlspecialchars($start_date)." → ".htmlspecialchars($end_date)."</strong>.</p>
<p>Vous recevrez un email une fois la demande traitée.</p>
<p>Cordialement,<br>".SITE_NAME."</p>";
send_mail($email, $subjectUser, $htmlUser);

header('Location: index.php?ok=1');

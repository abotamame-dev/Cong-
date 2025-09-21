<?php
// === Configuration de l'application de demande de congés ===

// Adresse email qui reçoit les notifications des nouvelles demandes
define('ADMIN_EMAIL', 'admin@example.com');

// Nom du site utilisé dans les emails
define('SITE_NAME', 'Portail des congés');

// URL publique du site (sans slash final). Exemple: https://votre-domaine.tld
// Mettre à jour cette valeur après déploiement pour des liens corrects dans les emails.
define('BASE_URL', 'http://localhost');

// Identifiants administrateur (login + mot de passe hashé)
define('ADMIN_LOGIN', 'admin');

// Pour générer un nouveau hash: php -r "echo password_hash('votre_mot_de_passe', PASSWORD_DEFAULT), PHP_EOL;"
define('ADMIN_PASSWORD_HASH', password_hash('changeme', PASSWORD_DEFAULT));

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Clé secrète pour CSRF et sessions (changez-la !)
define('APP_SECRET', '929a942f6a467f18de4a3e97b04a1a8a94a78bbc7a01ef15387fc14ce640ad16');

// Email d'expéditeur (From:) utilisé pour les notifications envoyées
define('MAIL_FROM', 'no-reply@example.com');
define('MAIL_FROM_NAME', SITE_NAME);

// Si votre hébergeur demande un Return-Path spécifique
define('MAIL_RETURN_PATH', 'no-reply@example.com');

// Active un log texte des emails envoyés (utile en test). Mettre à false en prod si non désiré.
define('MAIL_LOG', true);

// Emplacement du fichier SQLite
define('SQLITE_PATH', __DIR__ . '/storage.sqlite');

// Sécurité cookies
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Lax');

// Démarrage session + CSRF
if (session_status() === PHP_SESSION_NONE) {
    session_name('leaveapp');
    session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

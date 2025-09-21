<?php
require_once __DIR__ . '/db.php';

$pdo = db();

$pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at TEXT NOT NULL
);');

$pdo->exec('CREATE TABLE IF NOT EXISTS requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL,
    start_date TEXT NOT NULL,
    end_date TEXT NOT NULL,
    reason TEXT,
    status TEXT NOT NULL DEFAULT "en_attente",
    created_at TEXT NOT NULL,
    decided_at TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);');

echo "<h2>Base initialisée ✅</h2>";
echo "<p>Fichier SQLite&nbsp;: " . SQLITE_PATH . "</p>";
echo '<p><a href="index.php">Aller au formulaire</a></p>';

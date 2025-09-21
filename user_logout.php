<?php
require_once __DIR__ . '/config.php';
unset($_SESSION['user']);
session_regenerate_id(true);
header('Location: index.php');

<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/login.php');
    exit;
}

$username = sanitize_input($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === ADMIN_USER && $password === ADMIN_PASS) {
    $_SESSION['admin_authenticated'] = true;
    header('Location: ../admin/dashboard.php?message=' . urlencode('Connexion réussie. Bienvenue.'));
    exit;
}

header('Location: ../admin/login.php?error=1&message=' . urlencode('Identifiants invalides.'));
exit;

<?php
require_once __DIR__ . '/../includes/functions.php';

session_unset();
session_destroy();

header('Location: ../index.php?message=' . urlencode('Déconnexion réussie.'));
exit;

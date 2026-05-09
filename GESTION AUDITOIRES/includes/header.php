<?php
require_once __DIR__ . '/../includes/functions.php';
$rootPrefix = get_root_prefix();
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Auditoires</title>
    <link rel="stylesheet" href="<?php echo $rootPrefix; ?>css/style.php">
</head>
<body>
<header class="topbar">
    <div class="container topbar-grid">
        <div>
            <h1>Gestion des Auditoires</h1>
            <p class="topbar-subtitle">Planning intelligent, réservations sécurisées et gestion centralisée</p>
        </div>
        <nav>
            <a href="<?php echo $rootPrefix; ?>index.php">Accueil</a>
            <a href="<?php echo $rootPrefix; ?>admin/dashboard.php">Admin</a>
            <a href="<?php echo $rootPrefix; ?>admin/planning.php">Planning</a>
            <?php if (is_admin_logged_in()): ?>
                <a href="<?php echo $rootPrefix; ?>actions/logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="<?php echo $rootPrefix; ?>admin/login.php">Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
<?php echo get_alert_html(); ?>

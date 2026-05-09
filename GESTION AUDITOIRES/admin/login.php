<?php
require_once __DIR__ . '/../includes/functions.php';
if (is_admin_logged_in()) {
    header('Location: dashboard.php');
    exit;
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="login-wrapper">
    <section class="card login-card">
        <h2>Connexion administrateur</h2>
        <p class="login-note">Entrez vos identifiants pour accéder à la gestion des auditoires.</p>
        <form method="post" action="../actions/login.php">
            <div class="form-group">
                <label for="username">Utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Se connecter</button>
        </form>
    </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
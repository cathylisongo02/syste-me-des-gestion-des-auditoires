<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();
$salles = load_json('salles.json');
$promotions = load_json('promotions.json');
$options = load_json('options.json');
$courses = load_json('cours.json');
$planning = load_json('planning.json');
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="card">
    <h2>Tableau de bord Administrateur</h2>
    <p>Gérez les salles, les promotions, les options, les cours et générez le planning intelligent.</p>
</section>
<div class="card-grid">
    <div class="card">
        <h2>Salles</h2>
        <p><?php echo count($salles); ?> éléments</p>
        <a class="button" href="salles.php">Ouvrir</a>
    </div>
    <div class="card">
        <h2>Cours</h2>
        <p><?php echo count($courses); ?> éléments</p>
        <a class="button" href="cours.php">Ouvrir</a>
    </div>
    <div class="card">
        <h2>Promotions</h2>
        <p><?php echo count($promotions); ?> éléments</p>
        <a class="button" href="promotions.php">Ouvrir</a>
    </div>
    <div class="card">
        <h2>Options</h2>
        <p><?php echo count($options); ?> éléments</p>
        <a class="button" href="options.php">Ouvrir</a>
    </div>
</div>
<section class="card">
    <h2>Générer le planning</h2>
    <p>Le planning est généré automatiquement en vérifiant la capacité des salles et en évitant les conflits.</p>
    <form method="post" action="../actions/generate_planning.php">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <button type="submit" class="button button-success">Générer le planning</button>
    </form>
</section>
<section class="card">
    <h2>Planification actuelle</h2>
    <p><?php echo count($planning); ?> créneaux planifiés.</p>
    <a class="button button-secondary" href="planning.php">Voir le planning</a>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
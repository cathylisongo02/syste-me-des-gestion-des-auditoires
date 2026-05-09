<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
$salles = load_json('salles.json');
$promotions = load_json('promotions.json');
$options = load_json('options.json');
$courses = load_json('cours.json');
$planning = load_json('planning.json');
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h2>Bienvenue</h2>
    <p>Ce système gère les auditoires, la réservation intelligente des salles et la génération automatique du planning des cours.</p>
</section>
<div class="card-grid">
    <div class="card">
        <h2>Salles</h2>
        <p><?php echo count($salles); ?> salles enregistrées.</p>
    </div>
    <div class="card">
        <h2>Cours</h2>
        <p><?php echo count($courses); ?> cours disponibles.</p>
    </div>
    <div class="card">
        <h2>Promotions</h2>
        <p><?php echo count($promotions); ?> promotions listées.</p>
    </div>
    <div class="card">
        <h2>Planning</h2>
        <p><?php echo count($planning); ?> créneaux planifiés.</p>
    </div>
</div>
<section class="card">
    <h2>Planning rapide</h2>
    <?php if (empty($planning)): ?>
        <p>Aucun planning n'a encore été généré. Rendez-vous dans l'espace administrateur pour générer et visualiser le planning.</p>
        <a class="button" href="<?php echo BASE_URL; ?>admin/dashboard.php">Aller à l'administration</a>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Créneau</th>
                        <th>Cours</th>
                        <th>Salle</th>
                        <th>Groupe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($planning as $item): ?>
                        <tr>
                            <td><?php echo sanitize_input($item['creneau']); ?></td>
                            <td><?php echo sanitize_input(get_entity_label('course', $item['cours'], $courses, $salles, $promotions, $options)); ?></td>
                            <td><?php echo $item['salle'] !== null ? sanitize_input(get_entity_label('salle', $item['salle'], $courses, $salles, $promotions, $options)) : '<span class="badge">Non affectée</span>'; ?></td>
                            <td><?php echo sanitize_input($item['groupe']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
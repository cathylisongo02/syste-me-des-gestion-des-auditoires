<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();
$salles = load_json('salles.json');
$promotions = load_json('promotions.json');
$options = load_json('options.json');
$courses = load_json('cours.json');
$planning = load_json('planning.json');
$gridData = build_planning_grid($planning, $courses, $salles, $promotions, $options);
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="card">
    <h2>Planning des cours</h2>
    <p>Ce tableau présente les créneaux réservés, les salles utilisées et les groupes associés.</p>
</section>
<section class="table-wrapper card">
    <?php if (empty($planning)): ?>
        <p>Aucun planning n'est encore disponible. Vous pouvez le générer depuis le tableau de bord.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Heure</th>
                        <?php foreach ($gridData['days'] as $day): ?>
                            <th><?php echo sanitize_input($day); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gridData['rows'] as $row): ?>
                        <tr>
                            <td><strong><?php echo sanitize_input($row['time']); ?></strong></td>
                            <?php foreach ($gridData['days'] as $day): ?>
                                <td><?php echo $row[$day]; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <section class="card">
            <h3>Entrées non planifiées</h3>
            <?php $unplanned = array_filter($planning, function ($entry) { return $entry['creneau'] === 'Non planifié'; }); ?>
            <?php if (empty($unplanned)): ?>
                <p>Tous les cours ont été planifiés.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($unplanned as $entry): ?>
                        <li><?php echo sanitize_input(get_entity_label('course', $entry['cours'], $courses, $salles, $promotions, $options)); ?> - <?php echo sanitize_input($entry['groupe']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
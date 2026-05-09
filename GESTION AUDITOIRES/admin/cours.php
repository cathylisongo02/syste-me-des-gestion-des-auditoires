<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();
$courses = load_json('cours.json');
$promotions = load_json('promotions.json');
$options = load_json('options.json');
$editCourse = null;

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editCourse = find_item($courses, 'id_cours', $id);
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="card">
    <h2>Gestion des cours</h2>
    <p>Ajoutez les cours et associez-les à une promotion ou une option pour la planification.</p>
</section>
<section class="card">
    <form method="post" action="../actions/add_cours.php">
        <input type="hidden" name="id" value="<?php echo $editCourse ? $editCourse['id_cours'] : ''; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <div class="form-group">
            <label for="intitule">Intitulé du cours</label>
            <input id="intitule" name="intitule" type="text" value="<?php echo $editCourse ? sanitize_input($editCourse['intitule']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="volume_horaire">Volume horaire</label>
            <input id="volume_horaire" name="volume_horaire" type="number" min="1" value="<?php echo $editCourse ? sanitize_input($editCourse['volume_horaire']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="promotion_option">Promotion / Option</label>
            <select id="promotion_option" name="promotion_option" required>
                <option value="">Sélectionner</option>
                <?php foreach ($promotions as $promo): ?>
                    <option value="promo_<?php echo $promo['id_promotion']; ?>" <?php echo $editCourse && $editCourse['promotion_option'] === 'promo_' . $promo['id_promotion'] ? 'selected' : ''; ?>><?php echo sanitize_input($promo['libelle']); ?> (Promotion)</option>
                <?php endforeach; ?>
                <?php foreach ($options as $option): ?>
                    <option value="option_<?php echo $option['id_option']; ?>" <?php echo $editCourse && $editCourse['promotion_option'] === 'option_' . $option['id_option'] ? 'selected' : ''; ?>><?php echo sanitize_input($option['libelle']); ?> (Option)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="button"><?php echo $editCourse ? 'Mettre à jour le cours' : 'Ajouter le cours'; ?></button>
    </form>
</section>
<section class="table-wrapper card">
    <h3>Liste des cours</h3>
    <?php if (empty($courses)): ?>
        <p>Aucun cours défini pour l'instant.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Intitulé</th>
                    <th>Volume</th>
                    <th>Groupe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <?php $group = resolve_group_info($course['promotion_option'], $promotions, $options); ?>
                    <tr>
                        <td><?php echo $course['id_cours']; ?></td>
                        <td><?php echo sanitize_input($course['intitule']); ?></td>
                        <td><?php echo sanitize_input($course['volume_horaire']); ?> h</td>
                        <td><?php echo sanitize_input($group['label']); ?> (<?php echo $group['students']; ?> étudiants)</td>
                        <td>
                            <a class="small-link" href="cours.php?edit=<?php echo $course['id_cours']; ?>">Modifier</a>
                            &nbsp;|&nbsp;
                            <form class="inline-form" method="post" action="../actions/delete_entity.php" onsubmit="return confirm('Supprimer ce cours ?');">
                                <input type="hidden" name="entity" value="cours">
                                <input type="hidden" name="id" value="<?php echo $course['id_cours']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                <button type="submit" class="button-link">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
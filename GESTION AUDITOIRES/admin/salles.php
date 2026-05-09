<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();
$salles = load_json('salles.json');
$editSalle = null;

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editSalle = find_item($salles, 'id', $id);
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="card">
    <h2>Gestion des salles</h2>
    <p>Ajouter, modifier ou supprimer les salles disponibles pour la planification.</p>
</section>
<section class="card">
    <form method="post" action="../actions/add_salle.php">
        <input type="hidden" name="id" value="<?php echo $editSalle ? $editSalle['id'] : ''; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <div class="form-group">
            <label for="designation">Désignation</label>
            <input id="designation" name="designation" type="text" value="<?php echo $editSalle ? sanitize_input($editSalle['designation']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="capacite">Capacité</label>
            <input id="capacite" name="capacite" type="number" min="1" value="<?php echo $editSalle ? sanitize_input($editSalle['capacite']) : ''; ?>" required>
        </div>
        <button type="submit" class="button"><?php echo $editSalle ? 'Mettre à jour la salle' : 'Ajouter une salle'; ?></button>
    </form>
</section>
<section class="table-wrapper card">
    <h3>Liste des salles</h3>
    <?php if (empty($salles)): ?>
        <p>Aucune salle définie pour l'instant.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Désignation</th>
                    <th>Capacité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salles as $salle): ?>
                    <tr>
                        <td><?php echo $salle['id']; ?></td>
                        <td><?php echo sanitize_input($salle['designation']); ?></td>
                        <td><?php echo sanitize_input($salle['capacite']); ?></td>
                        <td>
                            <a class="small-link" href="salles.php?edit=<?php echo $salle['id']; ?>">Modifier</a>
                            &nbsp;|&nbsp;
                            <form class="inline-form" method="post" action="../actions/delete_entity.php" onsubmit="return confirm('Supprimer cette salle ?');">
                                <input type="hidden" name="entity" value="salles">
                                <input type="hidden" name="id" value="<?php echo $salle['id']; ?>">
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
<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();
$promotions = load_json('promotions.json');
$options = load_json('options.json');
$editPromo = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        header('Location: promotions.php?error=1&message=' . urlencode('Jeton CSRF invalide.'));
        exit;
    }

    $libelle = sanitize_input($_POST['libelle'] ?? '');
    $effectif_total = intval($_POST['effectif_total'] ?? 0);
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if ($libelle === '' || $effectif_total <= 0) {
        header('Location: promotions.php?error=1&message=' . urlencode('Veuillez remplir le libellé et l effectif.'));
        exit;
    }

    if ($id) {
        foreach ($promotions as &$promotion) {
            if ($promotion['id_promotion'] == $id) {
                $promotion['libelle'] = $libelle;
                $promotion['effectif_total'] = $effectif_total;
                break;
            }
        }
        save_json('promotions.json', $promotions);
        header('Location: promotions.php?message=' . urlencode('Promotion mise à jour.'));
        exit;
    }

    $promotions[] = [
        'id_promotion' => next_id($promotions, 'id_promotion'),
        'libelle' => $libelle,
        'effectif_total' => $effectif_total,
    ];
    save_json('promotions.json', $promotions);
    header('Location: promotions.php?message=' . urlencode('Promotion ajoutée.'));
    exit;
}

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editPromo = find_item($promotions, 'id_promotion', $id);
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="card">
    <h2>Gestion des promotions</h2>
    <p>Créez et modifiez les promotions qui regroupent les options.</p>
</section>
<section class="card">
    <form method="post" action="promotions.php">
        <input type="hidden" name="id" value="<?php echo $editPromo ? $editPromo['id_promotion'] : ''; ?>">
        <div class="form-group">
            <label for="libelle">Libellé</label>
            <input id="libelle" name="libelle" type="text" value="<?php echo $editPromo ? sanitize_input($editPromo['libelle']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="effectif_total">Effectif total</label>
            <input id="effectif_total" name="effectif_total" type="number" min="1" value="<?php echo $editPromo ? sanitize_input($editPromo['effectif_total']) : ''; ?>" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <button type="submit" class="button"><?php echo $editPromo ? 'Mettre à jour' : 'Ajouter'; ?></button>
    </form>
</section>
<section class="table-wrapper card">
    <h3>Liste des promotions</h3>
    <?php if (empty($promotions)): ?>
        <p>Aucune promotion définie.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Libellé</th>
                    <th>Effectif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($promotions as $promo): ?>
                    <tr>
                        <td><?php echo $promo['id_promotion']; ?></td>
                        <td><?php echo sanitize_input($promo['libelle']); ?></td>
                        <td><?php echo sanitize_input($promo['effectif_total']); ?></td>
                        <td>
                            <a class="small-link" href="promotions.php?edit=<?php echo $promo['id_promotion']; ?>">Modifier</a>
                            &nbsp;|&nbsp;
                            <form class="inline-form" method="post" action="../actions/delete_entity.php" onsubmit="return confirm('Supprimer cette promotion ?');">
                                <input type="hidden" name="entity" value="promotions">
                                <input type="hidden" name="id" value="<?php echo $promo['id_promotion']; ?>">
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
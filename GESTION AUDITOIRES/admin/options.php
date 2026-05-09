<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();
$promotions = load_json('promotions.json');
$options = load_json('options.json');
$editOption = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        header('Location: options.php?error=1&message=' . urlencode('Jeton CSRF invalide.'));
        exit;
    }

    $libelle = sanitize_input($_POST['libelle'] ?? '');
    $promotion_parente = intval($_POST['promotion_parente'] ?? 0);
    $effectif = intval($_POST['effectif'] ?? 0);
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if ($libelle === '' || $promotion_parente <= 0 || $effectif <= 0) {
        header('Location: options.php?error=1&message=' . urlencode('Veuillez remplir tous les champs de l option.'));
        exit;
    }

    if ($id) {
        foreach ($options as &$option) {
            if ($option['id_option'] == $id) {
                $option['libelle'] = $libelle;
                $option['promotion_parente'] = $promotion_parente;
                $option['effectif'] = $effectif;
                break;
            }
        }
        save_json('options.json', $options);
        header('Location: options.php?message=' . urlencode('Option mise à jour.'));
        exit;
    }

    $options[] = [
        'id_option' => next_id($options, 'id_option'),
        'libelle' => $libelle,
        'promotion_parente' => $promotion_parente,
        'effectif' => $effectif,
    ];
    save_json('options.json', $options);
    header('Location: options.php?message=' . urlencode('Option ajoutée.'));
    exit;
}

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editOption = find_item($options, 'id_option', $id);
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="card">
    <h2>Gestion des options</h2>
    <p>Ajoutez des options liées à une promotion et définissez leur effectif.</p>
</section>
<section class="card">
    <form method="post" action="options.php">
        <input type="hidden" name="id" value="<?php echo $editOption ? $editOption['id_option'] : ''; ?>">
        <div class="form-group">
            <label for="libelle">Libellé</label>
            <input id="libelle" name="libelle" type="text" value="<?php echo $editOption ? sanitize_input($editOption['libelle']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="promotion_parente">Promotion parente</label>
            <select id="promotion_parente" name="promotion_parente" required>
                <option value="">Sélectionner</option>
                <?php foreach ($promotions as $promo): ?>
                    <option value="<?php echo $promo['id_promotion']; ?>" <?php echo $editOption && $editOption['promotion_parente'] == $promo['id_promotion'] ? 'selected' : ''; ?>><?php echo sanitize_input($promo['libelle']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="effectif">Effectif</label>
            <input id="effectif" name="effectif" type="number" min="1" value="<?php echo $editOption ? sanitize_input($editOption['effectif']) : ''; ?>" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <button type="submit" class="button"><?php echo $editOption ? 'Mettre à jour' : 'Ajouter'; ?></button>
    </form>
</section>
<section class="table-wrapper card">
    <h3>Liste des options</h3>
    <?php if (empty($options)): ?>
        <p>Aucune option définie.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Libellé</th>
                    <th>Promotion</th>
                    <th>Effectif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($options as $option): ?>
                    <?php $promo = find_item($promotions, 'id_promotion', $option['promotion_parente']); ?>
                    <tr>
                        <td><?php echo $option['id_option']; ?></td>
                        <td><?php echo sanitize_input($option['libelle']); ?></td>
                        <td><?php echo $promo ? sanitize_input($promo['libelle']) : 'Promotion introuvable'; ?></td>
                        <td><?php echo sanitize_input($option['effectif']); ?></td>
                        <td>
                            <a class="small-link" href="options.php?edit=<?php echo $option['id_option']; ?>">Modifier</a>
                            &nbsp;|&nbsp;
                            <form class="inline-form" method="post" action="../actions/delete_entity.php" onsubmit="return confirm('Supprimer cette option ?');">
                                <input type="hidden" name="entity" value="options">
                                <input type="hidden" name="id" value="<?php echo $option['id_option']; ?>">
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
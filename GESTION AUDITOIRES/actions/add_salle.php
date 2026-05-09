<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/salles.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    header('Location: ../admin/salles.php?error=1&message=' . urlencode('Jeton CSRF invalide.'));
    exit;
}

$designation = sanitize_input($_POST['designation'] ?? '');
$capacite = intval($_POST['capacite'] ?? 0);
$id = isset($_POST['id']) ? intval($_POST['id']) : null;

if ($designation === '' || $capacite <= 0) {
    header('Location: ../admin/salles.php?error=1&message=' . urlencode('Veuillez fournir une désignation et une capacité valide.'));
    exit;
}

$salles = load_json('salles.json');

if ($id) {
    foreach ($salles as &$salle) {
        if ($salle['id'] == $id) {
            $salle['designation'] = $designation;
            $salle['capacite'] = $capacite;
            break;
        }
    }
    save_json('salles.json', $salles);
    $message = 'Salle mise à jour avec succès.';
} else {
    $salles[] = [
        'id' => next_id($salles, 'id'),
        'designation' => $designation,
        'capacite' => $capacite,
    ];
    save_json('salles.json', $salles);
    $message = 'Salle ajoutée avec succès.';
}

header('Location: ../admin/salles.php?message=' . urlencode($message));
exit;

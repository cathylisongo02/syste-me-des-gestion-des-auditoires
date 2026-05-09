<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/cours.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    header('Location: ../admin/cours.php?error=1&message=' . urlencode('Jeton CSRF invalide.'));
    exit;
}

$intitule = sanitize_input($_POST['intitule'] ?? '');
$volume_horaire = intval($_POST['volume_horaire'] ?? 0);
$promotion_option = sanitize_input($_POST['promotion_option'] ?? '');
$id = isset($_POST['id']) ? intval($_POST['id']) : null;

if ($intitule === '' || $volume_horaire <= 0 || $promotion_option === '') {
    header('Location: ../admin/cours.php?error=1&message=' . urlencode('Veuillez remplir tous les champs du cours.'));
    exit;
}

$courses = load_json('cours.json');

if ($id) {
    foreach ($courses as &$course) {
        if ($course['id_cours'] == $id) {
            $course['intitule'] = $intitule;
            $course['volume_horaire'] = $volume_horaire;
            $course['promotion_option'] = $promotion_option;
            break;
        }
    }
    save_json('cours.json', $courses);
    $message = 'Cours mis à jour avec succès.';
} else {
    $courses[] = [
        'id_cours' => next_id($courses, 'id_cours'),
        'intitule' => $intitule,
        'volume_horaire' => $volume_horaire,
        'promotion_option' => $promotion_option,
    ];
    save_json('cours.json', $courses);
    $message = 'Cours ajouté avec succès.';
}

header('Location: ../admin/cours.php?message=' . urlencode($message));
exit;

<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/dashboard.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    header('Location: ../admin/dashboard.php?error=1&message=' . urlencode('Jeton CSRF invalide.'));
    exit;
}

$courses = load_json('cours.json');
$promotions = load_json('promotions.json');
$options = load_json('options.json');
$salles = load_json('salles.json');

$planning = generate_planning_data($courses, $promotions, $options, $salles);
save_json('planning.json', $planning);

header('Location: ../admin/planning.php?message=' . urlencode('Planning généré avec succès.'));
exit;

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

$entity = sanitize_input($_POST['entity'] ?? '');
$id = intval($_POST['id'] ?? 0);
$allowed = ['salles', 'cours', 'promotions', 'options'];

if (!in_array($entity, $allowed, true) || $id <= 0) {
    header('Location: ../admin/dashboard.php?error=1&message=' . urlencode('Demande de suppression invalide.'));
    exit;
}

$items = load_json($entity . '.json');
$found = false;

if ($entity === 'promotions') {
    $courses = load_json('cours.json');
    foreach ($courses as $course) {
        if ($course['promotion_option'] === 'promo_' . $id) {
            header('Location: ../admin/promotions.php?error=1&message=' . urlencode('Impossible de supprimer : des cours dépendent de cette promotion.'));
            exit;
        }
    }
}

if ($entity === 'options') {
    $courses = load_json('cours.json');
    foreach ($courses as $course) {
        if ($course['promotion_option'] === 'option_' . $id) {
            header('Location: ../admin/options.php?error=1&message=' . urlencode('Impossible de supprimer : des cours dépendent de cette option.'));
            exit;
        }
    }
}

foreach ($items as $index => $item) {
    $key = $entity === 'cours' ? 'id_cours' : ($entity === 'promotions' ? 'id_promotion' : ($entity === 'options' ? 'id_option' : 'id'));
    if (isset($item[$key]) && $item[$key] == $id) {
        unset($items[$index]);
        $found = true;
        break;
    }
}

if ($found) {
    save_json($entity . '.json', array_values($items));

    if ($entity === 'salles' || $entity === 'cours') {
        $planning = load_json('planning.json');
        $planning = array_values(array_filter($planning, function ($entry) use ($entity, $id) {
            if ($entity === 'salles') {
                return $entry['salle'] != $id;
            }
            return $entry['cours'] != $id;
        }));
        save_json('planning.json', $planning);
    }

    header('Location: ../admin/' . $entity . '.php?message=' . urlencode(ucfirst($entity) . ' supprimé avec succès.'));
    exit;
}

header('Location: ../admin/dashboard.php?error=1&message=' . urlencode('Élément introuvable.'));
exit;

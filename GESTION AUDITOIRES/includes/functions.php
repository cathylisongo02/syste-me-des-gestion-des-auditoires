<?php
require_once __DIR__ . '/../config.php';

function load_json($filename) {
    $path = DATA_DIR . DIRECTORY_SEPARATOR . $filename;
    if (!file_exists($path)) {
        return [];
    }
    $content = file_get_contents($path);
    return json_decode($content, true) ?: [];
}

function save_json($filename, $data) {
    $path = DATA_DIR . DIRECTORY_SEPARATOR . $filename;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($path, $json);
}

function sanitize_input($value) {
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

function find_item($items, $key, $value) {
    foreach ($items as $item) {
        if (isset($item[$key]) && $item[$key] == $value) {
            return $item;
        }
    }
    return null;
}

function next_id($items, $key = 'id') {
    $max = 0;
    foreach ($items as $item) {
        if (isset($item[$key]) && is_numeric($item[$key]) && (int)$item[$key] > $max) {
            $max = (int)$item[$key];
        }
    }
    return $max + 1;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function get_root_prefix() {
    return strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false ? '../' : '';
}

function is_admin_logged_in() {
    return !empty($_SESSION['admin_authenticated']);
}

function require_admin_auth() {
    if (!is_admin_logged_in()) {
        header('Location: ../admin/login.php?error=1&message=' . urlencode('Connectez-vous pour accéder à l administration.'));
        exit;
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function get_alert_html() {
    $output = '';
    if (!empty($_GET['message'])) {
        $type = !empty($_GET['error']) ? 'alert-error' : 'alert-success';
        $message = sanitize_input($_GET['message']);
        $output .= "<div class=\"alert $type\">$message</div>";
    }
    return $output;
}

function resolve_group_info($promotion_option, $promotions, $options) {
    if (strpos($promotion_option, 'option_') === 0) {
        $id = (int)substr($promotion_option, 7);
        $option = find_item($options, 'id_option', $id);
        if ($option) {
            return [
                'label' => $option['libelle'],
                'students' => (int)$option['effectif'],
                'type' => 'option',
            ];
        }
    }
    if (strpos($promotion_option, 'promo_') === 0) {
        $id = (int)substr($promotion_option, 6);
        $promotion = find_item($promotions, 'id_promotion', $id);
        if ($promotion) {
            return [
                'label' => $promotion['libelle'],
                'students' => (int)$promotion['effectif_total'],
                'type' => 'promotion',
            ];
        }
    }
    return [
        'label' => 'Groupe inconnu',
        'students' => 0,
        'type' => 'unknown',
    ];
}

function is_salle_available($salle_id, $creneau, $planning) {
    foreach ($planning as $item) {
        if ($item['salle'] == $salle_id && $item['creneau'] === $creneau) {
            return false;
        }
    }
    return true;
}

function get_available_salles($required_capacity, $creneau, $planning, $salles) {
    $available = [];
    foreach ($salles as $salle) {
        if ((int)$salle['capacite'] >= $required_capacity && is_salle_available($salle['id'], $creneau, $planning)) {
            $available[] = $salle;
        }
    }
    usort($available, function ($a, $b) {
        return $a['capacite'] <=> $b['capacite'];
    });
    return $available;
}

function generate_planning_data($courses, $promotions, $options, $salles) {
    $planning = [];
    $coursesToSchedule = [];
    foreach ($courses as $course) {
        $group = resolve_group_info($course['promotion_option'], $promotions, $options);
        $coursesToSchedule[] = [
            'course' => $course,
            'group' => $group,
        ];
    }

    usort($coursesToSchedule, function ($a, $b) {
        return $b['group']['students'] <=> $a['group']['students'];
    });

    foreach ($coursesToSchedule as $entry) {
        $course = $entry['course'];
        $group = $entry['group'];
        $assigned = false;

        foreach (AVAILABLE_SLOTS as $slot) {
            $availableSalles = get_available_salles($group['students'], $slot, $planning, $salles);
            if (!empty($availableSalles)) {
                $planning[] = [
                    'creneau' => $slot,
                    'salle' => $availableSalles[0]['id'],
                    'cours' => $course['id_cours'],
                    'groupe' => $group['label'],
                ];
                $assigned = true;
                break;
            }
        }

        if (!$assigned) {
            $planning[] = [
                'creneau' => 'Non planifié',
                'salle' => null,
                'cours' => $course['id_cours'],
                'groupe' => $group['label'],
            ];
        }
    }

    return $planning;
}

function get_entity_label($type, $id, $courses, $salles, $promotions, $options) {
    if ($type === 'course') {
        $item = find_item($courses, 'id_cours', $id);
        return $item ? $item['intitule'] : 'Cours inconnu';
    }
    if ($type === 'salle') {
        $item = find_item($salles, 'id', $id);
        return $item ? $item['designation'] : 'Salle indisponible';
    }
    return 'Inconnu';
}

function get_time_slot_columns() {
    $days = [];
    foreach (AVAILABLE_SLOTS as $slot) {
        [$day, $time] = explode(' ', $slot, 2);
        $days[$day][] = $time;
    }
    return $days;
}

function build_planning_grid($planning, $courses, $salles, $promotions, $options) {
    $days = array_keys(get_time_slot_columns());
    $times = array_unique(array_map(function ($slot) {
        return explode(' ', $slot, 2)[1];
    }, AVAILABLE_SLOTS));
    sort($times);

    $grid = [];
    foreach ($times as $time) {
        $row = ['time' => $time];
        foreach ($days as $day) {
            $slot = "$day $time";
            $match = null;
            foreach ($planning as $entry) {
                if ($entry['creneau'] === $slot) {
                    $match = $entry;
                    break;
                }
            }
            if ($match) {
                $courseLabel = sanitize_input(get_entity_label('course', $match['cours'], $courses, $salles, $promotions, $options));
                $salleLabel = $match['salle'] ? sanitize_input(get_entity_label('salle', $match['salle'], $courses, $salles, $promotions, $options)) : 'Aucune salle';
                $row[$day] = "$courseLabel<br><span class=\"badge\">$salleLabel</span><br><small>$match[groupe]</small>";
            } else {
                $row[$day] = '';
            }
        }
        $grid[] = $row;
    }

    return ['days' => $days, 'rows' => $grid];
}

<?php
require_once __DIR__ . '/config.php';

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function current_user() {
    if (!is_logged_in()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'full_name' => $_SESSION['full_name'],
    ];
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . base_url() . 'index.php');
        exit;
    }
}

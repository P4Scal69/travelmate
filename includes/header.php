<?php
require_once __DIR__ . '/auth.php';
$page_title = $page_title ?? APP_NAME;
$active = $active ?? '';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover" />
    <meta name="theme-color" content="#0e7c66" />
    <title><?php echo htmlspecialchars($page_title); ?> · <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <header class="app-bar">
        <div class="app-bar-inner">
            <span class="app-logo">✈️</span>
            <span class="app-title"><?php echo htmlspecialchars(APP_NAME); ?></span>
            <?php if ($user): ?>
                <span class="app-user" id="navUser"><?php echo htmlspecialchars($user['full_name']); ?></span>
            <?php endif; ?>
        </div>
    </header>
    <main class="app-content">

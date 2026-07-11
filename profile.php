<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$user = current_user();
$uid = $user['id'];

$stmt = $pdo->prepare('SELECT destination, budget, country FROM trips WHERE user_id = ? ORDER BY start_date ASC');
$stmt->execute([$uid]);
$trips = $stmt->fetchAll();

$total = $pdo->prepare('SELECT COUNT(*) c, COALESCE(SUM(budget),0) b, COALESCE(AVG(budget),0) a FROM trips WHERE user_id = ?');
$total->execute([$uid]);
$stats = $total->fetch();

$page_title = 'Profile';
$active = 'profile';
require_once __DIR__ . '/includes/header.php';
?>
<section class="profile-head">
    <div class="avatar"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></div>
    <div>
        <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
        <span class="muted">@<?php echo htmlspecialchars($user['username']); ?></span>
    </div>
</section>

<section class="stat-grid">
    <div class="stat-card"><span class="stat-num"><?php echo (int)$stats['c']; ?></span><span class="stat-label">Trips</span></div>
    <div class="stat-card accent"><span class="stat-num">RM<?php echo number_format((int)$stats['b']); ?></span><span class="stat-label">Total</span></div>
    <div class="stat-card"><span class="stat-num">RM<?php echo number_format((int)$stats['a']); ?></span><span class="stat-label">Avg</span></div>
</section>

<section class="card">
    <h3>Budget by trip</h3>
    <canvas id="budgetChart" height="180"></canvas>
</section>

<section class="card settings">
    <h3>Preferences</h3>
    <label class="switch-row">
        <span>🌙 Dark mode</span>
        <input type="checkbox" id="darkToggle" />
    </label>
    <label class="switch-row">
        <span>Show budget as primary info</span>
        <input type="checkbox" id="budgetToggle" />
    </label>
</section>

<section class="card">
    <h3>Device</h3>
    <p class="muted" id="deviceInfo">Loading device info…</p>
</section>

<section class="row-actions">
    <a class="btn btn-primary" href="home.php">Dashboard</a>
    <a class="btn danger" href="logout.php">Log out</a>
</section>

<script>
window.__CHART_LABELS = <?php echo json_encode(array_map(fn($t) => $t['destination'], $trips)); ?>;
window.__CHART_VALUES = <?php echo json_encode(array_map(fn($t) => (int)$t['budget'], $trips)); ?>;
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$user = current_user();
$uid = $user['id'];

$total = $pdo->prepare("SELECT COUNT(*) AS c, COALESCE(SUM(budget),0) AS b FROM trips WHERE user_id = ?");
$total->execute([$uid]);
$stats = $total->fetch();

$upcoming = $pdo->prepare("SELECT * FROM trips WHERE user_id = ? AND start_date >= CURDATE() ORDER BY start_date ASC LIMIT 1");
$upcoming->execute([$uid]);
$next = $upcoming->fetch();

$recent = $pdo->prepare("SELECT * FROM trips WHERE user_id = ? ORDER BY created_at DESC LIMIT 3");
$recent->execute([$uid]);
$recentTrips = $recent->fetchAll();

$page_title = 'Home';
$active = 'home';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <h2>Hi, <?php echo htmlspecialchars(explode(' ', $user['full_name'])[0]); ?> 👋</h2>
    <p>Plan smarter trips, track budgets, and check the weather on the go.</p>
</section>

<section class="stat-grid">
    <div class="stat-card">
        <span class="stat-num"><?php echo (int)$stats['c']; ?></span>
        <span class="stat-label">Trips</span>
    </div>
    <div class="stat-card accent">
        <span class="stat-num">RM<?php echo number_format((int)$stats['b']); ?></span>
        <span class="stat-label">Total Budget</span>
    </div>
</section>

<section class="card weather-card" id="weatherCard">
    <div class="weather-head">
        <h3>Weather near you</h3>
        <button class="btn btn-mini" id="refreshWeather" type="button">⟳</button>
    </div>
    <div id="weatherBody" class="weather-body">
        <p class="muted">Tap refresh to share your location and see live weather.</p>
    </div>
</section>

<?php if ($next): ?>
<section class="card next-card">
    <h3>Upcoming trip</h3>
    <div class="next-row">
        <div>
            <strong><?php echo htmlspecialchars($next['destination']); ?></strong>,
            <?php echo htmlspecialchars($next['country']); ?><br />
            <span class="muted"><?php echo date('d M Y', strtotime($next['start_date'])); ?></span>
        </div>
        <a class="btn btn-mini" href="trip_detail.php?id=<?php echo $next['id']; ?>">View</a>
    </div>
</section>
<?php endif; ?>

<section>
    <h3 class="section-title">Recent trips</h3>
    <div class="trip-list">
        <?php if (empty($recentTrips)): ?>
            <p class="muted">No trips yet. Tap ➕ to add your first adventure.</p>
        <?php else: foreach ($recentTrips as $t): ?>
            <a class="trip-item" href="trip_detail.php?id=<?php echo $t['id']; ?>">
                <div class="trip-thumb"><?php echo strtoupper(substr($t['destination'], 0, 1)); ?></div>
                <div class="trip-meta">
                    <strong><?php echo htmlspecialchars($t['destination']); ?></strong>
                    <span class="muted"><?php echo htmlspecialchars($t['country']); ?> · <?php echo date('d M Y', strtotime($t['start_date'])); ?></span>
                </div>
                <span class="trip-budget">RM<?php echo number_format((int)$t['budget']); ?></span>
            </a>
        <?php endforeach; endif; ?>
    </div>
</section>

<section class="quick-actions">
    <a class="quick-btn" href="trip_form.php">➕<span>New Trip</span></a>
    <a class="quick-btn" href="trips.php">🧳<span>All Trips</span></a>
    <a class="quick-btn" href="profile.php">📊<span>Stats</span></a>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

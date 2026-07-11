<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$user = current_user();
$uid = $user['id'];
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM trips WHERE user_id = ? AND (destination LIKE ? OR country LIKE ? OR notes LIKE ?) ORDER BY start_date DESC");
    $like = "%$q%";
    $stmt->execute([$uid, $like, $like, $like]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM trips WHERE user_id = ? ORDER BY start_date DESC");
    $stmt->execute([$uid]);
}
$trips = $stmt->fetchAll();

$page_title = 'My Trips';
$active = 'trips';
require_once __DIR__ . '/includes/header.php';
?>
<section class="screen-head">
    <h2>My Trips</h2>
    <span class="count-pill"><?php echo count($trips); ?></span>
</section>

<div class="search-bar">
    <input type="search" id="searchInput" placeholder="Search destination, country, notes…"
           value="<?php echo htmlspecialchars($q); ?>" />
</div>

<div class="trip-list" id="tripList">
    <?php if (empty($trips)): ?>
        <div class="empty-state">
            <p>No trips found.</p>
            <a class="btn btn-primary" href="trip_form.php">Add your first trip</a>
        </div>
    <?php else: foreach ($trips as $t): ?>
        <div class="trip-item" data-search="<?php echo htmlspecialchars(strtolower($t['destination'] . ' ' . $t['country'] . ' ' . $t['notes'])); ?>">
            <a class="trip-main" href="trip_detail.php?id=<?php echo $t['id']; ?>">
                <div class="trip-thumb"><?php echo strtoupper(substr($t['destination'], 0, 1)); ?></div>
                <div class="trip-meta">
                    <strong><?php echo htmlspecialchars($t['destination']); ?></strong>
                    <span class="muted"><?php echo htmlspecialchars($t['country']); ?> · <?php echo date('d M Y', strtotime($t['start_date'])); ?></span>
                    <span class="stars"><?php echo str_repeat('★', (int)$t['rating']) . str_repeat('☆', 5 - (int)$t['rating']); ?></span>
                </div>
                <span class="trip-budget">RM<?php echo number_format((int)$t['budget']); ?></span>
            </a>
            <div class="row-actions">
                <a class="btn btn-mini" href="trip_form.php?id=<?php echo $t['id']; ?>">Edit</a>
                <button class="btn btn-mini danger" data-delete="<?php echo $t['id']; ?>" type="button">Delete</button>
            </div>
        </div>
    <?php endforeach; endif; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

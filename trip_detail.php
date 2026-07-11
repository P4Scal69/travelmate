<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$user = current_user();
$uid = $user['id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare('SELECT * FROM trips WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $uid]);
$trip = $stmt->fetch();
if (!$trip) { header('Location: trips.php'); exit; }

$hasCoords = $trip['latitude'] !== null && $trip['longitude'] !== null;

$page_title = $trip['destination'];
$active = 'trips';
require_once __DIR__ . '/includes/header.php';
?>
<section class="detail-head">
    <a class="back" href="trips.php">‹ Back</a>
    <h2><?php echo htmlspecialchars($trip['destination']); ?></h2>
    <span class="muted"><?php echo htmlspecialchars($trip['country']); ?></span>
</section>

<?php if (!empty($trip['photo'])): ?>
    <img class="detail-photo" src="<?php echo htmlspecialchars($trip['photo']); ?>" alt="trip photo" />
<?php endif; ?>

<section class="card detail-stats">
    <div><span class="muted">Date</span><strong><?php echo date('d M Y', strtotime($trip['start_date'])); ?></strong></div>
    <div><span class="muted">Budget</span><strong>RM<?php echo number_format((int)$trip['budget']); ?></strong></div>
    <div><span class="muted">Rating</span><strong><?php echo str_repeat('★', (int)$trip['rating']); ?></strong></div>
</section>

<?php if ($trip['notes']): ?>
<section class="card"><p><?php echo nl2br(htmlspecialchars($trip['notes'])); ?></p></section>
<?php endif; ?>

<section class="card weather-card">
    <div class="weather-head">
        <h3>Weather at destination</h3>
        <button class="btn btn-mini" id="weatherBtn" type="button">Check</button>
    </div>
    <div id="detailWeather" class="weather-body">
        <p class="muted">Press Check to load live weather from Open-Meteo.</p>
    </div>
</section>

<?php if ($hasCoords): ?>
<section class="card">
    <h3>Location</h3>
    <div id="map" class="map"></div>
</section>
<?php endif; ?>

<section class="row-actions detail-actions">
    <a class="btn btn-primary" href="trip_form.php?id=<?php echo $trip['id']; ?>">Edit</a>
    <button class="btn danger" data-delete="<?php echo $trip['id']; ?>" type="button">Delete</button>
</section>

<?php if ($hasCoords): ?>
<script>
window.__TRIP_LAT = <?php echo $trip['latitude']; ?>;
window.__TRIP_LON = <?php echo $trip['longitude']; ?>;
window.__TRIP_NAME = <?php echo json_encode($trip['destination'] . ', ' . $trip['country']); ?>;
</script>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

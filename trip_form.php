<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$user = current_user();
$uid = $user['id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$trip = null;

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM trips WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $uid]);
    $trip = $stmt->fetch();
    if (!$trip) { header('Location: trips.php'); exit; }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination = trim($_POST['destination'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $budget = (int)($_POST['budget'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 3);
    $notes = trim($_POST['notes'] ?? '');
    $latitude = $_POST['latitude'] !== '' ? (float)$_POST['latitude'] : null;
    $longitude = $_POST['longitude'] !== '' ? (float)$_POST['longitude'] : null;
    $photo = $trip['photo'] ?? null;

    if (!$destination || !$country || !$start_date || $budget <= 0) {
        $error = 'Destination, country, date and a budget > 0 are required.';
    } else {
        if (!empty($_FILES['photo']['name'])) {
            $dir = __DIR__ . '/assets/uploads';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $safe = 'trip_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $dir . '/' . $safe);
            $photo = 'assets/uploads/' . $safe;
        }

        if ($id) {
            $pdo->prepare('UPDATE trips SET destination=?, country=?, start_date=?, budget=?, rating=?, notes=?, latitude=?, longitude=?, photo=? WHERE id=? AND user_id=?')
                ->execute([$destination, $country, $start_date, $budget, $rating, $notes, $latitude, $longitude, $photo, $id, $uid]);
        } else {
            $pdo->prepare('INSERT INTO trips (user_id, destination, country, start_date, budget, rating, notes, latitude, longitude, photo) VALUES (?,?,?,?,?,?,?,?,?,?)')
                ->execute([$uid, $destination, $country, $start_date, $budget, $rating, $notes, $latitude, $longitude, $photo]);
        }
        header('Location: trips.php?saved=1');
        exit;
    }
}

$page_title = $id ? 'Edit Trip' : 'New Trip';
$active = $id ? 'trips' : 'add';
require_once __DIR__ . '/includes/header.php';
?>
<section class="screen-head">
    <h2><?php echo $id ? 'Edit Trip' : 'New Trip'; ?></h2>
</section>

<?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="card form" id="tripForm">
    <label class="field">
        <span>Destination *</span>
        <input type="text" name="destination" id="destination" required
               value="<?php echo htmlspecialchars($trip['destination'] ?? ''); ?>" placeholder="e.g. Paris" />
    </label>
    <label class="field">
        <span>Country *</span>
        <input type="text" name="country" id="country" required
               value="<?php echo htmlspecialchars($trip['country'] ?? ''); ?>" placeholder="e.g. France" />
    </label>
    <label class="field">
        <span>Start date *</span>
        <input type="date" name="start_date" id="start_date" required
               value="<?php echo htmlspecialchars($trip['start_date'] ?? ''); ?>" />
    </label>
    <label class="field">
        <span>Budget (RM) *</span>
        <input type="number" name="budget" id="budget" min="1" required
               value="<?php echo htmlspecialchars($trip['budget'] ?? ''); ?>" placeholder="0" />
    </label>
    <label class="field">
        <span>Rating</span>
        <select name="rating" id="rating">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?php echo $i; ?>" <?php echo ($trip['rating'] ?? 3) == $i ? 'selected' : ''; ?>><?php echo $i; ?> ★</option>
            <?php endfor; ?>
        </select>
    </label>
    <label class="field">
        <span>Notes</span>
        <textarea name="notes" id="notes" rows="3" placeholder="Things to do, reminders…"><?php echo htmlspecialchars($trip['notes'] ?? ''); ?></textarea>
    </label>

    <div class="field">
        <span>Location (GPS)</span>
        <div class="coord-row">
            <input type="text" name="latitude" id="latitude" readonly placeholder="Latitude"
                   value="<?php echo htmlspecialchars($trip['latitude'] ?? ''); ?>" />
            <input type="text" name="longitude" id="longitude" readonly placeholder="Longitude"
                   value="<?php echo htmlspecialchars($trip['longitude'] ?? ''); ?>" />
            <button type="button" class="btn btn-mini" id="gpsBtn">📍 GPS</button>
        </div>
    </div>

    <div class="field">
        <span>Photo (Camera)</span>
        <input type="file" name="photo" id="photo" accept="image/*" capture="camera" />
        <?php if (!empty($trip['photo'])): ?>
            <img class="preview" src="<?php echo htmlspecialchars($trip['photo']); ?>" alt="trip photo" />
        <?php endif; ?>
    </div>

    <button class="btn btn-primary" type="submit">Save Trip</button>
    <a class="btn btn-ghost" href="trips.php">Cancel</a>
</form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

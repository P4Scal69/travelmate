<?php
$active = $active ?? '';
$show_nav = $show_nav ?? true;
?>
    </main>
    <?php if ($show_nav && !empty($user)): ?>
    <nav class="bottom-nav">
        <a href="home.php" class="nav-item <?php echo $active === 'home' ? 'active' : ''; ?>">
            <span class="nav-icon">🏠</span><span class="nav-label">Home</span>
        </a>
        <a href="trips.php" class="nav-item <?php echo $active === 'trips' ? 'active' : ''; ?>">
            <span class="nav-icon">🧳</span><span class="nav-label">Trips</span>
        </a>
        <a href="trip_form.php" class="nav-item <?php echo $active === 'add' ? 'active' : ''; ?>">
            <span class="nav-icon">➕</span><span class="nav-label">Add</span>
        </a>
        <a href="profile.php" class="nav-item <?php echo $active === 'profile' ? 'active' : ''; ?>">
            <span class="nav-icon">👤</span><span class="nav-label">Me</span>
        </a>
    </nav>
    <?php endif; ?>
    <div id="toast" class="toast" role="status" aria-live="polite"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>

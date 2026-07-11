<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$show_nav = false;
$page_title = 'Login';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';

    if ($action === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['full_name'] = $row['full_name'];
            header('Location: home.php');
            exit;
        }
        $error = 'Invalid username or password.';
    } elseif ($action === 'register') {
        $username = trim($_POST['username'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($username && $full_name && strlen($password) >= 6) {
            $check = $pdo->prepare('SELECT id FROM users WHERE username = ?');
            $check->execute([$username]);
            if ($check->fetch()) {
                $error = 'Username already taken.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $pdo->prepare('INSERT INTO users (username, password, full_name) VALUES (?, ?, ?)')
                    ->execute([$username, $hash, $full_name]);
                $success = 'Account created. Please sign in.';
            }
        } else {
            $error = 'Fill all fields. Password must be at least 6 characters.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="auth-wrap">
    <div class="brand">
        <div class="brand-logo">✈️</div>
        <h1>TravelMate</h1>
        <p class="brand-sub">Smart Trip Planner & Travel Companion</p>
    </div>

    <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

    <form method="post" class="card" id="loginForm" autocomplete="off">
        <input type="hidden" name="action" value="login" />
        <label class="field">
            <span>Username</span>
            <input type="text" name="username" required placeholder="e.g. student" />
        </label>
        <label class="field">
            <span>Password</span>
            <input type="password" name="password" required placeholder="••••••" />
        </label>
        <button class="btn btn-primary" type="submit">Sign In</button>
        <button class="btn btn-ghost" type="button" id="showRegister">Create account</button>
        <p class="hint">Demo login — user: <b>student</b> · pass: <b>student123</b></p>
    </form>

    <form method="post" class="card hidden" id="registerForm" autocomplete="off">
        <input type="hidden" name="action" value="register" />
        <label class="field">
            <span>Full Name</span>
            <input type="text" name="full_name" required placeholder="Your name" />
        </label>
        <label class="field">
            <span>Username</span>
            <input type="text" name="username" required placeholder="Choose a username" />
        </label>
        <label class="field">
            <span>Password</span>
            <input type="password" name="password" required minlength="6" placeholder="Min 6 characters" />
        </label>
        <button class="btn btn-primary" type="submit">Register</button>
        <button class="btn btn-ghost" type="button" id="showLogin">Back to sign in</button>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/openai.php';
requireLogin();

$config = json_decode(file_get_contents(__DIR__ . '/../storage/installed.json'), true);
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config['openai_key'] = trim($_POST['openai_key'] ?? $config['openai_key']);
    $config['openai_model'] = $_POST['openai_model'] ?? 'gpt-4o-mini';
    $config['language'] = $_POST['language'] ?? 'en';
    
    if (!empty($_POST['new_password'])) {
        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $config['password_hash'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $message = 'Password updated successfully.';
            $messageType = 'success';
        } else {
            $message = 'Passwords do not match.';
            $messageType = 'danger';
        }
    }
    
    $config['username'] = $_POST['username'] ?? $config['username'];
    
    file_put_contents(__DIR__ . '/../storage/installed.json', json_encode($config, JSON_PRETTY_PRINT));
    
    if (empty($message)) {
        $message = 'Settings saved successfully.';
        $messageType = 'success';
    }
}

$models = ['gpt-4o', 'gpt-4o-mini', 'gpt-4-turbo', 'gpt-3.5-turbo'];
$languages = getLanguages();
$apiValid = !empty($config['openai_key']) ? checkApiKey($config['openai_key']) : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Thinker AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="main-content flex-grow-1">
            <div class="p-4">
                <h2 class="fw-bold mb-4">⚙️ Settings</h2>
                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?>"><?= safe_output($message) ?></div>
                <?php endif; ?>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card p-4">
                            <h5 class="mb-3">OpenAI Configuration</h5>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label">API Key</label>
                                    <?php if (!empty($config['openai_key'])): ?>
                                        <span class="badge <?= $apiValid ? 'bg-success' : 'bg-warning text-dark' ?>">
                                            <?= $apiValid ? '✓ Connected' : '⚠ Check key' ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <input type="password" name="openai_key" form="settings-form" class="form-control" value="<?= safe_output($config['openai_key'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Model</label>
                                <select name="openai_model" form="settings-form" class="form-select">
                                    <?php foreach ($models as $m): ?>
                                        <option value="<?= $m ?>" <?= ($config['openai_model'] ?? '') === $m ? 'selected' : '' ?>><?= $m ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card p-4">
                            <h5 class="mb-3">Account Settings</h5>
                            <form method="post" id="settings-form">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" value="<?= safe_output($config['username'] ?? 'admin') ?>">
                                </div>
                                <hr>
                                <h6>Change Password</h6>
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control" minlength="6">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" minlength="6">
                                </div>
                                <button type="submit" class="btn btn-primary">💾 Save Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

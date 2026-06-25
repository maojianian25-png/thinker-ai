<?php
/**
 * Thinker AI - Installation Wizard
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thinker AI - Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; display: flex; align-items: center; min-height: 100vh; }
        .install-box { max-width: 600px; margin: 0 auto; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px 16px 0 0 !important; padding: 24px; text-align: center; }
        .step { color: rgba(255,255,255,0.7); font-size: 14px; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 32px; border-radius: 8px; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(102,126,234,0.4); }
    </style>
</head>
<body>
    <div class="container install-box">
        <div class="text-center mb-4">
            <h1 style="font-weight:800; color:#667eea;">Thinker AI</h1>
            <p class="text-muted">Installation Wizard</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h4 class="mb-1">🚀 Setup Your AI Content Generator</h4>
                <div class="step">Step 1 of 2</div>
            </div>
            <div class="card-body p-4">
                <?php
                $error = '';
                $success = '';
                $installed = file_exists(__DIR__ . '/storage/installed.json');
                
                if ($installed) {
                    echo '<div class="alert alert-info">✅ Thinker AI is already installed. <a href="admin/">Go to Admin Panel</a></div>';
                    exit;
                }
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $username = trim($_POST['username'] ?? 'admin');
                    $password = $_POST['password'] ?? '';
                    $password_confirm = $_POST['password_confirm'] ?? '';
                    $openai_key = trim($_POST['openai_key'] ?? '');
                    
                    if (strlen($password) < 6) {
                        $error = 'Password must be at least 6 characters.';
                    } elseif ($password !== $password_confirm) {
                        $error = 'Passwords do not match.';
                    } elseif (empty($openai_key) || !str_starts_with($openai_key, 'sk-')) {
                        $error = 'Please enter a valid OpenAI API key (starts with sk-).';
                    } else {
                        $data = [
                            'username' => $username,
                            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                            'openai_key' => $openai_key,
                            'openai_model' => 'gpt-4o-mini',
                            'language' => 'en',
                            'installed_at' => date('Y-m-d H:i:s'),
                        ];
                        
                        $storageDir = __DIR__ . '/storage';
                        if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);
                        
                        file_put_contents(__DIR__ . '/storage/installed.json', json_encode($data, JSON_PRETTY_PRINT));
                        file_put_contents(__DIR__ . '/storage/content.json', json_encode([]));
                        
                        $_SESSION['thinker_logged_in'] = true;
                        $_SESSION['thinker_username'] = $username;
                        $_SESSION['thinker_login_time'] = time();
                        
                        $success = 'Installation complete! Redirecting...';
                        echo '<meta http-equiv="refresh" content="2;url=admin/">';
                    }
                }
                
                if ($error) echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
                if ($success) echo '<div class="alert alert-success">' . htmlspecialchars($success) . '</div>';
                ?>
                
                <form method="post">
                    <h5 class="mb-3">🔐 Admin Account</h5>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="admin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirm" class="form-control" minlength="6" required>
                    </div>
                    
                    <hr class="my-4">
                    <h5 class="mb-3">🤖 OpenAI Configuration</h5>
                    <div class="mb-3">
                        <label class="form-label">OpenAI API Key</label>
                        <input type="text" name="openai_key" class="form-control" placeholder="sk-..." required>
                        <div class="form-text">Get your key from <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com</a>. You need at least $5 credit.</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mt-3">🚀 Complete Installation</button>
                </form>
            </div>
        </div>
        
        <p class="text-center text-muted mt-3">
            <small>Thinker AI v1.0 — AI-Powered Content Generation</small>
        </p>
    </div>
</body>
</html>

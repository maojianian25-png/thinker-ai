<nav class="sidebar bg-dark text-white">
    <div class="p-3">
        <h5 class="fw-bold mb-4" style="color: #667eea;">Thinker AI</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="index.php" class="nav-link text-white <?= basename($_SERVER['SCRIPT_NAME']) === 'index.php' ? 'active' : '' ?>">
                    📊 Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="generate.php" class="nav-link text-white <?= basename($_SERVER['SCRIPT_NAME']) === 'generate.php' ? 'active' : '' ?>">
                    ✍️ Generate Content
                </a>
            </li>
            <li class="nav-item">
                <a href="history.php" class="nav-link text-white <?= basename($_SERVER['SCRIPT_NAME']) === 'history.php' ? 'active' : '' ?>">
                    📁 Content History
                </a>
            </li>
            <li class="nav-item">
                <a href="settings.php" class="nav-link text-white <?= basename($_SERVER['SCRIPT_NAME']) === 'settings.php' ? 'active' : '' ?>">
                    ⚙️ Settings
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="logout.php" class="nav-link text-white-50">🚪 Logout</a>
            </li>
        </ul>
    </div>
</nav>

<style>
.sidebar {
    width: 240px;
    min-height: 100vh;
    position: sticky;
    top: 0;
}
.sidebar .nav-link {
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 2px;
    transition: all 0.2s;
}
.sidebar .nav-link:hover {
    background: rgba(255,255,255,0.1);
}
.sidebar .nav-link.active {
    background: rgba(102,126,234,0.3);
    color: #667eea !important;
    font-weight: 600;
}
.main-content {
    background: #f8f9fa;
    min-height: 100vh;
}
.stat-card {
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.stat-card h3 {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 4px;
}
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
}
.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102,126,234,0.4);
}
</style>

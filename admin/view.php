<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = $_GET['id'] ?? '';
$contentList = loadContent();
$item = null;
foreach ($contentList as $c) {
    if ($c['id'] === $id) { $item = $c; break; }
}
if (!$item) { header('Location: history.php'); exit; }

$types = getContentTypes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Content - Thinker AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="main-content flex-grow-1">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">📄 <?= safe_output($item['title']) ?></h2>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">🖨️ Print</button>
                        <a href="history.php" class="btn btn-outline-primary btn-sm">← Back</a>
                    </div>
                </div>
                <div class="mb-3">
                    <span class="badge bg-secondary"><?= $types[$item['type']] ?? $item['type'] ?></span>
                    <span class="badge bg-info"><?= safe_output($item['language'] ?? 'en') ?></span>
                    <span class="badge bg-light text-dark"><?= formatDate($item['created_at']) ?></span>
                    <span class="badge bg-light text-dark"><?= str_word_count(strip_tags($item['body'] ?? '')) ?> words</span>
                </div>
                <div class="card"><div class="card-body"><?= $item['body'] ?></div></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

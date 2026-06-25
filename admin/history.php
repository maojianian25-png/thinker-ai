<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$contentList = loadContent();
$contentList = array_reverse($contentList);
$types = getContentTypes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content History - Thinker AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="main-content flex-grow-1">
            <div class="p-4">
                <h2 class="fw-bold mb-4">📁 Content History</h2>
                <div class="card">
                    <div class="card-body p-0">
                        <?php if (empty($contentList)): ?>
                            <div class="text-center py-5">
                                <p class="text-muted">No content generated yet. <a href="generate.php">Create your first piece!</a></p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead><tr><th>Title</th><th>Type</th><th>Language</th><th>Words</th><th>Created</th><th>Actions</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($contentList as $item): ?>
                                        <tr>
                                            <td><a href="view.php?id=<?= $item['id'] ?>"><?= safe_output(truncate($item['title'], 60)) ?></a></td>
                                            <td><span class="badge bg-secondary"><?= $types[$item['type']] ?? $item['type'] ?></span></td>
                                            <td><?= safe_output($item['language'] ?? 'en') ?></td>
                                            <td><?= str_word_count(strip_tags($item['body'] ?? '')) ?></td>
                                            <td><?= formatDate($item['created_at']) ?></td>
                                            <td>
                                                <a href="view.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-info">View</a>
                                                <a href="delete.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this content permanently?')">Delete</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

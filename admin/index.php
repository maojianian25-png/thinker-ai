<?php
/**
 * Thinker AI - Admin Dashboard
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$contentList = loadContent();
$totalContent = count($contentList);
$stats = [];
$types = getContentTypes();

// Count by type
foreach ($types as $key => $label) {
    $stats[$key] = count(array_filter($contentList, fn($c) => ($c['type'] ?? '') === $key));
}

// Recent 5 items
$recent = array_slice(array_reverse($contentList), 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Thinker AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content flex-grow-1">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">Dashboard</h2>
                    <a href="generate.php" class="btn btn-primary">+ Generate Content</a>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary text-white">
                            <h3><?= $totalContent ?></h3>
                            <p class="mb-0">Total Content</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success text-white">
                            <h3><?= $stats['article'] ?? 0 ?></h3>
                            <p class="mb-0">Articles</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info text-white">
                            <h3><?= array_sum($stats) - ($stats['article'] ?? 0) ?></h3>
                            <p class="mb-0">Other Content</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning text-dark">
                            <h3><?= count(array_filter($contentList, fn($c) => ($c['status'] ?? '') === 'draft')) ?></h3>
                            <p class="mb-0">Drafts</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Content</h5>
                        <a href="history.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($recent)): ?>
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No content yet. <a href="generate.php">Create your first piece!</a></p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Language</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent as $item): ?>
                                        <tr>
                                            <td><a href="view.php?id=<?= $item['id'] ?>"><?= safe_output(truncate($item['title'], 50)) ?></a></td>
                                            <td><span class="badge bg-secondary"><?= $types[$item['type']] ?? $item['type'] ?></span></td>
                                            <td><?= safe_output($item['language'] ?? 'en') ?></td>
                                            <td><?= formatDate($item['created_at']) ?></td>
                                            <td>
                                                <a href="view.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-info">View</a>
                                                <a href="delete.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this content?')">Delete</a>
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

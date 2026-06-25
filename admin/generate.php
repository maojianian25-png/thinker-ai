<?php
/**
 * Thinker AI - Generate Content
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/openai.php';
requireLogin();

$types = getContentTypes();
$languages = getLanguages();
$result = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic = trim($_POST['topic'] ?? '');
    $keywords = trim($_POST['keywords'] ?? '');
    $type = $_POST['type'] ?? 'article';
    $language = $_POST['language'] ?? 'en';
    $tone = $_POST['tone'] ?? 'professional';
    
    if (empty($topic)) {
        $error = 'Please enter a topic.';
    } else {
        if (!empty($keywords)) {
            $kwList = array_map('trim', explode(',', $keywords));
            $result = generateWithKeywords($kwList, $type, $language, $tone);
        } else {
            $result = generateContent($topic, $type, $language, $tone);
        }
        
        if (isset($result['error'])) {
            $error = $result['error'];
            $result = null;
        } elseif (isset($result['title'])) {
            // Save to storage
            addContent(
                $result['title'],
                $result['content'],
                $type,
                $language
            );
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Content - Thinker AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content flex-grow-1">
            <div class="p-4">
                <h2 class="fw-bold mb-4">✍️ Generate Content</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= safe_output($error) ?></div>
                <?php endif; ?>
                
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="card p-4">
                            <h5 class="mb-3">Content Settings</h5>
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Topic *</label>
                                    <textarea name="topic" class="form-control" rows="2" required placeholder="What do you want to write about?"><?= safe_output($_POST['topic'] ?? '') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keywords (optional)</label>
                                    <input type="text" name="keywords" class="form-control" placeholder="keyword1, keyword2, keyword3" value="<?= safe_output($_POST['keywords'] ?? '') ?>">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="form-label">Content Type</label>
                                        <select name="type" class="form-select">
                                            <?php foreach ($types as $key => $label): ?>
                                                <option value="<?= $key ?>" <?= ($_POST['type'] ?? '') === $key ? 'selected' : '' ?>><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Language</label>
                                        <select name="language" class="form-select">
                                            <?php foreach ($languages as $key => $label): ?>
                                                <option value="<?= $key ?>" <?= ($_POST['language'] ?? '') === $key ? 'selected' : '' ?>><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tone</label>
                                    <select name="tone" class="form-select">
                                        <option value="professional" <?= ($_POST['tone'] ?? '') === 'professional' ? 'selected' : '' ?>>Professional</option>
                                        <option value="conversational" <?= ($_POST['tone'] ?? '') === 'conversational' ? 'selected' : '' ?>>Conversational</option>
                                        <option value="persuasive" <?= ($_POST['tone'] ?? '') === 'persuasive' ? 'selected' : '' ?>>Persuasive</option>
                                        <option value="informative" <?= ($_POST['tone'] ?? '') === 'informative' ? 'selected' : '' ?>>Informative</option>
                                        <option value="creative" <?= ($_POST['tone'] ?? '') === 'creative' ? 'selected' : '' ?>>Creative</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">🚀 Generate with AI</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Generated Content</h5>
                                <div>
                                    <?php if ($result): ?>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="copyContent()">📋 Copy</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (!$result): ?>
                                    <div class="text-center py-5 text-muted">
                                        <p style="font-size: 48px;">🤖</p>
                                        <p>Enter a topic and click "Generate with AI" to create content.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-2">
                                        <span class="badge bg-success">✅ Generated</span>
                                        <span class="badge bg-info"><?= $result['word_count'] ?> words</span>
                                        <span class="badge bg-secondary"><?= $result['model'] ?></span>
                                    </div>
                                    <h4 id="contentTitle"><?= safe_output($result['title']) ?></h4>
                                    <div id="contentBody" class="mt-3 generated-content">
                                        <?= $result['content'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function copyContent() {
        const html = document.getElementById('contentBody')?.innerHTML || '';
        const text = document.getElementById('contentBody')?.innerText || '';
        navigator.clipboard.writeText(text).then(() => {
            alert('Content copied to clipboard!');
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

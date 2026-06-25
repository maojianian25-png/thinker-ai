<?php
/**
 * Thinker AI - Helper Functions
 */

function getStorageDir() {
    $dir = __DIR__ . '/../storage';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return $dir;
}

function getContentFile() {
    return getStorageDir() . '/content.json';
}

function loadContent() {
    $file = getContentFile();
    if (!file_exists($file)) {
        return [];
    }
    $data = json_decode(file_get_contents($file), true);
    return $data ?: [];
}

function saveContent($content) {
    $file = getContentFile();
    file_put_contents($file, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function addContent($title, $body, $type, $language = 'en', $status = 'published') {
    $content = loadContent();
    $content[] = [
        'id' => uniqid('ai_'),
        'title' => $title,
        'body' => $body,
        'type' => $type,
        'language' => $language,
        'status' => $status,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    saveContent($content);
    return end($content);
}

function deleteContent($id) {
    $content = loadContent();
    $content = array_filter($content, function($item) use ($id) {
        return $item['id'] !== $id;
    });
    saveContent(array_values($content));
}

function formatDate($date) {
    return date('Y-m-d H:i', strtotime($date));
}

function truncate($text, $length = 200) {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

function getContentTypes() {
    return [
        'article' => 'SEO Article',
        'blog' => 'Blog Post',
        'product' => 'Product Description',
        'meta' => 'Meta Description',
        'social' => 'Social Media Post',
        'email' => 'Email Newsletter',
        'landing' => 'Landing Page Copy',
    ];
}

function getLanguages() {
    return [
        'en' => 'English',
        'zh' => '中文 (Chinese)',
        'ja' => '日本語 (Japanese)',
        'ko' => '한국어 (Korean)',
        'es' => 'Español',
        'fr' => 'Français',
        'de' => 'Deutsch',
        'pt' => 'Português',
        'ar' => 'العربية',
        'th' => 'ไทย',
    ];
}

function safe_output($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

<?php
/**
 * Thinker AI - OpenAI API Integration
 */

function generateContent($prompt, $type = 'article', $language = 'en', $tone = 'professional') {
    $apiKey = OPENAI_API_KEY;
    if (empty($apiKey)) {
        return ['error' => 'OpenAI API key not configured. Please go to Settings.'];
    }

    $model = OPENAI_MODEL;
    
    $typeLabels = [
        'article' => 'SEO article',
        'blog' => 'blog post',
        'product' => 'product description',
        'meta' => 'meta description',
        'social' => 'social media post',
        'email' => 'email newsletter',
        'landing' => 'landing page copy',
    ];
    
    $typeLabel = $typeLabels[$type] ?? 'article';
    
    $languageNames = [
        'en' => 'English', 'zh' => 'Chinese', 'ja' => 'Japanese',
        'ko' => 'Korean', 'es' => 'Spanish', 'fr' => 'French',
        'de' => 'German', 'pt' => 'Portuguese', 'ar' => 'Arabic',
        'th' => 'Thai',
    ];
    $langName = $languageNames[$language] ?? 'English';
    
    $systemPrompt = "You are a professional content writer and SEO expert. Write high-quality, original content in {$langName}. 
    Tone: {$tone}. Format the content with proper HTML tags (h2, h3, p, ul, ol, strong, em). 
    Include an SEO-optimized title and meta description at the beginning.
    Make the content comprehensive, well-structured, and engaging.";
    
    $userPrompt = "Write a {$typeLabel} about: {$prompt}\n\nContent should be comprehensive and SEO-optimized.";

    $data = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ],
        'temperature' => 0.7,
        'max_tokens' => 4096,
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 120,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => 'API request failed: ' . $error];
    }

    $result = json_decode($response, true);

    if ($httpCode !== 200) {
        $errMsg = $result['error']['message'] ?? 'Unknown API error';
        return ['error' => 'OpenAI error (HTTP ' . $httpCode . '): ' . $errMsg];
    }

    $generatedText = $result['choices'][0]['message']['content'] ?? '';
    
    // Extract title from content
    $title = '';
    if (preg_match('/<h1>(.*?)<\/h1>/s', $generatedText, $m)) {
        $title = strip_tags($m[1]);
    } elseif (preg_match('/^#\s+(.+)/m', $generatedText, $m)) {
        $title = trim($m[1]);
    } else {
        $lines = explode("\n", trim($generatedText));
        $title = trim(strip_tags($lines[0]));
        $title = mb_substr($title, 0, 100);
    }
    
    // Estimate word count
    $wordCount = str_word_count(strip_tags($generatedText));
    if (in_array($language, ['zh', 'ja', 'ko', 'th'])) {
        $wordCount = mb_strlen(strip_tags($generatedText));
    }

    return [
        'title' => $title,
        'content' => $generatedText,
        'word_count' => $wordCount,
        'model' => $model,
        'tokens' => $result['usage']['total_tokens'] ?? 0,
    ];
}

function generateWithKeywords($keywords, $type = 'article', $language = 'en', $tone = 'professional', $wordCount = 1000) {
    $prompt = "Keywords: " . implode(', ', $keywords) . "\n";
    $prompt .= "Target word count: approximately {$wordCount} words.\n";
    $prompt .= "Create well-researched, original content that naturally incorporates these keywords for SEO.";
    return generateContent($prompt, $type, $language, $tone);
}

function checkApiKey($key) {
    if (empty($key)) return false;
    $ch = curl_init('https://api.openai.com/v1/models');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $key],
        CURLOPT_TIMEOUT => 15,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode === 200;
}

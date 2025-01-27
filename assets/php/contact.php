<?php
// Güvenlik başlıkları
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'");

// CSRF koruması
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        exit('CSRF token doğrulaması başarısız.');
    }

    // Input temizleme ve doğrulama
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    if (!$name || !$email || !$message) {
        http_response_code(400);
        exit('Lütfen tüm alanları doldurun.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        exit('Geçersiz email adresi.');
    }

    // Rate limiting
    $ip = $_SERVER['REMOTE_ADDR'];
    $timestamp = time();
    $rateLimit = 5; // 5 dakikada bir
    $rateLimitFile = sys_get_temp_dir() . '/rate_limit_' . md5($ip);

    if (file_exists($rateLimitFile)) {
        $lastSubmission = file_get_contents($rateLimitFile);
        if ($timestamp - $lastSubmission < 300) { // 5 dakika = 300 saniye
            http_response_code(429);
            exit('Çok fazla deneme yaptınız. Lütfen 5 dakika bekleyin.');
        }
    }
    file_put_contents($rateLimitFile, $timestamp);

    // Email gönderme
    $to = 'your-email@domain.com';
    $subject = 'Yeni İletişim Formu Mesajı';
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $emailBody = "İsim: " . $name . "\n" .
                "Email: " . $email . "\n" .
                "Mesaj: " . $message;

    if (mail($to, $subject, $emailBody, $headers)) {
        http_response_code(200);
        echo 'Mesajınız başarıyla gönderildi.';
    } else {
        http_response_code(500);
        echo 'Mesaj gönderilirken bir hata oluştu.';
    }
}
?> 
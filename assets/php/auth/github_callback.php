<?php
require_once "../config.php";
session_start();

// GitHub OAuth yapılandırması
$client_id = 'YOUR_GITHUB_CLIENT_ID';
$client_secret = 'YOUR_GITHUB_CLIENT_SECRET';
$redirect_uri = 'http://localhost/assets/php/auth/github_callback.php';

if (isset($_GET['code'])) {
    // Authorization code'u token'a çevir
    $token_url = 'https://github.com/login/oauth/access_token';
    $data = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirect_uri
    );

    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        // Kullanıcı bilgilerini al
        $user_info_url = 'https://api.github.com/user';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $user_info_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: token ' . $token_data['access_token'],
            'User-Agent: PHP'
        ));
        
        $user_info = curl_exec($ch);
        curl_close($ch);
        
        $user_data = json_decode($user_info, true);

        // E-posta bilgisini al
        $emails_url = 'https://api.github.com/user/emails';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $emails_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: token ' . $token_data['access_token'],
            'User-Agent: PHP'
        ));
        
        $emails_info = curl_exec($ch);
        curl_close($ch);
        
        $emails_data = json_decode($emails_info, true);
        $primary_email = '';
        
        foreach ($emails_data as $email) {
            if ($email['primary']) {
                $primary_email = $email['email'];
                break;
            }
        }

        if ($primary_email) {
            // Kullanıcı veritabanında var mı kontrol et
            $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
            $stmt->execute([$primary_email]);
            $user = $stmt->fetch();

            if ($user) {
                // Kullanıcı varsa giriş yap
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
            } else {
                // Kullanıcı yoksa kayıt et
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([
                    $user_data['name'] ?? $user_data['login'],
                    $primary_email,
                    password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT)
                ]);

                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $pdo->lastInsertId();
                $_SESSION['name'] = $user_data['name'] ?? $user_data['login'];
            }

            header('Location: /pages/index.html');
            exit;
        }
    }
}

// Hata durumunda login sayfasına yönlendir
header('Location: /pages/login.html');
exit; 
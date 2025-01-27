<?php
require_once "../config.php";
session_start();

// Google OAuth yapılandırması
$client_id = 'YOUR_GOOGLE_CLIENT_ID';
$client_secret = 'YOUR_GOOGLE_CLIENT_SECRET';
$redirect_uri = 'http://localhost/assets/php/auth/google_callback.php';

if (isset($_GET['code'])) {
    // Authorization code'u token'a çevir
    $token_url = 'https://oauth2.googleapis.com/token';
    $data = array(
        'code' => $_GET['code'],
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    );

    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        // Kullanıcı bilgilerini al
        $user_info_url = 'https://www.googleapis.com/oauth2/v2/userinfo';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $user_info_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token_data['access_token']
        ));
        
        $user_info = curl_exec($ch);
        curl_close($ch);
        
        $user_data = json_decode($user_info, true);

        if (isset($user_data['email'])) {
            // Kullanıcı veritabanında var mı kontrol et
            $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
            $stmt->execute([$user_data['email']]);
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
                    $user_data['name'],
                    $user_data['email'],
                    password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT)
                ]);

                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $pdo->lastInsertId();
                $_SESSION['name'] = $user_data['name'];
            }

            header('Location: /pages/index.html');
            exit;
        }
    }
}

// Hata durumunda login sayfasına yönlendir
header('Location: /pages/login.html');
exit; 
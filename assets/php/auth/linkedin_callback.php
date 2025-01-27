<?php
require_once "../config.php";
session_start();

// LinkedIn OAuth yapılandırması
$client_id = 'YOUR_LINKEDIN_CLIENT_ID';
$client_secret = 'YOUR_LINKEDIN_CLIENT_SECRET';
$redirect_uri = 'http://localhost/assets/php/auth/linkedin_callback.php';

if (isset($_GET['code'])) {
    // Authorization code'u token'a çevir
    $token_url = 'https://www.linkedin.com/oauth/v2/accessToken';
    $data = array(
        'grant_type' => 'authorization_code',
        'code' => $_GET['code'],
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri
    );

    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        // Kullanıcı bilgilerini al
        $user_info_url = 'https://api.linkedin.com/v2/me?projection=(id,localizedFirstName,localizedLastName,profilePicture)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $user_info_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token_data['access_token']
        ));
        
        $user_info = curl_exec($ch);
        curl_close($ch);
        
        $user_data = json_decode($user_info, true);

        // E-posta bilgisini al
        $email_url = 'https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $email_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token_data['access_token']
        ));
        
        $email_info = curl_exec($ch);
        curl_close($ch);
        
        $email_data = json_decode($email_info, true);
        $email = $email_data['elements'][0]['handle~']['emailAddress'] ?? null;

        if ($email && isset($user_data['localizedFirstName'])) {
            $full_name = $user_data['localizedFirstName'] . ' ' . ($user_data['localizedLastName'] ?? '');

            // Kullanıcı veritabanında var mı kontrol et
            $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
            $stmt->execute([$email]);
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
                    $full_name,
                    $email,
                    password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT)
                ]);

                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $pdo->lastInsertId();
                $_SESSION['name'] = $full_name;
            }

            header('Location: /pages/index.html');
            exit;
        }
    }
}

// Hata durumunda login sayfasına yönlendir
header('Location: /pages/login.html');
exit; 
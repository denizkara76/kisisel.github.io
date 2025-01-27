<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    
    // Validasyon kontrolleri
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Lütfen adınızı girin.";
    }
    
    if (empty($email)) {
        $errors[] = "Lütfen e-posta adresinizi girin.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Geçersiz e-posta formatı.";
    }
    
    if (empty($password)) {
        $errors[] = "Lütfen şifrenizi girin.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Şifreniz en az 6 karakter olmalıdır.";
    }
    
    if ($password != $confirm_password) {
        $errors[] = "Şifreler eşleşmiyor.";
    }
    
    // E-posta adresi daha önce kullanılmış mı kontrol et
    $sql = "SELECT id FROM users WHERE email = :email";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $errors[] = "Bu e-posta adresi zaten kullanılıyor.";
        }
    }
    
    // Hata yoksa kayıt işlemini gerçekleştir
    if (empty($errors)) {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        
        if ($stmt = $pdo->prepare($sql)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Kayıt başarılı! Şimdi giriş yapabilirsiniz.",
                    "redirect" => "/pages/login.html"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Bir hata oluştu. Lütfen daha sonra tekrar deneyin."
                ]);
            }
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => implode("<br>", $errors)
        ]);
    }
    
    unset($stmt);
    unset($pdo);
}
?> 
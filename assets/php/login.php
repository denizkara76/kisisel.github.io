eri<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    
    if (!empty($email) && !empty($password)) {
        $sql = "SELECT id, name, email, password FROM users WHERE email = :email";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $name = $row["name"];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;
                            
                            echo json_encode([
                                "status" => "success",
                                "message" => "Giriş başarılı!",
                                "redirect" => "/pages/index.html"
                            ]);
                        } else {
                            echo json_encode([
                                "status" => "error",
                                "message" => "Geçersiz şifre!"
                            ]);
                        }
                    }
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Bu e-posta adresiyle kayıtlı hesap bulunamadı."
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Bir hata oluştu. Lütfen daha sonra tekrar deneyin."
                ]);
            }
            
            unset($stmt);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Lütfen e-posta ve şifrenizi girin."
        ]);
    }
    
    unset($pdo);
}
?> 
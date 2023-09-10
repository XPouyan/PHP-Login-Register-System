<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // اتصال به پایگاه داده MySQL
    $host = "localhost"; // آدرس سرور پایگاه داده
    $dbUsername = "your_db_username"; // نام کاربری پایگاه داده
    $dbPassword = "your_db_password"; // رمز عبور پایگاه داده
    $dbName = "your_db_name"; // نام پایگاه داده

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    if (mysqli_connect_error()) {
        die("اتصال به پایگاه داده ناموفق بود: " . mysqli_connect_error());
    }

    // جستجوی کاربر بر اساس نام کاربری
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row["password"];
        
        // بررسی مطابقت رمز عبور ورودی با رمز عبور ذخیره شده
        if (password_verify($password, $storedPassword)) {
            $_SESSION["username"] = $username;
            header("Location: welcome.php");
            exit;
        } else {
            echo "ورود ناموفق. نام کاربری یا رمز عبور اشتباه است.";
        }
    } else {
        echo "ورود ناموفق. کاربری با این نام کاربری یافت نشد.";
    }

    $stmt->close();
    $conn->close();
}
?>

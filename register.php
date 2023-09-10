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

    // اینجا می‌توانید کد اعتبارسنجی دلخواه خود را اضافه کنید
    // به عنوان مثال، می‌توانید بررسی کنید که آیا نام کاربری تکراری است یا نه.

    // هش کردن رمز عبور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ذخیره اطلاعات کاربر در جدول users
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION["username"] = $username;
        header("Location: welcome.php");
        exit;
    } else {
        echo "خطا در ثبت نام: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
session_start();
include 'config.php'; // Koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Ambil data user dari database
        $sql = "SELECT id, username, password FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();

        // Cek apakah username ada dan password cocok
        if ($stmt->num_rows > 0 && password_verify($password, $db_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;

            echo "<script>alert('Login berhasil!'); window.location.href='form.php';</script>";
        } else {
            echo "<script>alert('Username atau password salah!'); window.location.href='index.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Harap isi semua kolom!'); window.location.href='form.php';</script>";
    }
}
$conn->close();
?>

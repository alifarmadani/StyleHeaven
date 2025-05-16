<?php
include 'config.php'; // Koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            // Cek apakah username sudah ada
            $sql = "SELECT id FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                // Hash password dan simpan ke database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $username, $hashed_password);
                if ($stmt->execute()) {
                    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='index.php';</script>";
                } else {
                    echo "<script>alert('Terjadi kesalahan!'); window.location.href='index.php';</script>";
                }
            } else {
                echo "<script>alert('Username sudah digunakan!'); window.location.href='index.php';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Password tidak cocok!'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Semua kolom harus diisi!'); window.location.href='index.php';</script>";
    }
    $conn->close();
}
?>

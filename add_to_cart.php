<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Silakan login terlebih dahulu!"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $size = htmlspecialchars($_POST['size']);
    $quantity = intval($_POST['quantity']);

    $result = mysqli_query($conn, "SELECT harga FROM produk WHERE id = '$product_id'");
    if ($row = mysqli_fetch_assoc($result)) {
        $harga = $row['harga'];

        $cek_query = "SELECT id, quantity FROM cart 
                      WHERE user_id = '$user_id' AND product_id = '$product_id' AND size = '$size'";
        $cek_result = mysqli_query($conn, $cek_query);

        if ($existing = mysqli_fetch_assoc($cek_result)) {
            $new_quantity = $existing['quantity'] + $quantity;
            $update_query = "UPDATE cart SET quantity = '$new_quantity', added_at = NOW() WHERE id = '{$existing['id']}'";

            if (mysqli_query($conn, $update_query)) {
                echo json_encode(["status" => "success", "message" => "Jumlah produk berhasil diperbarui di keranjang!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal memperbarui keranjang: " . mysqli_error($conn)]);
            }
        } else {
            $insert_query = "INSERT INTO cart (user_id, product_id, harga, quantity, size, added_at) 
                             VALUES ('$user_id', '$product_id', '$harga', '$quantity', '$size', NOW())";

            if (mysqli_query($conn, $insert_query)) {
                echo json_encode(["status" => "success", "message" => "Produk berhasil ditambahkan ke keranjang!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menambahkan ke keranjang: " . mysqli_error($conn)]);
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Produk tidak ditemukan!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Akses tidak diizinkan!"]);
}

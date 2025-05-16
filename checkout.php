<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Harap login terlebih dahulu!'); window.location.href='index.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$nama_penerima = $_POST['nama_penerima'];
$alamat = $_POST['alamat'];
$total_harga = $_POST['total_harga'];

$bukti_nama = time() . '_' . basename($_FILES['bukti']['name']);
$bukti_tmp = $_FILES['bukti']['tmp_name'];

if (move_uploaded_file($bukti_tmp, 'uploads/' . $bukti_nama)) {
} else {
    echo "Upload gagal.";
}

$insert_order = $conn->prepare("
    INSERT INTO orders (user_id, total_harga, status, nama_penerima, bukti_transfer, alamat_pengiriman)
    VALUES (?, ?, 'Pending', ?, ?, ?)
");
$insert_order->bind_param("idsss", $user_id, $total_harga, $nama_penerima, $bukti_nama, $alamat);
$insert_order->execute();
$order_id = $conn->insert_id;

$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $produk_id = $row['product_id'];
    $ukuran = $row['size'];
    $qty = $row['quantity'];

    $get_price = $conn->prepare("SELECT harga FROM produk WHERE id = ?");
    $get_price->bind_param("i", $produk_id);
    $get_price->execute();
    $price_result = $get_price->get_result()->fetch_assoc();
    $harga = $price_result['harga'];


   $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, harga_satuan) VALUES (?, ?, ?, ?, ?)");
    $insert_item->bind_param("iisid", $order_id, $produk_id, $ukuran, $qty, $harga);
    $insert_item->execute();

    $update_stok = $conn->prepare("UPDATE produk_ukuran SET stok = stok - ? WHERE produk_id = ? AND ukuran = ?");
    $update_stok->bind_param("iis", $qty, $produk_id, $ukuran);
    $update_stok->execute();
}

$delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$delete_cart->bind_param("i", $user_id);
$delete_cart->execute();

header("Location: cart.php");
exit();
?>

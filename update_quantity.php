<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = $_POST['id'];
$action  = $_POST['action'];

// Ambil data cart
$sql = "SELECT c.quantity, c.product_id, c.size 
        FROM cart c 
        WHERE c.id = ? AND c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cart_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(['error' => 'Cart item not found']);
    exit();
}

$quantity    = (int) $row['quantity'];
$produk_id   = $row['product_id'];
$ukuran      = $row['size'];

// Cek stok produk di size tersebut
$stok_sql = "SELECT stok FROM produk_ukuran WHERE produk_id = ? AND ukuran = ?";
$stok_stmt = $conn->prepare($stok_sql);
$stok_stmt->bind_param("is", $produk_id, $ukuran);
$stok_stmt->execute();
$stok_result = $stok_stmt->get_result();
$stok_row = $stok_result->fetch_assoc();

if (!$stok_row) {
    echo json_encode(['error' => 'Stok data not found']);
    exit();
}

$stok_tersedia = (int) $stok_row['stok'];

if ($action === 'increment') {
    if ($quantity >= $stok_tersedia) {
        echo json_encode(['error' => 'Stok tidak mencukupi']);
        exit();
    }
    $quantity += 1;
    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("iii", $quantity, $cart_id, $user_id);
    $update->execute();

    echo json_encode(['success' => true, 'new_quantity' => $quantity]);
    exit();

} elseif ($action === 'decrement') {
    $quantity -= 1;

    if ($quantity <= 0) {
        $delete = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $delete->bind_param("ii", $cart_id, $user_id);
        $delete->execute();

        echo json_encode(['success' => true, 'deleted' => true]);
        exit();
    } else {
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $update->bind_param("iii", $quantity, $cart_id, $user_id);
        $update->execute();

        echo json_encode(['success' => true, 'new_quantity' => $quantity]);
        exit();
    }
}

echo json_encode(['error' => 'Invalid action']);

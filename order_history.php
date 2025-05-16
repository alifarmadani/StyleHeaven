<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Harap login terlebih dahulu!'); window.location.href='index.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_orders = "SELECT id, total_harga, status, tanggal_pesanan, bukti_transfer, nama_penerima, alamat_pengiriman 
               FROM orders WHERE user_id = ? ORDER BY tanggal_pesanan DESC";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StyleHeaven - Order History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="style/header.css">
    <script src="js/functions.js"></script>
    <link rel="stylesheet" href="style/history.css">
</head>

<body>
    <?php include 'header.php' ?>
    <div class="container">
        <div class="main">
            <h2><span style="margin-right: 5px;">ORDER HISTORY</h2>
        </div>
        <?php while ($order = $result_orders->fetch_assoc()): ?>
            <div class="order-card">
                <div class="order-header">
                    <span>Order ID #<?= $order['id'] ?></span>
                    <span><span style="font-weight: normal;"><?= $order['tanggal_pesanan'] ?> | </span><?= $order['status'] ?></span>
                </div>
                <div class="line"></div>

                <?php
                $order_id = $order['id'];
                $sql_items = "SELECT p.nama AS nama_produk, p.gambar, oi.harga_satuan, oi.quantity, oi.size 
                      FROM order_items oi
                      JOIN produk p ON oi.product_id = p.id
                      WHERE oi.order_id = ?";
                $stmt_items = $conn->prepare($sql_items);
                $stmt_items->bind_param("i", $order_id);
                $stmt_items->execute();
                $result_items = $stmt_items->get_result();

                $items = [];
                while ($item = $result_items->fetch_assoc()) {
                    $items[] = $item;
                }

                ?>
                <div class="product-list">
                    <?php foreach ($items as $item): ?>
                        <div class="order-product">
                            <img src="uploads/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                            <div class="product-info">
                                <div class="product-name"><?= htmlspecialchars($item['nama_produk']) ?></div>
                                <div class="product-variant">x<?= $item['quantity'] ?> <p style="margin-top: 0px;">Size: <?= $item['size']; ?></p></div>
                                <div class="price">Rp<?= number_format($item['harga_satuan'], 0, ',', '.') ?></div>
                            </div>
                        </div>
                        <div class="line"></div>
                    <?php endforeach; ?>
                </div>

                <div class="total"><span style="color: black;">Total <?= count($items) ?> product: </span><strong>Rp<?= number_format($order['total_harga'], 0, ',', '.') ?></strong></div>
                <a href="javascript:void(0)" class="detail-btn" onclick="openOverlay(<?= $order['id'] ?>)">View Details</a>


                <div class="overlay" id="overlay-<?= $order['id'] ?>">
                    <div class="overlay-content">
                        <span class="close-btn" onclick="closeOverlay(<?= $order['id'] ?>)">&times;</span>
                        <h3>Shipping Information</h3>
                        <div class="line1"></div>

                    <div class="form-group">
                        <input type="text" name="nama_penerima" id="nama_penerima" value="<?= htmlspecialchars($order['nama_penerima']) ?>" readonly>
                        <label for="nama_penerima">Recipients Name / Nama Penerima</label>
                    </div>

                    <div class="form-group">
                        <textarea name="alamat" id="alamat" rows="3" readonly><?= nl2br(htmlspecialchars($order['alamat_pengiriman'])) ?></textarea>
                        <label for="alamat">Address / Alamat</label>
                    </div>

                    <div class="form-group1">
                        <label for="bukti">Bukti Transfer: </label>
                        <img src="bukti_transfer/<?= htmlspecialchars($order['bukti_transfer']) ?>" alt="Bukti Transfer" class="bukti-img">
                    </div>
                    </div>
                </div>

            </div>
        <?php endwhile; ?>
    </div>

    <footer>
        <p>&copy; 2025 Style Heaven. All rights reserved.</p>
    </footer>
    <script>
        function openOverlay(id) {
            document.getElementById('overlay-' + id).style.display = 'block';
        }

        function closeOverlay(id) {
            document.getElementById('overlay-' + id).style.display = 'none';
        }
    </script>

</body>

</html>
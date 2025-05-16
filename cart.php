<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Harap login terlebih dahulu!'); window.location.href='index.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.id, c.product_id, p.nama, p.harga, c.size, c.quantity, p.gambar, p.stok 
        FROM cart c
        JOIN produk p ON c.product_id = p.id
        WHERE c.user_id = ?";

     
$sql = "SELECT c.id, c.product_id, p.nama, p.harga, c.size, c.quantity, p.gambar, pu.stok 
        FROM cart c
        JOIN produk p ON c.product_id = p.id
        JOIN produk_ukuran pu ON pu.produk_id = c.product_id AND pu.ukuran = c.size
        WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StyleHeaven - Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/cart.css">
    <script src="js/functions.js"></script>
    <script src="js/cart.js"></script>
</head>

<body>
    <?php include 'header.php' ?>

    <div class="cart-container">
        <div class="cart-items">
            <h1>Shopping Cart</h1>

            <?php if ($result->num_rows > 0): ?>
                <?php $subtotal = 0; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $total = $row['harga'] * $row['quantity']; ?>
                    <?php $subtotal += $total; ?>

                    <div class="cart-item">
                        <a href="viewproduk.php?id=<?= $row['product_id'] ?>">
                            <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>">
                        </a>
                        <div class="cart-item-info">
<div class="cart-item-info">
    <h3><?= htmlspecialchars($row['nama']); ?></h3>
    <p>Harga: Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
    <p>Size: <?= htmlspecialchars($row['size']); ?></p>
    <p>Stok tersedia: <?= $row['stok']; ?></p>
    <div class="quantity-controls">
        <button class="quantity-btn decrement" data-id="<?= $row['id']; ?>">-</button>
        <span class="quantity-number" id="quantity-number-<?= $row['id']; ?>"><?= $row['quantity']; ?></span>
        <button class="quantity-btn increment" 
                data-id="<?= $row['id']; ?>" 
                data-stock="<?= $row['stok']; ?>">+</button>
    </div>
</div>

                        </div>
                        <button class="remove-btn" data-id="<?= $row['id']; ?>">
                            <i class="bi bi-trash3-fill"></i>
                        </button>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Shopping cart is empty.</p>
            <?php endif; ?>
        </div>
        <?php if ($result->num_rows > 0): ?>
            <div class="cart-summary">
                <h2>Summary</h2>
                <div class="summary-details">
                    <p>Subtotal: <span>Rp <?= number_format($subtotal, 0, ',', '.'); ?></span></p>
                    <p>Shipping cost: <span>Rp 100.000</span></p>
                    <hr>
                    <p><strong>Total:</strong> <span><strong>Rp <?= number_format($subtotal + 100000, 0, ',', '.'); ?></strong></span></p>
                </div>

                <button class="checkout-btn">Checkout</button>
            </div>
        <?php endif; ?>
    </div>

    <div id="checkout-overlay" class="checkout-overlay hidden">
        <div class="checkout-modal">
            <span class="close-overlay">&times;</span>
            <h2>Payment Method</h2>
            <div class="payment-list">
                <img src="img/bca.png" alt="">
                <div class="text-payment">
                    <H4>Bank BCA</H4>
                    <P>123-7839-92753 / STYLEHEAVEN</P>
                </div>
            </div>
            <div class="summary-details">
                <p>Subtotal: <span>Rp <?= number_format($subtotal, 0, ',', '.'); ?></span></p>
                <p>Shipping cost: <span>Rp 100.000</span></p>
                <hr>
                <p><strong>Total:</strong> <span><strong>Rp <?= number_format($subtotal + 100000, 0, ',', '.'); ?></strong></span></p>
            </div>
            <h4 style="margin-top: -8px;">Shipping Information</h4>
            <div class="line"></div>
            <div class="pengiriman">
                <form action="checkout.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="total_harga" value="<?= $subtotal + 100000; ?>">

                    <div class="form-group">
                        <input type="text" name="nama_penerima" id="nama_penerima" required>
                        <label for="nama_penerima">Recipients Name / Nama Penerima</label>
                    </div>

                    <div class="form-group">
                        <textarea name="alamat" id="alamat" rows="3" required></textarea>
                        <label for="alamat">Address / Alamat</label>
                    </div>

                    <div class="form-group1">
                        <label for="bukti">Bukti Transfer: </label>
                        <input type="file" name="bukti" id="bukti" required>
                    </div>

                    <input type="submit" value="Confirm Payment" class="payment-btn">
                </form>

            </div>

        </div>
    </div>

    <footer>
        <p>&copy; 2025 Style Heaven. All rights reserved.</p>
    </footer>


</body>


</html>
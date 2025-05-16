<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Harap login dahulu!'); window.location.href='form.php';</script>";
    exit();
}
include __DIR__ . '/../config.php';

// Ambil data statistik
$total_penjualan = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'Selesai'")->fetch_row()[0] ?? 0;
$pendapatan = $conn->query("
    SELECT SUM(o.quantity * o.harga_satuan)
FROM order_items o
JOIN orders ord ON o.order_id = ord.id
WHERE ord.status = 'Selesai'
AND MONTH(ord.tanggal_pesanan) = MONTH(CURDATE())
AND YEAR(ord.tanggal_pesanan) = YEAR(CURDATE())

")->fetch_row()[0] ?? 0;

// Ambil produk terlaris
$query_produk_terlaris = $conn->query("
    SELECT p.nama, SUM(o.quantity) as jumlah 
    FROM order_items o 
    JOIN produk p ON o.product_id = p.id 
    GROUP BY o.product_id 
    ORDER BY jumlah DESC 
    LIMIT 1
");
$produk_terlaris = $query_produk_terlaris->fetch_assoc();

// Jika tidak ada produk terlaris
$produk_terlaris_nama = $produk_terlaris['nama'] ?? "Belum ada data";
$produk_terlaris_jumlah = $produk_terlaris['jumlah'] ?? 0;

$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

$where_date = "";
if ($from && $to) {
    $from_escaped = $conn->real_escape_string($from);
    $to_escaped = $conn->real_escape_string($to);
    $where_date = "AND ord.tanggal_pesanan BETWEEN '$from_escaped' AND '$to_escaped'";
}

// Ambil daftar semua produk yang pernah dibeli
$query_order_items = $conn->query("
    SELECT 
        p.nama, 
        SUM(o.quantity) AS total_quantity, 
        o.harga_satuan, 
        SUM(o.quantity * o.harga_satuan) AS total_harga 
    FROM order_items o 
    JOIN produk p ON o.product_id = p.id
    JOIN orders ord ON o.order_id = ord.id
    WHERE ord.status = 'Selesai' $where_date
    GROUP BY o.product_id, o.harga_satuan
    ORDER BY total_quantity DESC
");



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style/dashboard.css">
</head>

<body>
    <div class="container">
        <h2>Dashboard</h2>
        <div class="container-box">
            <div class="box-stat">
                <i class="bi bi-box-seam-fill"></i>
                <div class="line-vertikal"></div>
                <div class="stat">
                    <h4>Total Penjualan Bulan Ini</h4>
                    <p><?= number_format($total_penjualan, 0, ',', '.'); ?> Pesanan</p>
                </div>
            </div>
            <div class="box-stat">
                <i class="bi bi-cash-coin"></i>
                <div class="line-vertikal"></div>
                <div class="stat">
                    <h4>Total Pendapatan</h4>
                    <p>Rp <?= number_format($pendapatan, 0, ',', '.'); ?></p>
                </div>
            </div>
            <div class="box-stat1">
                <i class="bi bi-fire"></i>
                <div class="line-vertikal"></div>
                <div class="stat">
                    <h4>Produk Terlaris</h4>
                    <p><?= htmlspecialchars($produk_terlaris_nama); ?> (<?= number_format($produk_terlaris_jumlah, 0, ',', '.'); ?> terjual)</p>
                </div>
            </div>
        </div>
        <h3>List Items Sold</h3>

        <form method="GET" class="filter-form">
            <label for="from">Sort By Date: From</label>
            <input type="date" name="from" id="from" value="<?= $_GET['from'] ?? '' ?>">
            <label for="to">to</label>
            <input type="date" name="to" id="to" value="<?= $_GET['to'] ?? '' ?>">
            <button type="submit" style="background-color: black; border: 1px solid #000; color: white;">Filter</button>
            <button type="button" style="background-color: white; border: 1px solid #000; color: black;" onclick="window.location.href='dashboard.php'" >Reset</button>
        </form>


        <table class="table-order-items">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $query_order_items->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama']); ?></td>
                        <td><?= number_format($item['total_quantity'], 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format($item['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="cetak.php?from=<?= $_GET['from'] ?? '' ?>&to=<?= $_GET['to'] ?? '' ?>" class="btn-cetak" target="_blank"><i class="bi bi-printer"></i> Print Report</a>

    </div>
</body>

</html>
<?php
include 'config.php';

$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

$where_date = "";
if ($from && $to) {
    $from_escaped = $conn->real_escape_string($from);
    $to_escaped = $conn->real_escape_string($to);
    $where_date = "AND ord.tanggal_pesanan BETWEEN '$from_escaped' AND '$to_escaped'";
}

// Ambil data order items
$query = $conn->query("
    SELECT 
        p.nama AS nama_produk,
        p.harga,
        SUM(o.quantity) AS quantity,
        SUM(o.quantity * o.harga_satuan) AS penjualan
    FROM order_items o
    JOIN produk p ON o.product_id = p.id
    JOIN orders ord ON o.order_id = ord.id
    WHERE ord.status = 'Selesai' $where_date
    GROUP BY p.id
    ORDER BY quantity DESC
");

if (!$query) {
    die("Query error: " . $conn->error);
}


$total_kuantitas = 0;
$total_penjualan = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
    <style>
        @media print {
            body {
                zoom: 80%;
            }
        }
            body {
                font-family: Poppins, static;
            }

            h2,
            h3 {
                text-align: center;
                margin: 0;
            }

            .info {
                text-align: center;
                margin-bottom: 20px;
            }

            table {
                width: 90%;
                margin: 0 auto;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: rgb(0, 0, 0);
                color: white;
                text-align: center;
            }

            .total-row td {
                font-weight: bold;
            }

            .footer {
                margin-top: 40px;
                text-align: right;
                padding-right: 5%;
            }
    </style>
</head>

<body onload="window.print()">
    <h2>STYLE HEAVEN</h2>
    <h3 style="color: darkred;">Penjualan per Barang</h3>
    <div class="info">
        Dari <b><?= htmlspecialchars($from) ?></b> s/d <b><?= htmlspecialchars($to) ?></b>
        <br><small>Outlet: Medan, Sumatera Utara</small>
    </div>


    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga Satuan</th>
                <th>Kuantitas</th>
                <th>Penjualan</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($query && $query->num_rows > 0): ?>
                <?php while ($row = $query->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td>Rp <?= number_format($row['penjualan'], 0, ',', '.') ?></td>
                        <td> <?= number_format($row['quantity'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['penjualan'], 0, ',', '.') ?></td>
                    </tr>
                    <?php
                    $total_kuantitas += $row['quantity'];
                    $total_penjualan += $row['penjualan'];
                    ?>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td><?= number_format($total_kuantitas, 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($total_penjualan, 0, ',', '.') ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data penjualan pada periode ini.</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
</body>

</html>
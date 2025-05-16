<?php
session_start();
include 'config.php';

$id = $_GET['id'];

// Ambil data look
$look_query = mysqli_query($conn, "SELECT * FROM looks WHERE id='$id'");
$look = mysqli_fetch_assoc($look_query);

// Ambil produk-produk terkait
$produk_query = mysqli_query($conn, "
    SELECT p.* 
    FROM look_produk lp
    JOIN produk p ON lp.produk_id = p.id
    WHERE lp.look_id='$id'
");

$produks = [];
while ($row = mysqli_fetch_assoc($produk_query)) {
    $produks[] = $row;
}

// Ambil produk random untuk "Produk Lainnya"
$queryRandom = "SELECT * FROM produk ORDER BY RAND() LIMIT 6";
$resultRandom = mysqli_query($conn, $queryRandom);
$produkRandom = [];
while ($row = mysqli_fetch_assoc($resultRandom)) {
    $produkRandom[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= htmlspecialchars($look['look_name']) ?> - Style Heaven</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style/viewall.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="icon" href="">
    <style>
        .main {
            margin: 90px auto 30px;
            width: 93%;
            border: 1px solid #000;
        }
        .main h2 {
            font-style: italic;
            padding: 0 30px;
            font-weight: normal;
        }
        .look-container {
            width: 90%;
            margin: 50px auto;
            text-align: center;
            display: flex;
            flex-wrap: wrap;
        }
        .container-looks {
            width: 50%;
        }
        .img-besar {
            width: 100%;
            border-radius: 10px;
        }
        .produkterkait {
            width: 50%;
            text-align: left;
        }
        .textproduk {
            margin-bottom: 15px;
            margin-left: 20px;
            font-weight: 400;
            font-style: italic;
            font-size: 22px;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            padding: 0 20px 30px;
        }
        .product-grid a {
            text-decoration: none;
            border-radius: 5px;
            color: inherit;
            border: 1px solid #000;
            transition: 0.4s ease-in-out;
        }
        .product-grid a:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.22);
            scale: 1.01;
        }
        .product-grid img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 5px 5px 0 0;
        }
        .product-grid h3 {
            font-size: 1rem;
            margin: 10px 15px 5px;
        }
        .product-grid p {
            font-size: 0.95rem;
            margin: 0 15px 15px;
            color: red;
            font-weight: 600;
        }
        .produklainnya {
            margin-top: 40px;
        }
        .textprduklainnya {
            margin-bottom: 15px;
            margin-left: 20px;
            font-weight: 400;
            font-style: italic;
            font-size: 20px;
        }
        .produk-lainnya-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 5px;
            padding: 0 20px 50px;
        }
        .produk-lainnya-grid a {
            text-decoration: none;
            border-radius: 5px;
            color: inherit;
            border: 1px solid #000;
            scale: 0.8;
            transition: 0.4s ease-in-out;
        }
        .produk-lainnya-grid a:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.22);
            scale: 0.91;
        }
        .produk-lainnya-grid img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 5px 5px 0 0;
        }
        .produk-lainnya-grid h3 {
            font-size: 0.9rem;
            margin: 10px 15px 5px;
        }
        .produk-lainnya-grid p {
            font-size: 0.85rem;
            margin: 0 15px 15px;
            color: red;
            font-weight: 600;
        }
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
<script src="js/functions.js"></script>
<?php include 'header.php'; ?>

<main>
    <div class="main">
        <h2><?= htmlspecialchars($look['look_name']) ?></h2>
    </div>

    <div class="look-container">
        <div class="container-looks">
            <img src="uploads/look/<?= htmlspecialchars($look['look_image']) ?>" alt="<?= htmlspecialchars($look['look_name']) ?>" class="img-besar">
        </div>

        <div class="produkterkait">
            <h3 class="textproduk">PRODUK TERKAIT</h3>
            <div class="product-grid">
                <?php foreach ($produks as $p) { ?>
                    <a href="viewproduk.php?id=<?= $p['id'] ?>">
                        <img src="uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
                        <h3><?= htmlspecialchars($p['nama']) ?></h3>
                        <p>Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
                    </a>
                <?php } ?>
            </div>

            <div class="produklainnya">
                <h4 class="textprduklainnya">PRODUK LAINNYA</h4>
                <div class="produk-lainnya-grid">
                    <?php foreach ($produkRandom as $pr) { ?>
                        <a href="viewproduk.php?id=<?= $pr['id'] ?>">
                            <img src="uploads/<?= htmlspecialchars($pr['gambar']) ?>" alt="<?= htmlspecialchars($pr['nama']) ?>">
                            <h3><?= htmlspecialchars($pr['nama']) ?></h3>
                            <p>Rp <?= number_format($pr['harga'], 0, ',', '.') ?></p>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="wa">
        <a href="https://wa.me/6281350060514" target="_blank"><i class="bi bi-whatsapp"></i></a>
    </div>
</main>

<footer>
    <p>&copy; 2025 Style Heaven. All rights reserved.</p>
</footer>

</body>
</html>

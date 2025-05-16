<?php
session_start();
include 'config.php';


$order = isset($_GET['order']) ? $_GET['order'] : 'new';
$price = isset($_GET['price']) ? $_GET['price'] : '';

$sql = "SELECT * FROM produk WHERE kategori='Women'";

$orderBy = " ORDER BY waktutambah DESC";

if ($order == 'old') {
    $orderBy = " ORDER BY waktutambah ASC";
}

if ($price == 'low') {
    $orderBy = " ORDER BY harga ASC";
} elseif ($price == 'high') {
    $orderBy = " ORDER BY harga DESC";
}

$sql .= $orderBy;

$result = mysqli_query($conn, $sql);

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Style Heaven - Women's Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Tangerine:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style/viewall.css">
    <link rel="stylesheet" href="style/header.css">
    <style>
        .hero-background {
            background-image: url(img/women.jpg);
        }
    </style>
</head>
<script src="js/functions.js"></script>

<body>
    <?php include 'header.php' ?>
    <main>
        <section id="hero">
            <div class="hero-background">
            </div>
            <h2 style="font-size: 2rem; margin-top: 150px; margin-bottom: 0.1em;">WOMEN'S COLLECTION </h2>
            <p>Choose your best women's outfit.</p>
            <a href="#product" class="btn">Shop Now →</a>
        </section>

        <section id="product">
            <div class="filter-container">
                <form id="filterForm">
                    <label for="order">Sort by:</label>
                    <select name="order" class="btn-filter" id="order" onchange="applyFilter()">
                        <option value="new" <?= ($order == 'new') ? 'selected' : '' ?>>Newest</option>
                        <option value="old" <?= ($order == 'old') ? 'selected' : '' ?>>Oldest</option>
                    </select>

                    <label for="price">Price:</label>
                    <select name="price" id="price" class="btn-filter" onchange="applyFilter()">
                        <option value="" <?= ($price == '') ? 'selected' : '' ?>>Default</option>
                        <option value="low" <?= ($price == 'low') ? 'selected' : '' ?>>Low to High</option>
                        <option value="high" <?= ($price == 'high') ? 'selected' : '' ?>>High to Low</option>
                    </select>
                </form>
            </div>
            <div class="grid">
                <?php foreach ($rows as $r) { ?>
                    <a href="viewproduk.php?id=<?= $r['id'] ?>" class="grid-link">
                        <div class="grid-item">
                            <img alt="Product" src="uploads/<?= $r['gambar'] ?>" />
                            <p><span class="kategori"><i class="bi bi-dash"></i><?= $r['kategori']; ?></span></p>
                            <h2><?php $words = explode(" ", $r['nama']);
                                $short_desc = count($words) > 5 ? implode(" ", array_slice($words, 0, 5)) . "..." : $r['nama'];
                                echo $short_desc; ?></h2>
                            <p class="rp"><?= "Rp " . number_format($r['harga'], 0, ',', '.'); ?></p>
                        </div>
                    </a>
                <?php } ?>
            </div>

        </section>
        <div class="wa">
            <a href="https://wa.me/6281350060514" target="_blank"><i class="bi bi-whatsapp"></i></a>
        </div>
    </main>
    <footer>
        <p>Copyright &copy; 2025 Style Heaven. All rights reserved.</p>
    </footer>
</body>

</html>
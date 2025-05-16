<?php
session_start();
include 'config.php';


$category = isset($_GET['category']) ? $_GET['category'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';

$sql = "SELECT * FROM produk";

if (!empty($category)) {
    $sql .= " WHERE kategori='$category'";
}

if ($price == 'low') {
    $sql .= " ORDER BY harga ASC";
} elseif ($price == 'high') {
    $sql .= " ORDER BY harga DESC";
} else {
    $sql .= " ORDER BY waktutambah DESC";
}

$sql .= " LIMIT 15";

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
    <title>Style Heaven - New Arrivals</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Tangerine:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style/viewall.css">
    <link rel="stylesheet" href="style/header.css">
    <style>
        .hero-background {
            position: absolute;
            width: 100%;
            
            height: 100%;
            animation: changeBackground 8s infinite;
            opacity: 0.2;
            background-size: contain;
        }

        @keyframes changeBackground {
            0% {
                background-image: url('img/kids.jpg');
            }

            20% {
                background-image: url('img/mens.jpg');
            }

            40% {
                background-image: url('img/women.jpg');
            }

            60% {
                background-image: url('img/kids.jpg');
            }

            80% {
                background-image: url('img/mens.jpg');
            }

            100% {
                background-image: url('img/kids.jpg');
            }
        }

        .new-badge {
            z-index: 2;
            rotate: -40deg;
            padding: 20px 30px 5px 30px;
            background-color: red;
            color: white;
            position: absolute;
            margin-left: -35px;
            margin-top: -14px;
        }

        .grid-item {
            position: relative;
            overflow: hidden;
        }
    </style>
</head>
<script src="js/functions.js"></script>
<script>
    function applyFilter() {
        let order = document.getElementById("category").value;
        let price = document.getElementById("price").value;

        let url = new URL(window.location.href);
        url.searchParams.set("category", order);
        url.searchParams.set("price", price);

        window.location.href = url.toString();
    }
</script>

<body>
    <?php include 'header.php' ?>
    <main>
        <section id="hero">
            <div class="hero-background">
            </div>
            <h2 style="font-size: 2rem; margin-top: 150px; margin-bottom: 0.1em;">NEW ARRIVALS </h2>
            <p>Find our latest products.</p>
            <a href="#product" class="btn">Shop Now →</a>
        </section>

        <section id="product">
            <div class="filter-container">
                <form id="filterForm">
                    <label for="order">Category:</label>
                    <select name="order" class="btn-filter" id="category" onchange="applyFilter()">
                        <option value="" <?= ($category == '') ? 'selected' : '' ?>>Default</option>
                        <option value="Men" <?= ($category == 'Men') ? 'selected' : '' ?>>Men</option>
                        <option value="Women" <?= ($category == 'Women') ? 'selected' : '' ?>>Women</option>
                        <option value="Kids" <?= ($category == 'Kids') ? 'selected' : '' ?>>Kids</option>
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
                            <span class="new-badge">New</span>
                            <img alt="Product" src="uploads/<?= $r['gambar'] ?>" />
                            <p><span class="kategori"><i class="bi bi-dash"></i><?= $r['kategori']; ?></span></p>
                            <h2><?php $words = explode(" ", $r['nama']);
                                $short_desc = count($words) > 6 ? implode(" ", array_slice($words, 0, 6)) . "..." : $r['nama'];
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
<?php
include 'config.php';
$look = mysqli_query($conn, "SELECT * FROM look ORDER BY waktutambah DESC");

while($l = mysqli_fetch_assoc($look)) {
    echo "<h2>".$l['judul']."</h2>";
    echo "<img src='uploads/look/".$l['foto']."' width='300'>";

    $produk = mysqli_query($conn, "SELECT produk.* FROM look_produk 
                                   JOIN produk ON look_produk.produk_id = produk.id
                                   WHERE look_produk.look_id=".$l['id']);

    echo "<ul>";
    while($p = mysqli_fetch_assoc($produk)) {
        echo "<li>".$p['nama']." - Rp ".number_format($p['harga'],0,',','.')."</li>";
    }
    echo "</ul><hr>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Style Heaven - <?= $look['nama'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style/viewall.css">
    <link rel="stylesheet" href="style/header.css">
    <style>
        .look-hero {
            text-align: center;
            margin-top: 120px;
            padding: 30px;
        }

        .look-hero img {
            width: 80%;
            max-width: 800px;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .look-hero h2 {
            font-size: 2.2rem;
            margin-bottom: 0.3em;
        }

        .look-hero p {
            font-size: 1rem;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
        }

        .produk-terpakai {
            padding: 50px 20px;
            max-width: 1200px;
            margin: auto;
        }

        .produk-terpakai h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }

        .grid-item {
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.07);
            text-align: center;
            transition: 0.3s;
        }

        .grid-item:hover {
            transform: translateY(-5px);
        }

        .grid-item img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .grid-item h4 {
            font-size: 1rem;
            margin: 10px 0 5px;
        }

        .grid-item p {
            font-weight: 600;
            color: #333;
        }

        .wa {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 30px;
        }

        .wa a {
            color: #25D366;
        }
    </style>
</head>

<body>
    <?php include 'header.php' ?>

    <main>
        <section class="look-hero">
            <img src="uploads/<?= $look['gambar'] ?>" alt="<?= $look['nama'] ?>">
            <h2><?= $look['nama'] ?></h2>
            <p><?= $look['deskripsi'] ?></p>
        </section>

        <section class="produk-terpakai">
            <h3>Products in This Look</h3>
            <div class="grid">
                <?php foreach ($produkDipakai as $p) { ?>
                    <a href="viewproduk.php?id=<?= $p['id'] ?>">
                        <div class="grid-item">
                            <img src="uploads/<?= $p['gambar'] ?>" alt="<?= $p['nama'] ?>">
                            <h4><?= $p['nama'] ?></h4>
                            <p>Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
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

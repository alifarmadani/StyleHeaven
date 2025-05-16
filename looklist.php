<?php
session_start();
include 'config.php';

$sql = "SELECT * FROM looks ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$looks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $looks[] = $row;
}



function getProdukByLookId($conn, $look_id)
{
    $produk = [];

    $sql = "SELECT p.* FROM look_produk lp 
            JOIN produk p ON lp.produk_id = p.id 
            WHERE lp.look_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $look_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $produk[] = $row;
    }

    return $produk;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Style Heaven - Look Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style/viewall.css">
    <link rel="stylesheet" href="style/header.css">
    <style>
        .grid-item {
            justify-content: start;
        }

        .grid ul {
            margin-top: -6px;
            font-size: 14px;
            padding-right: 8px;
        }
    </style>
</head>

<body>
    <script src="js/functions.js"></script>
    <?php include 'header.php' ?>
    <main>
        <section id="hero">
            <div class="hero-background"></div>
            <h2 style="font-size: 2rem; margin-top: 150px; margin-bottom: 0.1em;">SHOP BY LOOKS</h2>
            <p>Shop by outfit inspiration.</p>
        </section>

        <section id="product">
            <div class="grid">
                <?php foreach ($looks as $look) { ?>
                    <a href="lookdetail.php?id=<?= $look['id'] ?>" class="grid-link">
                        <div class="grid-item">
                            <img alt="Look" src="uploads/look/<?= $look['look_image'] ?>" />
                            <h2 style="margin-top: 10px;"><?= $look['look_name'] ?></h2>

                            <?php $produkList = getProdukByLookId($conn, $look['id']); ?>
                            <ul>
                                <?php
                                $maxDisplay = 2;
                                $count = 0;
                                foreach ($produkList as $produk):
                                    if ($count < $maxDisplay): ?>
                                        <li><?= $produk['nama'] ?></li>
                                    <?php endif;
                                    $count++;
                                endforeach;

                                if (count($produkList) > $maxDisplay): ?>
                                    <li><em>And more...</em></li>
                                <?php endif; ?>
                            </ul>
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
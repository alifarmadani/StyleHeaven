<?php
include 'config.php';

$look_id = $_GET['id'];

// Ambil data look-nya
$look = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM looks WHERE id='$look_id'"));

// Ambil produk-produk yang terkait dengan look ini
$produk_query = "
    SELECT p.* FROM produk p
    JOIN look_produk lp ON p.id = lp.produk_id
    WHERE lp.look_id = '$look_id'
";
$produk_result = mysqli_query($conn, $produk_query);
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $look['look_name']; ?> | StyleHeaven</title>
    <style>
        .container {
            width: 90%;
            height: fit-content;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
        }

        .container h2 {
            font-weight: 600;
            font-style: italic;
        }

        .look-image {
            max-width: 350px;
        }

        h3 {
            margin-bottom: 15px;
            font-size: 1rem;
            font-weight: 400;
            font-style: italic;
        }

        .produk-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .produk-item {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
            padding-bottom: 10px;
            border: 1px solid #000;
            overflow: hidden;
            width: 170px;
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .produk-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .produk-item img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .produk-item p {
            margin: 5px 0;
            font-size: 0.9rem;
        }

        .produk-item a {
            text-decoration: none;
            color: inherit;
        }

        .produk-item p.harga {
            color: #e53935;
            font-weight: 600;
        }

        .flexcontain {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .containproduklist {
            margin-top: -10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><?php echo $look['look_name']; ?></h2>
        <div class="flexcontain">
            <img src="../uploads/look/<?php echo $look['look_image']; ?>" alt="<?php echo $look['look_name']; ?>" class="look-image">

            <div class="containproduklist">
            <h3>Produk dalam Look</h3>
            <div class="produk-list">
                <?php while ($produk = mysqli_fetch_assoc($produk_result)) { ?>
                    <div class="produk-item">
                        <a href="../viewproduk.php?id=<?php echo $produk['id']; ?>" target="_blank">
                            <img src="../uploads/<?php echo $produk['gambar']; ?>" alt="<?php echo $produk['nama']; ?>">
                            <p><?php echo $produk['nama']; ?></p>
                            <p class="harga">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
                        </a>
                        </div>
                <?php } ?>
            </div>
            </div>
        </div>
    </div>
</body>

</html>
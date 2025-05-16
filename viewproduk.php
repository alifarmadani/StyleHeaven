<?php
session_start();
include 'config.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");
$v = mysqli_fetch_assoc($result);

$ukuran_query = mysqli_query($conn, "SELECT * FROM produk_ukuran WHERE produk_id='$id'");
$ukuran_stok = [];
$total_stok = 0;
while ($row = mysqli_fetch_assoc($ukuran_query)) {
    $ukuran_stok[$row['ukuran']] = $row['stok'];
    $total_stok += $row['stok'];
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Style Heaven - <?= htmlspecialchars($v['nama']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/view.css">
    <script src="js/functions.js"></script>
    <script src="js/add-to-cart.js"></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="container">
            <div class="image">
                <a href="uploads/<?= htmlspecialchars($v['gambar']); ?>" data-lightbox="produk" data-title="<?= htmlspecialchars($v['nama']) ?>" rel="lightbox">
                    <img src="uploads/<?= htmlspecialchars($v['gambar']); ?>" alt="<?= htmlspecialchars($v['nama']); ?>" width="300">
                </a>
            </div>

            <div class="masuk-keranjang">
                <ul>
                    <li class="nama-produk">
                        <?= htmlspecialchars($v['nama']); ?>
                    </li>       
                    <li>
                        <p class="rp">Rp <?= number_format($v['harga'], 0, ',', '.'); ?></p>
                    </li>
                    <li>
                        <textarea name="deskripsi" id="deskripsi" class="deskripsi-box" readonly><?= $v['deskripsi']; ?></textarea>
                    </li>

                    <li class="size">
    <label>Select Size:</label>
    <div class="size-options">
        <?php foreach ($ukuran_stok as $ukuran => $stok) : ?>
            <button class="size-btn" data-size="<?= $ukuran; ?>" data-stock="<?= $stok; ?>"
                <?= $stok == 0 ? 'disabled style="opacity:0.4;cursor:not-allowed;"' : '' ?>>
                <?= $ukuran; ?>
            </button>
        <?php endforeach; ?>
    </div>
</li>

                    <li>
    <label>Quantity:</label>
    <div class="quantity-selector">
        <table cellspacing="0">
            <td style="background-color:rgb(215, 215, 215);"><button type="button" class="qty-btn" id="decrease">-</button></td>
            <td><input type="number" class="num-input" id="quantity" name="quantity" value="1" min="1"></td>
            <td style="background-color:rgb(215, 215, 215);"><button type="button" class="qty-btn" id="increase">+</button></td>
        </table>
        <span id="stok-info"><?= $total_stok; ?> pieces left</span>
    </div>
</li>


                    <li>
                        <button class="add-to-cart" data-id="<?= $v['id']; ?>">ADD TO CART</button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="wa">
            <a href="https://wa.me/6281350060514" target="_blank"><i class="bi bi-whatsapp"></i></a>
        </div>
    </main>
    <footer>
        <p>Copyright &copy; 2025 Style Heaven. All rights reserved.</p>
    </footer>
    <script>
        
// Size button click logic
document.querySelectorAll('.size-btn').forEach(button => {
    button.addEventListener('click', function () {
        const stock = parseInt(this.getAttribute('data-stock'));
        const size = this.getAttribute('data-size');

        // Update stok info
        document.getElementById('stok-info').textContent = stock + ' pieces left';

        // Update max quantity input
        document.getElementById('quantity').setAttribute('max', stock);

        // Reset quantity jadi 1
        document.getElementById('quantity').value = 1;

        // Highlight button terpilih
        document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('selected'));
        this.classList.add('selected');

        // Cek stok, atur tombol add-to-cart
        const addToCartBtn = document.querySelector('.add-to-cart');
        if (stock === 0) {
            addToCartBtn.disabled = true;
            addToCartBtn.textContent = 'OUT OF STOCK';
        } else {
            addToCartBtn.disabled = false;
            addToCartBtn.textContent = 'ADD TO CART';
        }
    });
});

// Tombol + dan - quantity
document.getElementById('increase').addEventListener('click', function () {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max')) || 999;
    let val = parseInt(input.value);
    if (val < max) {
        input.value = val + 1;
    }
});

document.getElementById('decrease').addEventListener('click', function () {
    const input = document.getElementById('quantity');
    let val = parseInt(input.value);
    if (val > 1) {
        input.value = val - 1;
    }
});

</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js" defer></script>
</body>

</html>
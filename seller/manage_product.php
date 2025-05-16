<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Harap login dahulu!'); window.location.href='form.php';</script>";
    exit();
}
include 'config.php';

$sql = "SELECT * FROM produk ORDER BY id DESC";
$products = [];
$result = mysqli_query($conn, $sql);
while ($product = mysqli_fetch_assoc($result)) {
    $product_id = $product['id'];
    $sizes = mysqli_query($conn, "SELECT ukuran, stok FROM produk_ukuran WHERE produk_id = $product_id");
    $product['ukuran_stok'] = [];
    while ($row = mysqli_fetch_assoc($sizes)) {
        $product['ukuran_stok'][] = $row;
    }
    $products[] = $product;
}

if (isset($_POST['submit-product'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];

    $namaFile = $_FILES['gambar']['name'];
    $tmpName = $_FILES['gambar']['tmp_name'];
    $uniqueName = uniqid() . '_' . $namaFile;
    $targetDir = '../uploads/';
    $targetFile = $targetDir . $uniqueName;

    if (move_uploaded_file($tmpName, $targetFile)) {
        $qr = "INSERT INTO produk (nama, harga, kategori, deskripsi, gambar) VALUES ('$nama', $harga, '$kategori', '$deskripsi', '$uniqueName')";

        if (mysqli_query($conn, $qr)) {
            $lastProductId = mysqli_insert_id($conn);
            if (isset($_POST['ukuran'], $_POST['stok_ukuran'])) {
                $ukuranList = $_POST['ukuran'];
                $stokList = $_POST['stok_ukuran'];
                foreach ($ukuranList as $index => $ukuran) {
                    $ukuran = mysqli_real_escape_string($conn, $ukuran);
                    $stokUkuran = (int) $stokList[$index];
                    $queryUkuran = "INSERT INTO produk_ukuran (produk_id, ukuran, stok) VALUES ($lastProductId, '$ukuran', $stokUkuran)";
                    mysqli_query($conn, $queryUkuran);
                }
            }
            echo "<script>alert('Succes, product successfully added.'); window.location.href='manage_product.php';</script>";
            exit;
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Gagal mengunggah gambar.')</script>";
    }
}

if (isset($_POST['submit-edit'])) {
    $id = $_POST['submit-edit'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = $_POST['harga'];
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $gambarBaru = '';
    if (!empty($_FILES['gambar']['name'])) {
        $namaFile = $_FILES['gambar']['name'];
        $tmpName = $_FILES['gambar']['tmp_name'];
        $uniqueName = uniqid() . '_' . $namaFile;
        $targetDir = '../uploads/';
        $targetFile = $targetDir . $uniqueName;

        if (move_uploaded_file($tmpName, $targetFile)) {
            $gambarBaru = $uniqueName;
        } else {
            echo "<script>alert('Gagal mengunggah gambar baru.')</script>";
        }
    }

    $qr = "UPDATE produk SET nama = '$nama', harga = $harga, kategori = '$kategori', deskripsi = '$deskripsi'";
    if ($gambarBaru) {
        $qr .= ", gambar = '$gambarBaru'";
    }
    $qr .= " WHERE id = $id";

    if (mysqli_query($conn, $qr)) {
        mysqli_query($conn, "DELETE FROM produk_ukuran WHERE produk_id = $id");
        if (isset($_POST['ukuran'], $_POST['stok_ukuran'])) {
            $ukuranList = $_POST['ukuran'];
            $stokList = $_POST['stok_ukuran'];
            foreach ($ukuranList as $index => $ukuran) {
                $ukuran = mysqli_real_escape_string($conn, $ukuran);
                $stokUkuran = (int) $stokList[$index];
                mysqli_query($conn, "INSERT INTO produk_ukuran (produk_id, ukuran, stok) VALUES ($id, '$ukuran', $stokUkuran)");
            }
        }
        echo "<script>alert('Product updated successfully.'); window.location.href = 'manage_product.php';</script>";
        exit;
    } else {
        echo "Database Error saat update: " . mysqli_error($conn);
    }
}

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
    <link rel="stylesheet" href="style/mng_product.css">
    <style>
        .btn-addlook {
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            font-size: 14px;
            font-family: Poppins, static;
            text-decoration: none;
            padding: 8px 15px;
            cursor: pointer;
            border: 1px solid #000;
            transition: 0.3s ease-in-out;
        }

        .btn-addlook:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .btn-addlook i {
            padding-right: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Product</h2>
        <button id="openForm" class="btn-add"><i class="bi bi-bag-plus-fill"></i> Add Product</button>
        <a href="add_look.php" class="btn-addlook"><i class="bi bi-person-lines-fill"></i> Add Look</a>
        <div class="overlay" id="overlayForm">
            <div class="form-container">
                <span class="close-btn" id="closeForm">&times;</span>
                <h3>Add Product</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="text" name="nama" placeholder="Product Name" required>
                    <input type="number" name="harga" placeholder="Price" required>
                    <div class="row">
                        <select name="kategori" required>
                            <option value="" disabled selected>Category</option>
                            <option value="Kids">Kids</option>
                            <option value="Men">Men</option>
                            <option value="Women">Women</option>
                        </select>
                    </div>
                    <div id="size-wrapper">
                        <div class="size-row">
                            <input type="text" name="ukuran[]" placeholder="Size (S, M, L, XL, XXL)" required>
                            <input type="number" name="stok_ukuran[]" placeholder="Stock for size" required>
                            <button type="button" onclick="removeSize(this)">Remove</button>
                        </div>
                    </div>
                    <button type="button" onclick="addSize()">Add Size</button>
                    <textarea name="deskripsi" placeholder="Description" rows="3" required></textarea>
                    <input type="file" name="gambar" required>
                    <button type="submit" name="submit-product">Add Product</button>
                </form>
            </div>
        </div>

        <div class="products-box">
            <?php foreach ($products as $p) { ?>
                <div class="box-product">
                    <img src="../uploads/<?= $p['gambar']; ?>" alt="<?= $p['nama'] ?>" class="image">
                    <div class="text">
                        <ul>
                            <li style="font-weight: 500;">
                                <?php $words = explode(" ", $p['nama']);
                                $nama = count($words) > 3 ? implode(" ", array_slice($words, 0, 3)) . "..." : $p['nama'];
                                echo $nama; ?>
                            </li>
                            <li style="color:rgba(255, 0, 0, 0.81);">
                                Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                                <span style="color: black;"> | </span>
                                <span style="color:rgb(86, 86, 86);">
                                    <?php
                                    $stok_query = $conn->prepare("SELECT SUM(stok) as total_stok FROM produk_ukuran WHERE produk_id = ?");
                                    $stok_query->bind_param("i", $p['id']);
                                    $stok_query->execute();
                                    $stok_result = $stok_query->get_result()->fetch_assoc();
                                    $p['stok'] = $stok_result['total_stok'] ?? 0;
                                    ?> pieces
                                </span>
                            </li>
                            <li style="margin-top: 10px;">
                                <button class="openEditForm" data-id="<?= $p['id'] ?>"><i class="bi bi-pencil-square"></i></button>
                                <a href="deleteproduk.php?id=<?= $p['id'] ?>" onclick="return confirm('Yakin hapus <?= htmlspecialchars($p['nama'], ENT_QUOTES) ?>?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </li>
                        </ul>
                        <p class="kategori"><?= $p['kategori']; ?></p>
                    </div>
                </div>
                <div class="overlay editOverlay" id="editOverlay-<?= $p['id'] ?>">
                    <div class="form-container">
                        <span class="close-btn closeEditForm" data-id="<?= $p['id'] ?>">&times;</span>
                        <h3>Edit Product</h3>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="text" name="nama" value="<?= htmlspecialchars($p['nama']) ?>" required>
                            <input type="number" name="harga" value="<?= $p['harga'] ?>" required>
                            <div class="row">
                                <select name="kategori" required>
                                    <option value="" disabled>Category</option>
                                    <option value="Kids" <?= $p['kategori'] == 'Kids' ? 'selected' : '' ?>>Kids</option>
                                    <option value="Men" <?= $p['kategori'] == 'Men' ? 'selected' : '' ?>>Men</option>
                                    <option value="Women" <?= $p['kategori'] == 'Women' ? 'selected' : '' ?>>Women</option>
                                </select>
                            </div>
                            <textarea name="deskripsi" rows="3" required><?= htmlspecialchars($p['deskripsi']) ?></textarea>

<div id="edit-size-wrapper-<?= $p['id'] ?>">
    <?php foreach ($p['ukuran_stok'] as $ukuranItem) { ?>
        <div class="size-row">
            <input type="text" name="ukuran[]" value="<?= $ukuranItem['ukuran'] ?>" required>
            <input type="number" name="stok_ukuran[]" value="<?= $ukuranItem['stok'] ?>" required>
            <button type="button" onclick="removeSize(this)">Remove</button>
        </div>
    <?php } ?>
</div>
<button type="button" onclick="addSize(<?= $p['id'] ?>)">Add Size</button>

<input type="file" name="gambar">
<button type="submit" name="submit-edit" value="<?= $p['id'] ?>">Save Changes</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
        const openForm = document.getElementById('openForm');
        const closeForm = document.getElementById('closeForm');
        const overlayForm = document.getElementById('overlayForm');

        openForm.addEventListener('click', () => {
            overlayForm.style.display = 'flex';
        });

        closeForm.addEventListener('click', () => {
            overlayForm.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === overlayForm) {
                overlayForm.style.display = 'none';
            }
        });

        document.querySelectorAll('.openEditForm').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const overlay = document.getElementById(`editOverlay-${id}`);
                if (overlay) overlay.style.display = 'flex';
            });
        });

        document.querySelectorAll('.closeEditForm').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const overlay = document.getElementById(`editOverlay-${id}`);
                if (overlay) overlay.style.display = 'none';
            });
        });

        window.addEventListener('click', (e) => {
            document.querySelectorAll('.editOverlay').forEach(overlay => {
                if (e.target === overlay) {
                    overlay.style.display = 'none';
                }
            });
        });

        function addSize() {
            const wrapper = document.getElementById('size-wrapper');
            const newRow = document.createElement('div');
            newRow.className = 'size-row';
            newRow.innerHTML = `
                <input type="text" name="ukuran[]" placeholder="Size (S, M, L, XL, XXL)" required>
                <input type="number" name="stok_ukuran[]" placeholder="Stock for size" required>
                <button type="button" onclick="removeSize(this)">Remove</button>
            `;
            wrapper.appendChild(newRow);
        }

        function removeSize(btn) {
            const row = btn.parentNode;
            row.remove();
        }
    </script>
</body>

</html>
<?php 
include 'config.php';
$id = $_GET['id'];
$query = "DELETE FROM produk WHERE id=$id";
if (mysqli_query($conn, $query)) {
    echo "<script>window.location.href='manage_product.php'; alert('Product successfully deleted.');</script>";
} else {
    mysqli_connect_error($conn);
}
?>
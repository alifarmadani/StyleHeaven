<?php
include 'config.php';

$query = "SELECT * FROM looks";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

        .box-look {
            margin: 10px;
            text-align: center;
            width: 200px;
            border: 1px solid #000;
        }

        .box-look h4 {
            padding: 0 8px;
        }
        a {  
            color: #000;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Look</h2>
        <div style="display: flex; flex-wrap: wrap;">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <a href="look_detail.php?id=<?php echo $row['id']; ?>">
                <div class="box-look">
                        <img src="../uploads/look/<?php echo $row['look_image']; ?>" alt="<?php echo $row['look_name']; ?>" style="width: 200px; height: auto;">
                        <h4><?php echo $row['look_name']; ?></h4>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>

</body>

</html>
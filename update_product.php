<?php
include 'db.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM PRODUCT WHERE product_id = $id");
    header("Location: update_product.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['id'];
    $name  = $_POST['product_name'];
    $price = $_POST['price'];

    $conn->query("UPDATE PRODUCT SET product_name='$name', price='$price' WHERE product_id=$id");
    header("Location: update_product.php");
    exit;
}

$result = $conn->query("SELECT * FROM PRODUCT");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products</title>
<style>
    body { background: linear-gradient(135deg, #43cea2, #185a9d); font-family: 'Poppins', sans-serif; color: white; text-align: center; padding: 30px; }
    table { width: 80%; margin: 20px auto; border-collapse: collapse; border-radius: 10px; overflow: hidden; background: rgba(255,255,255,0.1); }
    th, td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.3); }
    th { background: rgba(0,0,0,0.3); text-transform: uppercase; letter-spacing: 1px; }
    tr:hover { background: rgba(255,255,255,0.1); }
    input { width: 90%; padding: 6px; border-radius: 6px; border: none; outline: none; text-align: center; }
    button, a { background: rgba(255,255,255,0.2); color: white; border: none; border-radius: 8px; padding: 6px 12px; cursor: pointer; text-decoration: none; }
    button:hover, a:hover { background: rgba(255,255,255,0.4); }
    .back-btn { display: inline-block; margin-top: 30px; background: rgba(0,0,0,0.2); padding: 10px 25px; border-radius: 8px; }
</style>
</head>
<body>
<h2>📦 Manage Products</h2>

<table>
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <form method="POST">
                <td><?= $row['product_id'] ?></td>
                <td><input type="text" name="product_name" value="<?= $row['product_name'] ?>"></td>
                <td><input type="number" step="0.01" name="price" value="<?= $row['price'] ?>"></td>
                <td>
                    <input type="hidden" name="id" value="<?= $row['product_id'] ?>">
                    <button type="submit">Update</button>
                    <a href="?delete=<?= $row['product_id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </form>
        </tr>
    <?php } ?>
</table>

<a href="index.html" class="back-btn">⬅ Back</a>
</body>
</html>

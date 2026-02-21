<?php
include 'db.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM ORDERS WHERE order_id = $id");
    header("Location: update_order.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['id'];
    $date  = $_POST['order_date'];
    $cust  = $_POST['customer_id'];

    $conn->query("UPDATE ORDERS SET order_date='$date', customer_id='$cust' WHERE order_id=$id");
    header("Location: update_order.php");
    exit;
}

$customers = $conn->query("SELECT * FROM CUSTOMER");
$result = $conn->query("SELECT * FROM ORDERS");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders</title>
<style>
    body { background: linear-gradient(135deg, #f7971e, #ffd200); font-family: 'Poppins', sans-serif; color: white; text-align: center; padding: 30px; }
    table { width: 80%; margin: 20px auto; border-collapse: collapse; border-radius: 10px; overflow: hidden; background: rgba(255,255,255,0.1); }
    th, td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.3); }
    th { background: rgba(0,0,0,0.3); text-transform: uppercase; letter-spacing: 1px; }
    tr:hover { background: rgba(255,255,255,0.1); }
    input, select { width: 90%; padding: 6px; border-radius: 6px; border: none; outline: none; text-align: center; }
    button, a { background: rgba(255,255,255,0.2); color: white; border: none; border-radius: 8px; padding: 6px 12px; cursor: pointer; text-decoration: none; }
    button:hover, a:hover { background: rgba(255,255,255,0.4); }
    .back-btn { display: inline-block; margin-top: 30px; background: rgba(0,0,0,0.2); padding: 10px 25px; border-radius: 8px; }
</style>
</head>
<body>
<h2>🧾 Manage Orders</h2>

<table>
    <tr><th>ID</th><th>Order Date</th><th>Customer</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <form method="POST">
                <td><?= $row['order_id'] ?></td>
                <td><input type="date" name="order_date" value="<?= $row['order_date'] ?>"></td>
                <td>
                    <select name="customer_id" required>
                        <?php 
                        $cust_res = $conn->query("SELECT * FROM CUSTOMER");
                        while ($cust = $cust_res->fetch_assoc()) {
                            $selected = ($cust['customer_id'] == $row['customer_id']) ? "selected" : "";
                            echo "<option value='{$cust['customer_id']}' $selected>{$cust['name']}</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="id" value="<?= $row['order_id'] ?>">
                    <button type="submit">Update</button>
                    <a href="?delete=<?= $row['order_id'] ?>" onclick="return confirm('Delete this order?')">Delete</a>
                </td>
            </form>
        </tr>
    <?php } ?>
</table>

<a href="index.html" class="back-btn">⬅ Back</a>
</body>
</html>

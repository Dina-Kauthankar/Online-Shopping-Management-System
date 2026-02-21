<?php
include 'db.php';

// ===== DELETE OPERATION =====
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM CUSTOMER WHERE customer_id = $id");
    header("Location: update_customer.php");
    exit;
}

// ===== UPDATE OPERATION =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $city  = $_POST['city'];

    $conn->query("UPDATE CUSTOMER SET name='$name', email='$email', city='$city' WHERE customer_id=$id");
    header("Location: update_customer.php");
    exit;
}

// ===== FETCH ALL CUSTOMERS =====
$result = $conn->query("SELECT * FROM CUSTOMER");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Customers</title>
<style>
    body { background: linear-gradient(135deg, #667eea, #764ba2); font-family: 'Poppins', sans-serif; color: white; text-align: center; margin: 0; padding: 30px; }
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
<h2>👤 Manage Customers</h2>

<table>
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>City</th><th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <form method="POST">
                <td><?= $row['customer_id'] ?></td>
                <td><input type="text" name="name" value="<?= $row['name'] ?>"></td>
                <td><input type="email" name="email" value="<?= $row['email'] ?>"></td>
                <td><input type="text" name="city" value="<?= $row['city'] ?>"></td>
                <td>
                    <input type="hidden" name="id" value="<?= $row['customer_id'] ?>">
                    <button type="submit">Update</button>
                    <a href="?delete=<?= $row['customer_id'] ?>" onclick="return confirm('Delete this customer?')">Delete</a>
                </td>
            </form>
        </tr>
    <?php } ?>
</table>

<a href="index.html" class="back-btn">⬅ Back</a>
</body>
</html>

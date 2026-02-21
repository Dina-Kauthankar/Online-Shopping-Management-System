<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $date = $_POST['payment_date'];
    $order_id = $_POST['order_id'];

    $sql = "INSERT INTO PAYMENT (amount, type, payment_date, order_id) VALUES ('$amount', '$type', '$date', '$order_id')";
    $msg = ($conn->query($sql)) ? "✅ Payment added successfully!" : "❌ Error: " . $conn->error;
}

// Fetch orders for dropdown
$order_result = $conn->query("SELECT order_id FROM ORDERS");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Payment</title>
<style>
    body { background: linear-gradient(135deg, #ff416c, #ff4b2b); font-family: 'Poppins', sans-serif; color: white; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
    .form-container { background: rgba(255,255,255,0.1); padding: 30px 50px; border-radius: 20px; text-align: center; box-shadow: 0 0 20px rgba(0,0,0,0.2); }
    input, select, button, a { width: 100%; padding: 10px; margin: 10px 0; border-radius: 8px; border: none; outline: none; }
    button, a { background: rgba(255,255,255,0.2); color: white; text-decoration: none; cursor: pointer; }
    button:hover, a:hover { background: rgba(255,255,255,0.4); }
</style>
</head>
<body>
    <div class="form-container">
        <h2>💳 Add Payment</h2>
        <form method="POST">
            <input type="number" step="0.01" name="amount" placeholder="Enter Amount" required>
            <input type="text" name="type" placeholder="Payment Type (e.g. UPI, Card)" required>
            <input type="date" name="payment_date" required>
            <select name="order_id" required>
                <option value="">-- Select Order ID --</option>
                <?php while ($row = $order_result->fetch_assoc()) echo "<option value='{$row['order_id']}'>Order #{$row['order_id']}</option>"; ?>
            </select>
            <button type="submit">Add Payment</button>
        </form>
        <?php if (isset($msg)) echo "<p>$msg</p>"; ?>
        <a href="index.html">⬅ Back</a>
    </div>
</body>
</html>

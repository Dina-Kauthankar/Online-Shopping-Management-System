<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🔗 Join Operations - Online Shopping</title>
<style>
    body {
        background: linear-gradient(135deg, #4e54c8, #8f94fb);
        font-family: 'Poppins', sans-serif;
        color: white;
        text-align: center;
        padding: 30px;
    }
    table {
        width: 85%;
        margin: 30px auto;
        border-collapse: collapse;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.3);
    }
    th {
        background: rgba(0,0,0,0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    tr:hover {
        background: rgba(255,255,255,0.1);
    }
    .back-btn {
        display: inline-block;
        margin-top: 30px;
        background: rgba(0,0,0,0.2);
        padding: 10px 25px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
    }
    .back-btn:hover {
        background: rgba(0,0,0,0.4);
    }
</style>
</head>
<body>

<h2>🔗 Join Operations Report</h2>

<?php
// 1️⃣ Join: Customer + Orders + Payment
$query1 = "
    SELECT c.name AS customer_name, o.order_id, o.order_date, 
           p.amount, p.type AS payment_type
    FROM CUSTOMER c
    JOIN ORDERS o ON c.customer_id = o.customer_id
    LEFT JOIN PAYMENT p ON o.order_id = p.order_id
    ORDER BY o.order_date DESC
";
$result1 = $conn->query($query1);

// 2️⃣ Join: Orders + Product + Order_Product
// 2️⃣ Join: Orders + Product + Order_Product (Show all orders even if product not linked yet)
$query2 = "
    SELECT 
        o.order_id, 
        pr.product_name, 
        op.quantity, 
        pr.price, 
        (op.quantity * pr.price) AS total
    FROM ORDERS o
    LEFT JOIN ORDER_PRODUCT op ON o.order_id = op.order_id
    LEFT JOIN PRODUCT pr ON op.product_id = pr.product_id
    ORDER BY o.order_id;
";
$result2 = $conn->query($query2);
?>


<!-- Section 1 -->
<h3>🧾 Orders with Customer & Payment Details</h3>
<table>
    <tr>
        <th>Customer Name</th>
        <th>Order ID</th>
        <th>Order Date</th>
        <th>Payment Amount (₹)</th>
        <th>Payment Type</th>
    </tr>
    <?php while($row = $result1->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['customer_name'] ?></td>
        <td><?= $row['order_id'] ?></td>
        <td><?= $row['order_date'] ?></td>
        <td><?= $row['amount'] ? number_format($row['amount'], 2) : '—' ?></td>
        <td><?= $row['payment_type'] ?: 'Pending' ?></td>
    </tr>
    <?php } ?>
</table>

<!-- Section 2 -->
<h3>📦 Products in Each Order</h3>
<table>
    <tr>
        <th>Order ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price (₹)</th>
        <th>Total (₹)</th>
    </tr>
    <?php while($row = $result2->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['order_id'] ?></td>
        <td><?= $row['product_name'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= number_format($row['price'], 2) ?></td>
        <td><?= number_format($row['total'], 2) ?></td>
    </tr>
    <?php } ?>
</table>

<a href="index.html" class="back-btn">⬅ Back to Home</a>

</body>
</html>

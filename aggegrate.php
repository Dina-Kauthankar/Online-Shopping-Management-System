<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📊 Aggregate Reports - Online Shopping</title>
<style>
    body {
        background: linear-gradient(135deg, #00b09b, #96c93d);
        font-family: 'Poppins', sans-serif;
        color: white;
        text-align: center;
        padding: 30px;
    }
    h2, h3 {
        text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
    }
    table {
        width: 85%;
        margin: 30px auto;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        background: rgba(255,255,255,0.1);
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

<h2>📊 Aggregate Reports</h2>

<?php
// 1️⃣ Total Sales per Product
$q1 = "
    SELECT p.product_name, SUM(p.price * op.quantity) AS total_sales
    FROM PRODUCT p
    JOIN ORDER_PRODUCT op ON p.product_id = op.product_id
    GROUP BY p.product_name
";
$r1 = $conn->query($q1);

// 2️⃣ Total Orders per Customer
$q2 = "
    SELECT c.name, COUNT(o.order_id) AS total_orders
    FROM CUSTOMER c
    LEFT JOIN ORDERS o ON c.customer_id = o.customer_id
    GROUP BY c.name
";
$r2 = $conn->query($q2);

// 3️⃣ Average Payment Amount
$q3 = "
    SELECT AVG(amount) AS avg_payment, MIN(amount) AS min_payment, MAX(amount) AS max_payment
    FROM PAYMENT
";
$r3 = $conn->query($q3);

// 4️⃣ Total Revenue
$q4 = "
    SELECT SUM(amount) AS total_revenue
    FROM PAYMENT
";
$r4 = $conn->query($q4);
$row4 = $r4->fetch_assoc();
?>

<!-- TABLE 1 -->
<h3>💰 Total Sales per Product</h3>
<table>
<tr><th>Product</th><th>Total Sales (₹)</th></tr>
<?php 
if ($r1->num_rows > 0) {
    while($row = $r1->fetch_assoc()) {
        echo "<tr><td>{$row['product_name']}</td><td>".number_format($row['total_sales'],2)."</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>No data found</td></tr>";
}
?>
</table>

<!-- TABLE 2 -->
<h3>🧾 Total Orders per Customer</h3>
<table>
<tr><th>Customer Name</th><th>Total Orders</th></tr>
<?php 
if ($r2->num_rows > 0) {
    while($row = $r2->fetch_assoc()) {
        echo "<tr><td>{$row['name']}</td><td>{$row['total_orders']}</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>No data found</td></tr>";
}
?>
</table>

<!-- TABLE 3 -->
<h3>💳 Payment Summary</h3>
<table>
<tr><th>Average Payment (₹)</th><th>Minimum (₹)</th><th>Maximum (₹)</th></tr>
<?php 
if ($r3->num_rows > 0) {
    while($row = $r3->fetch_assoc()) {
        echo "<tr><td>".number_format($row['avg_payment'],2)."</td><td>".number_format($row['min_payment'],2)."</td><td>".number_format($row['max_payment'],2)."</td></tr>";
    }
}
?>
</table>

<!-- TABLE 4 -->
<h3>📈 Total Revenue from All Payments</h3>
<table>
<tr><th>Total Revenue (₹)</th></tr>
<tr><td><strong><?= number_format($row4['total_revenue'],2) ?></strong></td></tr>
</table>

<a href="index.html" class="back-btn">⬅ Back to Home</a>

</body>
</html>

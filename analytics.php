<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📊 Analytics & Reports</title>
<style>
    body {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        font-family: 'Poppins', sans-serif;
        color: white;
        text-align: center;
        padding: 30px;
    }
    h2 {
        margin-bottom: 20px;
    }
    table {
        width: 70%;
        margin: 25px auto;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.3);
    }
    th {
        background: rgba(0, 0, 0, 0.3);
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

<h2>📊 Analytics & Reports</h2>

<?php
// 1️⃣ Total Customers
$total_customers = $conn->query("SELECT COUNT(*) AS total FROM CUSTOMER")->fetch_assoc()['total'];

// 2️⃣ Total Orders
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM ORDERS")->fetch_assoc()['total'];

// 3️⃣ Total Revenue (sum of payment amounts)
$total_revenue = $conn->query("SELECT IFNULL(SUM(amount), 0) AS total FROM PAYMENT")->fetch_assoc()['total'];

// 4️⃣ Average Order Value
$avg_order = $conn->query("SELECT IFNULL(AVG(amount), 0) AS avg_order FROM PAYMENT")->fetch_assoc()['avg_order'];

// 5️⃣ Top 3 Customers (based on total amount spent)
$top_customers = $conn->query("
    SELECT c.name, SUM(p.amount) AS total_spent
    FROM CUSTOMER c
    JOIN ORDERS o ON c.customer_id = o.customer_id
    JOIN PAYMENT p ON o.order_id = p.order_id
    GROUP BY c.customer_id
    ORDER BY total_spent DESC
    LIMIT 3
");
?>

<!-- Summary Table -->
<h3>📈 Overall Summary</h3>
<table>
    <tr><th>Total Customers</th><td><?= $total_customers ?></td></tr>
    <tr><th>Total Orders</th><td><?= $total_orders ?></td></tr>
    <tr><th>Total Revenue (₹)</th><td><?= number_format($total_revenue, 2) ?></td></tr>
    <tr><th>Average Order Value (₹)</th><td><?= number_format($avg_order, 2) ?></td></tr>
</table>

<!-- Top Customers Table -->
<h3>🏆 Top 3 Customers by Total Spend</h3>
<table>
    <tr><th>Customer Name</th><th>Total Spent (₹)</th></tr>
    <?php while($row = $top_customers->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= number_format($row['total_spent'], 2) ?></td>
        </tr>
    <?php } ?>
</table>

<a href="index.html" class="back-btn">⬅ Back to Home</a>

</body>
</html>

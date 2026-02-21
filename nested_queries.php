<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🧠 Nested Queries - Online Shopping</title>
<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
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

<h2>🧠 Nested Queries Reports</h2>

<?php
// 1️⃣ Nested Query: Customers who made orders above average payment amount
$query1 = "
    SELECT name, city FROM CUSTOMER 
    WHERE customer_id IN (
        SELECT o.customer_id FROM ORDERS o
        JOIN PAYMENT p ON o.order_id = p.order_id
        WHERE p.amount > (SELECT AVG(amount) FROM PAYMENT)
    )
";
$result1 = $conn->query($query1);

// 2️⃣ Nested Query: Products with price greater than the average price
$query2 = "
    SELECT product_name, price 
    FROM PRODUCT 
    WHERE price > (SELECT AVG(price) FROM PRODUCT)
";
$result2 = $conn->query($query2);

// // 3️⃣ Nested Query: Orders that have more than one product
// $query3 = "
//     SELECT order_id FROM ORDER_PRODUCT 
//     WHERE order_id IN (
//         SELECT order_id FROM ORDER_PRODUCT 
//         GROUP BY order_id HAVING COUNT(product_id) > 1
//     )
// ";
// $result3 = $conn->query($query3);

// 3️⃣ Nested Query: Orders that have more than one product (with customer name and quantity)
// ✅ Fixed Nested Query: Unique orders (no redundancy) with total quantity per order
$query3 = "
    SELECT 
        o.order_id, 
        c.name AS customer_name, 
        SUM(op.quantity) AS total_quantity
    FROM ORDERS o
    JOIN CUSTOMER c ON o.customer_id = c.customer_id
    JOIN ORDER_PRODUCT op ON o.order_id = op.order_id
    WHERE o.order_id IN (
        SELECT order_id 
        FROM ORDER_PRODUCT 
        GROUP BY order_id 
        HAVING COUNT(product_id) > 1
    )
    GROUP BY o.order_id, c.name
    ORDER BY o.order_id;
";
$result3 = $conn->query($query3);



// 4️⃣ Nested Query: Customers who haven’t made any payments yet
$query4 = "
    SELECT name, email FROM CUSTOMER 
    WHERE customer_id NOT IN (
        SELECT o.customer_id FROM ORDERS o
        JOIN PAYMENT p ON o.order_id = p.order_id
    )
";
$result4 = $conn->query($query4);
?>

<!-- Table 1 -->
<h3>💰 Customers with Orders Above Average Payment</h3>
<table>
<tr><th>Name</th><th>City</th></tr>
<?php if ($result1->num_rows > 0) {
    while($row = $result1->fetch_assoc()) {
        echo "<tr><td>{$row['name']}</td><td>{$row['city']}</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>No results found</td></tr>";
} ?>
</table>

<!-- Table 2 -->
<h3>📦 Products Priced Above Average</h3>
<table>
<tr><th>Product Name</th><th>Price (₹)</th></tr>
<?php if ($result2->num_rows > 0) {
    while($row = $result2->fetch_assoc()) {
        echo "<tr><td>{$row['product_name']}</td><td>".number_format($row['price'],2)."</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>No results found</td></tr>";
} ?>
</table>

<!-- Table 3 -->
<h3>🧾 Orders Containing Multiple Products</h3>
<table>
<tr><th>Order ID</th><th>Customer Name</th><th>Total Quantity</th></tr>
<?php if ($result3->num_rows > 0) {
    while($row = $result3->fetch_assoc()) {
        echo "<tr><td>{$row['order_id']}</td><td>{$row['customer_name']}</td><td>{$row['total_quantity']}</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>No results found</td></tr>";
} ?>
</table>



<!-- Table 4 -->
<h3>🚫 Customers Without Any Payments</h3>
<table>
<tr><th>Name</th><th>Email</th></tr>
<?php if ($result4->num_rows > 0) {
    while($row = $result4->fetch_assoc()) {
        echo "<tr><td>{$row['name']}</td><td>{$row['email']}</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>No results found</td></tr>";
} ?>
</table>

<a href="index.html" class="back-btn">⬅ Back to Home</a>

</body>
</html>

<?php
include("db.php"); // Database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Product</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #f1f8e9);
            color: #333;
            text-align: center;
            padding: 40px;
        }

        h1 {
            color: #004d40;
            font-size: 36px;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 40px;
            background-color: #ffffff;
            padding: 20px;
            display: inline-block;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        input[type="text"] {
            padding: 10px;
            width: 250px;
            border: 2px solid #00796b;
            border-radius: 6px;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: #004d40;
        }

        input[type="submit"] {
            background-color: #00796b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 10px;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #004d40;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 90%;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #004d40;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f1f8e9;
        }

        h2 {
            color: #00695c;
            margin-bottom: 15px;
        }

        h3 {
            color: red;
        }

        a {
            text-decoration: none;
            color: #00796b;
            display: inline-block;
            margin-top: 20px;
        }

        a:hover {
            color: #004d40;
        }
    </style>
</head>
<body>

<h1>🔍 Search Product</h1>

<form method="POST" action="">
    <label><strong>Enter Product Name:</strong></label>
    <input type="text" name="product_name" placeholder="e.g. Shoes" required>
    <input type="submit" name="search" value="Search">
</form>

<?php
if (isset($_POST['search'])) {
    $product_name = $_POST['product_name'];

    // ✅ Correct Query Based on Your Schema
    $query = "
        SELECT 
            p.product_id,
            p.product_name,
            p.price,
            op.quantity,
            o.order_id,
            o.order_date,
            c.customer_id,
            c.name AS customer_name,
            c.city
        FROM PRODUCT p
        JOIN ORDER_PRODUCT op ON p.product_id = op.product_id
        JOIN ORDERS o ON op.order_id = o.order_id
        JOIN CUSTOMER c ON o.customer_id = c.customer_id
        WHERE p.product_name LIKE '%$product_name%'
        ORDER BY o.order_date DESC;
    ";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results for '<em>$product_name</em>'</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>City</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['order_date']}</td>
                    <td>{$row['customer_id']}</td>
                    <td>{$row['customer_name']}</td>
                    <td>{$row['city']}</td>
                    <td>{$row['product_id']}</td>
                    <td>{$row['product_name']}</td>
                    <td>₹ {$row['price']}</td>
                    <td>{$row['quantity']}</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<h3>No product found with name '<em>$product_name</em>'</h3>";
    }
}
?>

<br><br>
<a href="index.html">⬅ Back to Home</a>

</body>
</html>

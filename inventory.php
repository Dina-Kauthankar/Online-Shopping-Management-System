<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_shopping_db2";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products with quantity
$sql = "SELECT product_id, product_name, price, quantity FROM product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory - Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 70%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h2{
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h2>📦 Inventory - Available Products</h2>

<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Price (₹)</th>
        <th>Quantity Available</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["product_id"] . "</td>";
            echo "<td>" . $row["product_name"] . "</td>";
            echo "<td>" . $row["price"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No products available</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>

<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🔍 Search Customer</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #83a4d4, #b6fbff);
        color: #333;
        text-align: center;
        padding: 40px;
        margin: 0;
    }

    h2 {
        color: #004e92;
        margin-bottom: 20px;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    form {
        margin-bottom: 30px;
        background: rgba(255, 255, 255, 0.3);
        padding: 20px;
        border-radius: 10px;
        display: inline-block;
    }

    input[type="text"], input[type="submit"] {
        padding: 10px;
        border-radius: 6px;
        border: none;
        margin: 5px;
        font-size: 15px;
    }

    input[type="submit"] {
        background: #004e92;
        color: #fff;
        cursor: pointer;
        transition: 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #003366;
    }

    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        background: rgba(255,255,255,0.8);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    th {
        background: #004e92;
        color: white;
    }

    tr:hover {
        background-color: #e3f2fd;
    }

    .back-btn {
        margin-top: 30px;
        display: inline-block;
        padding: 10px 25px;
        background: #004e92;
        color: white;
        text-decoration: none;
        border-radius: 6px;
    }
</style>
</head>
<body>

<h2>🔍 Search Customer by Name</h2>

<form method="POST">
    <input type="text" name="search_name" placeholder="Enter customer name..." required>
    <input type="submit" value="Search">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['search_name']);

    // 1️⃣ FETCH CUSTOMER DETAILS
    $stmt = $conn->prepare("SELECT * FROM customer WHERE name LIKE ?");
    $like = "%".$name."%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $customers = $stmt->get_result();

    if ($customers->num_rows == 0) {
        echo "<p class='no-result'>No customer found named <b>$name</b></p>";
    } else {

        while ($c = $customers->fetch_assoc()) {

            $customer_id = $c['customer_id'];

            echo "<h2>👤 Customer Details</h2>";
            echo "<table>
                    <tr><th>ID</th><td>{$c['customer_id']}</td></tr>
                    <tr><th>Name</th><td>{$c['name']}</td></tr>
                    <tr><th>Email</th><td>{$c['email']}</td></tr>
                    <tr><th>City</th><td>{$c['city']}</td></tr>
                  </table>";

            // 2️⃣ COUNT TOTAL ORDERS
            $orderCount = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE customer_id = $customer_id")->fetch_assoc()['total'];

            echo "<h2>📦 Total Orders: $orderCount</h2>";

            // 3️⃣ LIST ALL ORDERS
            $orders = $conn->query("
                SELECT o.order_id, o.order_date, p.amount, p.type AS payment_type
                FROM orders o
                LEFT JOIN payment p ON o.order_id = p.order_id
                WHERE o.customer_id = $customer_id
                ORDER BY o.order_date DESC
            ");

            if ($orders->num_rows > 0) {
                echo "<h2>🧾 Order History</h2>";
                echo "<table>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Payment Mode</th>
                            <th>Total Amount</th>
                        </tr>";

                while ($o = $orders->fetch_assoc()) {
                    echo "<tr>
                            <td>{$o['order_id']}</td>
                            <td>{$o['order_date']}</td>
                            <td>{$o['payment_type']}</td>
                            <td>₹ {$o['amount']}</td>
                          </tr>";

                    // 4️⃣ SHOW PRODUCTS INSIDE EACH ORDER
                    $details = $conn->query("
                        SELECT 
                            p.product_name,
                            p.price,
                            op.quantity,
                            (p.price * op.quantity) AS total
                        FROM order_product op
                        INNER JOIN product p ON op.product_id = p.product_id
                        WHERE op.order_id = {$o['order_id']}
                    ");

                    echo "<tr><td colspan='4'>
                          <table style='width:80%; margin:auto;'>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Line Total</th>
                            </tr>";

                    while ($item = $details->fetch_assoc()) {
                        echo "<tr>
                                <td>{$item['product_name']}</td>
                                <td>₹ {$item['price']}</td>
                                <td>{$item['quantity']}</td>
                                <td>₹ {$item['total']}</td>
                              </tr>";
                    }

                    echo "</table></td></tr>";
                }

                echo "</table>";
            }
        }
    }
}
?>

<a href="index.html" class="back-btn">⬅ Back</a>

</body>
</html>

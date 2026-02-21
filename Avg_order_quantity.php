<?php
include 'db.php';

// Corrected SQL query: Join CUSTOMER → ORDERS → ORDER_PRODUCT
$sql = "
  SELECT 
      c.customer_id, 
      c.name AS customer_name, 
      ROUND(AVG(op.quantity), 2) AS avg_quantity
  FROM CUSTOMER c
  JOIN ORDERS o ON c.customer_id = o.customer_id
  JOIN ORDER_PRODUCT op ON o.order_id = op.order_id
  GROUP BY c.customer_id, c.name
  ORDER BY avg_quantity DESC;
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Average Order Quantity per Customer</title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
  }

  body {
    background: linear-gradient(135deg, #F9D423, #FF4E50);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .container {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    padding: 40px 50px;
    width: 85%;
    max-width: 800px;
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(12px);
    color: #fff;
    text-align: center;
  }

  h1 {
    margin-bottom: 25px;
    font-size: 2em;
    color: #fff;
    text-shadow: 0 0 10px rgba(255,255,255,0.4);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    overflow: hidden;
    border-radius: 10px;
  }

  thead {
    background: rgba(255, 255, 255, 0.2);
  }

  th, td {
    padding: 14px 12px;
    text-align: center;
    font-size: 1.05em;
  }

  tr:nth-child(even) {
    background: rgba(255, 255, 255, 0.1);
  }

  tr:hover {
    background: rgba(255, 255, 255, 0.25);
    transition: 0.3s;
  }

  .no-data {
    margin-top: 20px;
    font-size: 1.1em;
    color: #f9f9f9;
  }

  .btn {
    display: inline-block;
    margin-top: 25px;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.3);
    color: white;
    text-decoration: none;
    transition: 0.3s;
  }

  .btn:hover {
    background: rgba(255, 255, 255, 0.5);
    transform: scale(1.05);
  }

</style>
</head>
<body>
  <div class="container">
    <h1>📦 Average Order Quantity per Customer</h1>

    <?php
    if ($result && $result->num_rows > 0) {
        echo "<table>
                <thead>
                  <tr>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Average Quantity Ordered</th>
                  </tr>
                </thead>
                <tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['customer_id']}</td>
                    <td>{$row['customer_name']}</td>
                    <td>{$row['avg_quantity']}</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='no-data'>No order data found in the database.</p>";
    }

    $conn->close();
    ?>

    <a href="index.html" class="btn">⬅ Back to Home</a>
  </div>
</body>
</html>

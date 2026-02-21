<?php
include 'db.php';

$productData = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];

    $sql = "SELECT product_id, product_name, price 
            FROM PRODUCT 
            WHERE product_name LIKE '%$product_name%'";
    $result = $conn->query($sql);
    $productData = $result && $result->num_rows > 0 ? $result : null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Product</title>
<style>
    body {
        background: linear-gradient(135deg, #43cea2, #185a9d);
        font-family: 'Poppins', sans-serif;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }

    .container {
        background: rgba(255, 255, 255, 0.12);
        padding: 40px 60px;
        border-radius: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        text-align: center;
        backdrop-filter: blur(8px);
        width: 450px;
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
        margin-bottom: 25px;
        font-size: 26px;
        text-shadow: 0 0 8px rgba(0,0,0,0.3);
    }

    input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border-radius: 10px;
        border: none;
        outline: none;
        font-size: 16px;
        text-align: center;
    }

    button {
        background: rgba(255,255,255,0.25);
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
        transition: 0.3s ease;
        width: 100%;
    }

    button:hover {
        background: rgba(255,255,255,0.4);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.3);
    }

    th {
        background: rgba(255,255,255,0.2);
        text-transform: uppercase;
    }

    tr:hover {
        background: rgba(255,255,255,0.15);
        transition: 0.3s ease;
    }

    .no-result {
        margin-top: 20px;
        background: rgba(255,255,255,0.15);
        padding: 10px;
        border-radius: 10px;
    }

    .back-btn {
        display: inline-block;
        margin-top: 25px;
        padding: 10px 18px;
        background: rgba(255,255,255,0.2);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        transition: background 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.4);
    }
</style>
</head>
<body>
    <div class="container">
        <h2>🔍 Search Product</h2>
        <form method="POST">
            <input type="text" name="product_name" placeholder="Enter product name..." required>
            <button type="submit">Search</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($productData) {
                echo "<table>
                        <tr><th>Product ID</th><th>Product Name</th><th>Price (₹)</th></tr>";
                while ($row = $productData->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['product_id']}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['price']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='no-result'>❌ No products found with that name.</div>";
            }
        }
        ?>
        <a href="index.html" class="back-btn">⬅ Back</a>
    </div>
</body>
</html>

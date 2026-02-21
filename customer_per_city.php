<?php
include 'db.php';

// Query to count total customers per city
$sql = "
    SELECT city, COUNT(customer_id) AS total_customers
    FROM CUSTOMER
    GROUP BY city
    ORDER BY total_customers DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customers per City</title>
<style>
    body {
        background: linear-gradient(135deg, #667eea, #764ba2);
        font-family: 'Poppins', sans-serif;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }

    .table-container {
        background: rgba(255,255,255,0.1);
        padding: 40px 60px;
        border-radius: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        text-align: center;
        backdrop-filter: blur(8px);
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
        margin-bottom: 25px;
        font-size: 26px;
        letter-spacing: 1px;
        color: #fff;
        text-shadow: 0 0 8px rgba(0,0,0,0.3);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.3);
    }

    th {
        background: rgba(255,255,255,0.2);
        font-weight: 600;
        text-transform: uppercase;
    }

    tr:hover {
        background: rgba(255,255,255,0.15);
        transition: 0.3s ease;
    }

    td {
        font-size: 16px;
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
    <div class="table-container">
        <h2>🏙️ Total Customers per City</h2>
        <table>
            <tr>
                <th>City</th>
                <th>Total Customers</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['city']}</td>
                            <td>{$row['total_customers']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No data found!</td></tr>";
            }
            ?>
        </table>
        <a href="index.html" class="back-btn">⬅ Back</a>
    </div>
</body>
</html>

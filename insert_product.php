<?php
// add_order.php
include 'db.php';

// Fetch all products (use correct table name)
$productQuery = "SELECT product_id, product_name, price FROM PRODUCT";
$productResult = $conn->query($productQuery);
if ($productResult === false) {
    die("Error fetching products: " . $conn->error);
}

$msg = '';
// When form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // product_id is stored as JSON string in the <option> value
    $product_json = $_POST['product_id'] ?? '';
    $decoded = json_decode($product_json, true);

    if (!is_array($decoded) || empty($decoded['product_id'])) {
        $msg = "❌ Invalid product selected.";
    } else {
        $product_id = (int)$decoded['product_id'];
        $quantity   = (int)($_POST['quantity'] ?? 0);

        if ($quantity <= 0) {
            $msg = "❌ Quantity must be at least 1.";
        } else {
            // NOTE: use table name exactly as in your DB. I use ORDERS and ORDER_PRODUCT.
            // If your DB uses lowercase or different names, change them consistently.
            $customer_id = 1; // change to dynamic later (dropdown)
            $order_date  = date('Y-m-d');

            // Start transaction so both inserts succeed or neither
            $conn->begin_transaction();

            // Insert into ORDERS
            $sql_order = "INSERT INTO ORDERS (order_date, customer_id) VALUES (?, ?)";
            $stmt1 = $conn->prepare($sql_order);
            if (!$stmt1) {
                $msg = "❌ Prepare failed (ORDERS): " . $conn->error;
                $conn->rollback();
            } else {
                $stmt1->bind_param('si', $order_date, $customer_id);
                if (!$stmt1->execute()) {
                    $msg = "❌ Insert ORDERS failed: " . $stmt1->error;
                    $conn->rollback();
                } else {
                    $order_id = $conn->insert_id;

                    // Insert into ORDER_PRODUCT
                    $sql_op = "INSERT INTO ORDER_PRODUCT (order_id, product_id, quantity) VALUES (?, ?, ?)";
                    $stmt2 = $conn->prepare($sql_op);
                    if (!$stmt2) {
                        $msg = "❌ Prepare failed (ORDER_PRODUCT): " . $conn->error;
                        $conn->rollback();
                    } else {
                        $stmt2->bind_param('iii', $order_id, $product_id, $quantity);
                        if (!$stmt2->execute()) {
                            $msg = "❌ Insert ORDER_PRODUCT failed: " . $stmt2->error;
                            $conn->rollback();
                        } else {
                            // success
                            $conn->commit();
                            $msg = "✅ Order placed successfully (Order ID: $order_id).";
                        }
                        $stmt2->close();
                    }
                }
                $stmt1->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Add Order</title>
<style>
    body { background: linear-gradient(135deg,#348F50,#56B4D3); font-family:'Poppins',sans-serif; color:#fff; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
    .card { background: rgba(255,255,255,0.12); padding:34px; border-radius:14px; width:420px; box-shadow:0 10px 30px rgba(0,0,0,0.2); }
    h2 { margin-bottom:16px; }
    select,input,button { width:100%; padding:12px; margin:10px 0; border-radius:10px; border:none; font-size:15px; }
    select,input { background:#fff; color:#222; }
    button { background: rgba(255,255,255,0.18); color:#fff; cursor:pointer; }
    .msg { margin-top:10px; padding:10px; border-radius:8px; background: rgba(0,0,0,0.25); }
    a { color:#fff; text-decoration:none; display:inline-block; margin-top:12px; }
</style>

<script>
let currentPrice = 0;
function updateProduct() {
    const sel = document.getElementById('product_list').value;
    if (!sel) {
        document.getElementById('total_price').value = '';
        currentPrice = 0;
        return;
    }
    const p = JSON.parse(sel);
    currentPrice = parseFloat(p.price);
    calculateTotal();
}
function calculateTotal() {
    const q = parseInt(document.getElementById('quantity').value || 0, 10);
    if (q > 0 && currentPrice) {
        document.getElementById('total_price').value = (q * currentPrice).toFixed(2);
    } else {
        document.getElementById('total_price').value = '';
    }
}
</script>
</head>
<body>
<div class="card">
    <h2>🛒 Place New Order</h2>

    <form method="post">
        <select id="product_list" name="product_id" onchange="updateProduct()" required>
            <option value="" disabled selected>-- Select Product --</option>
            <?php
            // build options; value is JSON string with id & price
            while ($row = $productResult->fetch_assoc()) {
                $json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                echo "<option value='{$json}'>{$row['product_name']} — ₹" . number_format($row['price'],2) . "</option>";
            }
            ?>
        </select>

        <input id="quantity" type="number" name="quantity" min="1" placeholder="Quantity" required
               oninput="calculateTotal()" />

        <input id="total_price" type="text" placeholder="Total Price (auto)" readonly />

        <button type="submit">Place Order</button>
    </form>

    <?php if ($msg): ?>
        <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <a href="index.html">⬅ Back</a>
</div>
</body>
</html>

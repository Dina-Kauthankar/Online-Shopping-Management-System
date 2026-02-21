<?php
include 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_id = $_POST['customer_id'];
    $order_date = date('Y-m-d');
    $payment_type = $_POST['payment_type'];
    $grand_total = $_POST['grand_total'];

    $conn->begin_transaction();

    try {

        // INSERT INTO `order` TABLE (IMPORTANT — USE BACKTICKS)
        $sql = "INSERT INTO `orders` (order_date, customer_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $order_date, $customer_id);
        $stmt->execute();

        $order_id = $conn->insert_id;

        // Insert multiple order items
        foreach ($_POST['product_id'] as $index => $product_id) {

            $qty = $_POST['quantity'][$index];

            $sql = "INSERT INTO order_product (order_id, product_id, quantity)
                    VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $order_id, $product_id, $qty);
            $stmt->execute();
        }

        // Insert payment
        $payment_date = date('Y-m-d');

        $sql = "INSERT INTO payment (amount, type, payment_date, order_id)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $grand_total, $payment_type, $payment_date, $order_id);
        $stmt->execute();

        $conn->commit();
        $message = "Order successfully placed!";

    } catch (Exception $e) {
        $conn->rollback();
        $message = "Transaction failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Order</title>
<style>
body{
    background:linear-gradient(135deg,#667eea,#764ba2);
    font-family:Poppins;
    color:white;
    padding:30px;
}
.container{
    width:85%;
    margin:auto;
    background:rgba(255,255,255,0.15);
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 25px rgba(0,0,0,0.4);
}
input,select{
    padding:10px;
    width:100%;
    border:none;
    border-radius:8px;
    margin-bottom:10px;
}
button{
    padding:12px 25px;
    background:#4a00e0;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
}
button:hover{
    background:#3500b5;
}
.table{
    width:100%;
    margin-top:15px;
}
.table input{
    width:95%;
}
.add-btn{
    margin-top:10px;
    background:#22c55e;
}
.remove-btn{
    background:red;
    padding:3px 10px;
}
</style>

<script>
    // If product selected from view_products.html
window.onload = function() {
    const url = new URL(window.location.href);
    const pid = url.searchParams.get("pid");
    const pname = url.searchParams.get("pname");
    const price = url.searchParams.get("price");

    if (pid) {
        let table = document.getElementById("itemsTable");
        let newRow = table.insertRow();

        newRow.innerHTML = `
            <td>
                <select name="product_id[]" class="productSelect" onchange="updatePrice(this.parentNode.parentNode)">
                    <option value="${pid}" data-price="${price}" selected>${pname}</option>
                </select>
            </td>
            <td><input type="text" class="priceInput" value="${price}" readonly></td>
            <td><input type="number" name="quantity[]" class="qtyInput" value="1" min="1" onchange="updatePrice(this.parentNode.parentNode)"></td>
            <td><input type="text" class="lineTotal" value="${price}" readonly></td>
            <td><button type="button" class="remove-btn" onclick="this.parentNode.parentNode.remove(); updateGrandTotal();">X</button></td>
        `;

        updateGrandTotal();
    }
}
// ------------------------------------------------------
function updatePrice(row){
    let productSelect = row.querySelector(".productSelect");
    let priceInput = row.querySelector(".priceInput");
    let quantityInput = row.querySelector(".qtyInput");
    let totalInput = row.querySelector(".lineTotal");

    let price = productSelect.options[productSelect.selectedIndex].dataset.price;

    priceInput.value = price;
    totalInput.value = (price * quantityInput.value).toFixed(2);

    updateGrandTotal();
}

function updateGrandTotal(){
    let totals = document.querySelectorAll(".lineTotal");
    let grand = 0;
    totals.forEach(t => { grand += parseFloat(t.value || 0); });
    document.getElementById("grand_total").innerText = grand.toFixed(2);
    document.getElementById("grand_total_input").value = grand.toFixed(2);
}

function addRow(){
    let table = document.getElementById("itemsTable");
    let newRow = table.insertRow();

    newRow.innerHTML = `
    <td>
        <select name="product_id[]" class="productSelect" onchange="updatePrice(this.parentNode.parentNode)">
            <option value="">-- Select Product --</option>
            <?php
            $res = $conn->query("SELECT * FROM product");
            while ($p = $res->fetch_assoc()) {
                echo "<option value='{$p['product_id']}' data-price='{$p['price']}'>{$p['product_name']}</option>";
            }
            ?>
        </select>
    </td>
    <td><input type="text" class="priceInput" readonly></td>
    <td><input type="number" name="quantity[]" class="qtyInput" value="1" min="1" onchange="updatePrice(this.parentNode.parentNode)"></td>
    <td><input type="text" class="lineTotal" readonly></td>
    <td><button type="button" class="remove-btn" onclick="this.parentNode.parentNode.remove(); updateGrandTotal();">X</button></td>
    `;
}
</script>

</head>

<body>

<div class="container">

<h1>🛒 Add Order</h1>
<p>One customer can order multiple products — then confirm & pay</p>

<button type="button" 
        onclick="window.location.href='view_product.html'" 
        style="background:#00b7ff;margin-bottom:15px;">
    📦 View Products
</button>
<!-- ----------------- -->

<?php if ($message): ?>
<div style="background:#00000033;padding:10px;border-radius:8px;margin-bottom:15px;">
    <?= $message ?>
</div>
<?php endif; ?>

<form method="POST">

<label>Select Customer</label>
<select name="customer_id" required>
<option value="">-- Select Customer --</option>
<?php
$res = $conn->query("SELECT * FROM customer");
while ($c = $res->fetch_assoc()){
    echo "<option value='{$c['customer_id']}'>{$c['name']}</option>";
}
?>
</select>

<label>Select Payment Type</label>
<select name="payment_type" required>
    <option value="UPI">UPI</option>
    <option value="NetBanking">NetBanking</option>
    <option value="Card">Card Payment</option>
</select>

<h3>Order Items</h3>
<table border="1" class="table">
<thead>
<tr>
<th>Product</th>
<th>Unit Price</th>
<th>Quantity</th>
<th>Line Total</th>
<th>Remove</th>
</tr>
</thead>
<tbody id="itemsTable"></tbody>
</table>

<button type="button" class="add-btn" onclick="addRow()">+ Add Item</button>

<h2>Grand Total: ₹ <span id="grand_total">0.00</span></h2>
<input type="hidden" name="grand_total" id="grand_total_input">

<button type="submit">Confirm & Place Order</button>


</form>
</div>

<script>
addRow();
</script>

</body>
</html>

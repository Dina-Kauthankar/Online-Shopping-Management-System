<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $city  = $_POST['city'];

    $sql = "INSERT INTO CUSTOMER (name, email, city) VALUES ('$name', '$email', '$city')";
    
    if ($conn->query($sql)) {
        $msg = "✅ Customer added successfully!";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Customer</title>
<style>
    body {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        font-family: 'Poppins', sans-serif;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }
    .form-container {
        background: rgba(255,255,255,0.1);
        padding: 30px 50px;
        border-radius: 20px;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
        text-align: center;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 8px;
        border: none;
        outline: none;
    }
    button, a {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin: 5px;
    }
    button:hover, a:hover {
        background: rgba(255,255,255,0.4);
    }
</style>
</head>
<body>
    <div class="form-container">
        <h2>🧍 Add New Customer</h2>

        <form method="POST">
            <input type="text" name="name" placeholder="Enter Name" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="text" name="city" placeholder="Enter City" required>
            <button type="submit">Add Customer</button>
        </form>

        <?php if (isset($msg)) echo "<p>$msg</p>"; ?>

        <a href="index.html">⬅ Back</a>
    </div>
</body>
</html>

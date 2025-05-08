<?php
include('connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure user is logged in
    $user_id = $_SESSION['user_id'] ?? 0;

    // Check if the user is logged in (i.e., valid user_id)
    if ($user_id == 0) {
        echo "You must be logged in to place an order.";
        exit;
    }

    // Verify that the user_id exists in the users table
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If user_id does not exist in the users table, handle the error
    if ($result->num_rows == 0) {
        echo "Invalid user. Please log in again.";
        exit;
    }

    $items = [];

    // Collect order items
    foreach ($_POST as $item => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            $item_cleaned = str_replace(['_d', '_s'], '', $item); // clean _d and _s suffixes for dinner and sapper
            $items[] = [
                'user_id' => $user_id,
                'item' => $item_cleaned,
                'quantity' => $quantity
            ];
        }
    }

    // Insert orders
    foreach ($items as $order) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, item, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $order['user_id'], $order['item'], $order['quantity']);
        $stmt->execute();
    }

    echo '<script>history.replaceState(null, null, location.href); document.addEventListener("DOMContentLoaded", function() { document.getElementById("success-msg").style.display = "block"; });</script>';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CoICT Cafeteria Menu</title>
    <style>
        body {background-image:url('images/cafe3.jpg'); background-size:cover; background-position:center; background-repeat:no-repeat; }
        h1, h2 { text-align: center; color:#28a745; }
        .tabs { display: flex; justify-content: space-around; margin-bottom: 20px; flex-wrap: wrap; gap: 50px; }
        .tab-button { padding: 10px 20px; background-color: #eee; border: none; cursor: pointer; border-radius: 5px; font-weight: bold; transition: background 0.3s; }
        .tab-button.active { background-color: #28a745; color: white; }
        .tab-content { display: none; background-color: gold; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .tab-content.active { display: block; }
        .menu-item { display: flex; justify-content: space-between; align-items: center; margin: 10px 0; padding: 10px; background: #f9f9f9; border-radius: 8px; flex-wrap: wrap; }
        .menu-item span { flex: 1; }
        .menu-item input[type=number] { width: 60px; padding: 5px; margin-right: 10px; }
        .category-total { text-align: right; font-weight: bold; margin-top: 10px; }
        .submit-section { text-align: center; margin-top: 20px; }
        .submit-section button { padding: 10px 20px; font-size: 16px; background-color: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; }
        .success-message { text-align: center; margin-top: 20px; font-weight: bold; color: #fff; background-color: #28a745; padding: 10px; border-radius: 8px; display: none; }
    </style>
</head>
<body>
<h1>Welcome to CoICT Cafeteria</h1>
<h2>View Menu and Place Your Order</h2>
<div id="success-msg" class="success-message">Order Submitted Successfully!</div>
<form action="" method="POST">
    <div class="tabs">
        <button type="button" class="tab-button active" onclick="showTab(event, 'breakfast')">Breakfast</button>
        <button type="button" class="tab-button" onclick="showTab(event, 'lunch')">Lunch</button>
        <button type="button" class="tab-button" onclick="showTab(event, 'dinner')">Dinner</button>
        <button type="button" class="tab-button" onclick="showTab(event, 'sapper')">Sapper</button>
    </div>

    <div id="breakfast" class="tab-content active">
        <h2>Breakfast</h2>
        <?php
        $breakfast_items = [
            ['Chai Rangi', 300],
            ['Chai Maziwa', 500],
            ['Andazi', 300],
            ['Chapati', 300],
            ['Donati', 300],
            ['Supu', 2000],
            ['Sambusa', 500],
            ['Mihogo', 500],
            ['Viazi', 500]
        ];
        foreach ($breakfast_items as [$name, $price]) {
            echo "<div class='menu-item' data-name='$name' data-price='$price'>
                    <span>$name - Tsh $price</span>
                    <input type='number' name='$name' min='0' value='0' onchange='updateTotal(this)'>
                    <span class='total'>Total: 0</span>
                  </div>";
        }
        ?>
        <div class="category-total" id="breakfast-total">Category Total: Tsh 0</div>
    </div>

    <div id="lunch" class="tab-content">
        <h2>Lunch</h2>
        <?php
        $lunch_items = [
            ['Wali Kuku', 2000],
            ['Wali Nyama', 1700],
            ['Wali Nyama Makange', 2000],
            ['Chips Yai', 2500],
            ['Chips Kavu', 1500],
            ['Ugali Samaki', 2000],
            ['Ugali Nyama', 1700],
            ['Ugali Nyama choma', 2000],
            ['Ugali Dagaa', 1500]
        ];
        foreach ($lunch_items as [$name, $price]) {
            echo "<div class='menu-item' data-name='$name' data-price='$price'>
                    <span>$name - Tsh $price</span>
                    <input type='number' name='$name' min='0' value='0' onchange='updateTotal(this)'>
                    <span class='total'>Total: 0</span>
                  </div>";
        }
        ?>
        <div class="category-total" id="lunch-total">Category Total: Tsh 0</div>
    </div>

    <div id="dinner" class="tab-content">
        <h2>Dinner</h2>
        <?php
        $dinner_items = [
            ['Wali Kuku', 2000],
            ['Wali Nyama', 1700],
            ['Wali Nyama Makange', 2000],
            ['Chips Yai', 2500],
            ['Ugali Samaki', 2000],
            ['Ugali Nyama', 1700],
            ['Ugali Nyama choma', 2000],
            ['Ugali Dagaa', 1500],
            ['Biriani', 5000]

        ];
        foreach ($dinner_items as [$name, $price]) {
            echo "<div class='menu-item' data-name='$name' data-price='$price'>
                    <span>$name - Tsh $price</span>
                    <input type='number' name='$name' min='0' value='0' onchange='updateTotal(this)'>
                    <span class='total'>Total: 0</span>
                  </div>";
        }
        ?>
        <div class="category-total" id="dinner-total">Category Total: Tsh 0</div>
    </div>

    <div id="sapper" class="tab-content">
        <h2>Sapper</h2>
        <?php
        $sapper_items = [
            ['Wali Kuku', 2000],
            ['Wali Nyama', 1700],
            ['Wali Nyama Makange', 2000],
            ['Chips Yai', 2500],
            ['Chips Kavu', 1500],
            ['Ugali Samaki', 2000],
            ['Ugali Nyama', 1700],
            ['Ugali Nyama choma', 2000],
            ['Ugali Dagaa', 1500],
            ['Biriani', 5000]

        ];
        foreach ($sapper_items as [$name, $price]) {
            echo "<div class='menu-item' data-name='$name' data-price='$price'>
                    <span>$name - Tsh $price</span>
                    <input type='number' name='$name' min='0' value='0' onchange='updateTotal(this)'>
                    <span class='total'>Total: 0</span>
                  </div>";
        }
        ?>
        <div class="category-total" id="sapper-total">Category Total: Tsh 0</div>
    </div>

    <div class="submit-section">
        <button type="submit">Submit Order</button>
      <a href="payment.php"><button class="submit-btn" type="button">Proceed to Make Payment</button></a>
    </div>
</form>

<script>
    function showTab(evt, tabId) {
        const tabs = document.querySelectorAll('.tab-content');
        const buttons = document.querySelectorAll('.tab-button');
        tabs.forEach(t => t.classList.remove('active'));
        buttons.forEach(b => b.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        evt.currentTarget.classList.add('active');
    }

    function updateTotal(input) {
        const container = input.closest('.menu-item');
        const price = parseInt(container.getAttribute('data-price'));
        const quantity = parseInt(input.value) || 0;
        const total = price * quantity;
        container.querySelector('.total').textContent = 'Total: Tsh ' + total;

        updateCategoryTotal(container.closest('.tab-content'));
    }

    function updateCategoryTotal(tabContent) {
        const items = tabContent.querySelectorAll('.menu-item');
        let categoryTotal = 0;
        items.forEach(item => {
            const price = parseInt(item.getAttribute('data-price'));
            const qtyInput = item.querySelector('input[type=number]');
            const quantity = parseInt(qtyInput.value) || 0;
            categoryTotal += price * quantity;
        });

        const categoryTotalDisplay = tabContent.querySelector('.category-total');
        categoryTotalDisplay.textContent = 'Category Total: Tsh ' + categoryTotal;
    }

    // Show success message after form submission if triggered by PHP script
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById("success-msg").style.display === "block") {
            setTimeout(() => {
                document.getElementById("success-msg").style.display = "none";
            }, 1500); // Hide after 4 seconds
        }
    });
</script>

</body>
</html>

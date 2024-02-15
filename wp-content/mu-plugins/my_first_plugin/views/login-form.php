<?php

if ($_POST['button']) {
    $name = test_input($_POST["name"]);
    $phone = test_input($_POST["phone"]);

    $nameErr = $phoneErr = "";
    $name = $phone = "";

    if (empty($_POST["name"])) {
        $nameErr = 'name is required';
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["phone"])) {
        $phoneErr = 'phone is required';
    } else {
        $phone = test_input($_POST["phone"]);
    }

    $userdata = get_user_by_email($_POST['phone']);

    if (!$userdata) {
        $user = wp_create_user($_POST['name'], $_POST['phone'], $_POST['phone']);
    }

    $order_data = array(
        'status' => 'completed',
        'customer_id' => 'id',
        'created_via' => 'customer',
    );
    $order = wc_create_order($order_data);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!DOCTYPE HTML>
<html>
<head>
</head>
<body>
<form method="POST">
    Name: <input type="text" name="name">
    <br><br>
    phone: <input type="text" name="phone">
    <br><br>
    <button type="submit" name="button" value="Submit">send</button>
</form>
</body>
</html>

<?php
echo $phoneErr;
echo $nameErr;

?>

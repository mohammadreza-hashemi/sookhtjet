<?php
if (isset($_POST['button'])) {

    $name = test_input($_POST["name"]);
    $phone = test_input($_POST["phone"]);

    echo var_dump(wp_verify_nonce('add_order'));
    die();

    if (!wp_verify_nonce('add_order')) {
        echo 'nonce is ok';
    } else {
        echo 'nonce is faild';
    }

    $nameErr = $phoneErr = "";

    if (empty($_POST["name"])) {
        $nameErr = 'name is required';
    }

    if (empty($_POST["phone"])) {
        $phoneErr = 'phone is required';
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
    <?php echo wp_nonce_field(-1, 'add_order') ?>
    Name: <input type="text" name="name">
    <br><br>
    phone: <input type="text" name="phone">
    <br><br>
    <button type="submit" name="button" value="submit">send</button>

</form>
</body>
</html>
<?php

echo $nameErr;
echo $phoneErr;

?>

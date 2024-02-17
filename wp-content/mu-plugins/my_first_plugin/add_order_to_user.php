<?php

define('TWELVE_COLUMNS_MAKING_MONEY', '36');
define('GIFT', '17');

$error = $name = $phone = $success = '';

if (isset($_POST['button'])) {

    $name = sanitize_text_field($_POST["name"]);
    $phone = sanitize_text_field($_POST["phone"]);
    $nonce = $_POST['add_order'];

    if (!check_nonce($nonce)) return $error = ' نانس معتبر نمیباشد !';

    if (!check_name($name)) return $error = 'نام کاربری معتبر نمیباشد !';

    $phone = phone_sanetizer($phone);
    if (!check_phone($phone)) return $error = 'شماره موبایل  معتبر نمیباشد !';

    $user = get_user_by('login', $phone);

    if (!$user) {
        $user = wp_create_user($phone, $name);
    }

    $order = create_order($user);

    add_gift_to_order($order);

    if ($order) {
        $success = 'success';
    }
}
/**
 * @param string $phone
 * @return array|string|string[]|null
 */
function phone_sanetizer(string $phone)
{
    $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT); // 0912abc to 0912
    $phone = ltrim($phone);
    $phone = rtrim($phone);
    $phone = stripslashes($phone);
    $phone = preg_replace('/[^\dxX]/', '', $phone); //+91-5345534534 to 915345534534
    return $phone;
}

/**
 * @param string $name
 * @return bool
 */
function check_name(string $name)
{
    if (empty($name) || strlen($name) <= 3) {
        return false;
    } else {
        return true;
    }
}

/**
 * @param string $phone
 * @return bool
 */
function check_phone(string $phone)
{
    $pattern = "/^(?:98|\+98|0)?9[0-9]{9}$/";
    if (empty($phone) || !preg_match($pattern, $phone)) {
        return false;
    } else {
        return true;
    }
}

/**
 * @param $nonce
 * @return bool
 */
function check_nonce($nonce)
{
    if (!wp_verify_nonce($nonce, 'add_order_action')) {
        return false;
    } else {
        return true;
    }
}

/**
 * @param $user
 * @return WC_Order|WP_Error
 */
function create_order($user)
{
    $userId = $user->ID;
    $order_data = array(
        'status' => 'completed',
        'customer_id' => $userId,
        'created_via' => $user->user_nicename,
        'customer_note' => $user->user_login,
    );

    $order = wc_create_order($order_data);
    $order->set_customer_id($userId);
    $order->set_billing_first_name('mohammadreza biling');
    $order->set_billing_city('tehran');
    $order->set_billing_email('setbillingemail@gmail.com');
    $order->set_created_via('created via');
    $order->set_address('set address');
    $order->add_data();
    $order->save();

    $product = wc_get_product('TWELVE_COLUMNS_MAKING_MONEY');
    $add_product_to_user_order = $order->add_product($product, 1);
    return $order;
}

/**
 * @param $order
 * @return void
 */
function add_gift_to_order($order)
{
    $start_time = "2024/02/17 12:49:11";
    $end_time = "2024/02/17 12:55:11";
    $current_time = date('Y/m/d h:i:s');

    if ($current_time > $start_time && $current_time < $end_time) {
        $product_gift = wc_get_product('GIFT');
        $add_product_to_user_order = $order->add_product($product_gift, 1);
    }

}









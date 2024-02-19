<?php
define('TWELVE_COLUMNS_MAKING_MONEY', '36');
define('GIFT', '17');
define('CAMPAIN_NAME', 'کمپین 12 ستون پولسازی ');

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

    if ($user) {
        $user = wp_update_user(
            array(
                'ID' => $user->ID,
                'user_nicename' => $name,
                'user_email' => 'm.' . $phone . '@soocktjet.com',
                'display_name' => $name
            ));
        $order = create_order($user);
    }

    if (!$user) {
        $user_id = wp_create_user($phone, $name);
        wp_update_user(
            array(
                'ID' => $user_id,
                'user_nicename' => $name,
                'user_email' => 'm.' . $phone . '@soocktjet.com',
                'display_name' => $name
            ));
        $order = create_order($user_id);
    }

    if ($order->get_status() === 'completed') add_gift_to_order($order);

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
    $phone = trim($phone);
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
    $user = get_user_by('ID', $user);
    $order_data = array(
        'status' => 'completed',
        'customer_id' => $user->ID,
        'created_via' => $user->nickname,
        'customer_note' => $user->user_login,
    );

    $order = wc_create_order($order_data);
    $order->set_customer_id($user->ID);
    $order->add_order_note(CAMPAIN_NAME . date('Y-m-d h:i:s') . 'خرید از لندینگ ');
    $order->set_billing_email($user->user_email);
    $order->set_billing_phone($user->user_login);
    $order->save();

    $product = wc_get_product(TWELVE_COLUMNS_MAKING_MONEY);
    $order->add_product($product, 1);

    add_order_to_another_server($user,$order);
    return $order;
}

/**
 * @param $order
 * @return void
 */
function add_gift_to_order($order)
{
    $start_time = "2024-02-19 08:00:00";
    $end_time = "2024-02-19 19:00:00";
    $current_time = date('Y-m-d h:i:s');

    if ($current_time > $start_time && $current_time < $end_time) {
        $product_gift = wc_get_product(GIFT);
        $add_product_to_user_order = $order->add_product($product_gift, 1);
    }
}


function add_order_to_another_server($user,$order)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://localhost/new_wordpress/wp-json/wp/v2/order',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('order_id' => 1, 'user_id' => '55', 'user_name' =>'test', 'app_version' => 'sookht_jet'),
    ));
    $response = curl_exec($curl);
    curl_close($curl);




    unset($curl);
}









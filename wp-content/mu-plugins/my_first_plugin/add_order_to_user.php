<?php
define('TWELVE_COLUMNS_MAKING_MONEY', '36');
define('GIFT', '17');
define('CAMPAIN_NAME', 'کمپین 12 ستون پولسازی ');

$error = $name = $phone = $success = '';

if (isset($_POST['button'])) {

    $name = sanitize_text_field($_POST["name"]);
    $phone = sanitize_text_field($_POST["phone"]);
    $nonce = $_POST['add_order'];

    // چی میشد بجای اینکه یک ارور نمایش بدیم همه ارور ها رو با هم نمایش بدیم به کاربر که همه رو باهم اصلاح کنه بعد فرم رو ارسال کنه برای ما
    if (!check_nonce($nonce)) return $error = ' نانس معتبر نمیباشد !';

    if (!check_name($name)) return $error = 'نام کاربری معتبر نمیباشد !';

    $phone = phone_sanetizer($phone);
    if (!check_phone($phone)) return $error = 'شماره موبایل  معتبر نمیباشد !';

    $user = get_user_by('login', $phone);

    if ($user) {
        // اگر یک جای کار اطلاعات کاربر بروزرسانی نشد چی؟
        $user = wp_update_user(
            array(
                'ID' => $user->ID,
                'user_nicename' => $phone,
                'user_email' => 'm.' . $phone . '@soocktjet.com',
                'display_name' => $name
            ));
        // بهتره که ما تا جایی که میتونیم کاری کنیم که کد کپی نداشته باشیم.
        $order = create_order($user);
    }

    if (!$user) {
        $user_id = wp_create_user($phone, $name);

        wp_update_user(
            array(
                'ID' => $user_id,
                'user_nicename' => $phone,
                'user_email' => 'm.' . $phone . '@soocktjet.com',
                'display_name' => $name
            ));
        $order = create_order($user_id);
        // بنظرت لازم هست ما بررسی کنیم که آیا این تابع بالا کار کرده یا نه که اگر کار نکرده بود ادامه پیدا نکنه کد؟
    }
        // اینجا هم قرار بود با هوک کار رو هندل کنی ولی هنوز با تابع داره این اتفاق میوفته
    if ($order->get_status() === 'completed') add_gift_to_order($order);
    // این بزرگوار هم داره راست میگه وقتی خط بالایی داری وضعیت سفارش رو میگیره دیگه اینجا یعنی هست و در نظر گرفتن شرط پایین اصلا اضافه است.
    // بیشتر میخوام بگم که مرحله ای فکر کن ببین تا اینجا چی ها رو چک کردی که وجود دارند.
    // وقتی مرحله ای چک کنی مطمئن میشی که چ شرطی جاش کجاست
    // سخت نگیر هر چی بیشتر کد بزنی بیشتر میاد دستت :)
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
// تابع ها بهتره که ورودی های تایپ داشته باشه که با فرمت های اشتباه پارامتر ها به این تابع ارسال نشن
function create_order($user)
{
    // چرا داریم یوزر رو میگیریم وقتی که یوزر رو به عنوان پارامتر داریم به این تابع میدیم؟
    // این $user که اینجا هست یک آبجکت هست برای اینکه با ایدی بگیری باید آی دی رو بفرستی نه کل آبجکت رو
    $user = get_user_by('ID', $user);
    // قبل اینکه یک کاری رو انجام بدیم بهتره چک کنیم هر اطلاعاتی که میخواییم باهاش کار کنیم وجود داره و اروری در کار نیست.
    // الان اینجا باید یوزر چک میشد که هست یا نه.
    $order_data = array(
        'status' => 'completed',
        'customer_id' => $user->ID,
        'created_via' => $user->nickname,
        'customer_note' => $user->user_login,
    );

    $order = wc_create_order($order_data);
    // اینجا هم باید چک بشه که اگر سفارش ساخته شده بود کار ادامه پیدا کنه
    $order->set_customer_id($user->ID);
    $order->add_order_note(CAMPAIN_NAME . date('Y-m-d ') . 'خرید از لندینگ ');
    $order->set_billing_email($user->user_email);
    $order->set_billing_phone($user->user_login);
    // تابع سیو آخر از کار صدا زده میشه که همه تغییراتی که تو دادی رو سیو کنه الان بعد از سیو باز داری روی سفارشت کار میکنی
    $order->save();
// این رو هم قبل از ساختن کار بگیری بهتره که هر کاری که با سفارش داری میکنی پشت هم بیوفته که خوانایی کد بیشتر بشه
    $product = wc_get_product(TWELVE_COLUMNS_MAKING_MONEY);
    $order->add_product($product, 1);
// بببین این هم باید توی یک شرایطی صدا زده بشه که مطمئن باشیم سفارش ساخته شده و یوزر هست ولی تا اینجا هیچ شرطی من ندیدم که این دوتا رو چک بکنه. اگر هر جای کار
    // ایراد بوجود بیاد کدت کرش میکنه اینجوری. تا جای ممکن باید خطا ها رو جلوش رو گرفت
    add_order_to_another_server($user, $order);
    return $order;
}

/**
 * @param $order
 * @return void
 */
function add_gift_to_order($order)
{
    $start_time = "2024-02-20 02:00:00";
    $end_time = "2024-02-20 19:00:00";
    $current_time = date('Y-m-d h:i:s');

    if ($current_time > $start_time && $current_time < $end_time) {
        $product_gift = wc_get_product(GIFT);
        // اینجا هم باید چک کنی که این پروداکت رو که داری میگیری ایا گرفته شده که بره برای اجرای خط بعدی
        $add_product_to_user_order = $order->add_product($product_gift, 1);
        // یک کار دیگه ای که در ادامه بهتره در جریانش باشی اینکه شرط هات رو باید با لاگ گیری ذخیره کنی که بفهمی کجای کارت ایراد داره
        // اینجوری یک فایل لاگ از کار هات داری که میتونی بهتر تاابع هایی که نوشتی رو دیباگ کنی
    }
}


function add_order_to_another_server($user, $order)
{

    // اینجا باید اطلاعات ورودی تابعت رو اعتبار سنجی کنی که ولید باشه
    // اگر نبود باید متوفق بشه کار و به کاربر ارور نمایش بده
    // یا حداقل یک لاگی جایی ذخیره بشه که بفهمیم اینجا مشکل هست.
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
        CURLOPT_POSTFIELDS => array('order_id' => $order->id, 'user_id' => $user->ID, 'user_name' => $user->display_name, 'app_version' => 'sookht_jet'),
        CURLOPT_HTTPHEADER => array(
            'nonce: '.wp_create_nonce( 'wp_rest' ),
            'token: my_token_script'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    // بعد از اینکه response رو میگیری باید چک کنی. ببینی چه جوابی گرفتی
    // کار درست انجام شده یا باگ داره
    // بعد به کاربر یک چیزی نمایش بدی یا یک لاگی بندازی.
    // اینجوری رو هوا ول کردنه.
    // یک روز میایی میبینی کد اجرا نشده و نمیدونی از کجا شروع کنی و کجا ایراد داره
    // و باید کجای کار رو از سر بگیریم و اصلاح کنیم.

    $curl_err = curl_error($curl);

    if ($curl_err) {
        return rest_ensure_response('some things happend ! try again ');
    }

    unset($curl);
}









<?php
require 'add_order_to_user.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5" dir="rtl">
        <div class="row">
            <div class="col-sm-4 col-md-4 mx-auto">
                <?php
                if (!empty($error)) {
                    echo '
               <div class="alert alert-danger" dir="rtl">
                    <strong>خطا  :</strong>' . esc_attr($error) . '
                </div>
            ';
                }
                if ($success === 'success') {
                    echo '<div class="alert alert-success" role="alert">
                  سفارش شما با موفقیت ثبت شد تبریک کد پیگیری شما :' . random_int(100, 200) . '
            </div>
        ';
                } else {
                ?>

                <div class="alert alert-info " role="alert">
                    لطفا اطلاعات خواسته شده را با دقت وارد کنید
                </div>
                <form method="POST">
                    <?php echo wp_nonce_field('add_order_action', 'add_order') ?>
                    <div class="mb-3 mt-3 " dir="rtl">
                        <label dir="rtl" for="name" class="form-label">نام و نام خانوادگی</label>
                        <input dir="rtl" type="text" value="<?php echo $name ?>" class="form-control"
                               placeholder="نام و نام خانوادگی را وارد کنید" name="name" dir="rtl">
                    </div>
                    <div class="mb-3" >
                        <label for="phone" class="form-label">شماره همراه</label>
                        <input type="text" value="<?php echo $phone ?>" class="form-control" id="phone" dir="rtl"
                               placeholder="شماره همراه را وارد کنید" name="phone"  >
                    </div>
                    <button type="submit" name="button" class="btn btn-primary">ارسال</button>
                </form>
            </div>
        </div>
        <?php
    }
    ?>
</div>
</body>
</html>
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
                سفارش شما با موفقیت ثبت شد تبریک
            </div>
        ';
    } else {
        ?>
        <div class="row">

            <div class="alert alert-info col-sm-4 col-md-4" role="alert">
                لطفا اطلاعات خواسته شده را با دقت وارد کنید
            </div>
            <form method="POST">
                <?php echo wp_nonce_field('add_order_action', 'add_order') ?>
                <div class="mb-3 mt-3  col-sm-4 col-md-4" dir="rtl">
                    <label dir="rtl" for="name" class="form-label">نام کاربری</label>
                    <input dir="rtl" type="text" value="<?php echo $name ?>" class="form-control"
                           placeholder="نام کاربری را وارد کنید" name="name">
                </div>
                <div class="mb-3  col-sm-4 col-md-4" dir="rtl">
                    <label for="phone" class="form-label">شماره همراه</label>
                    <input type="text" value="<?php echo $phone ?>" class="form-control" id="phone"
                           placeholder="شماره همراه را وارد کنید" name="phone">
                </div>
                <button type="submit" name="button" class="btn btn-primary">ارسال</button>
            </form>
        </div>
        <?php
    }
    ?>
</div>
</body>

</html>
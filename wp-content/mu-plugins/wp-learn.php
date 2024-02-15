<?php

add_filter('template_include', 'my_function');

function my_function()
{

}

?>

<form action="" method="post">
    <input type="hidden" value="<?php echo ' wp_create_nonce()' ?>"/>
    <input name="name" type="text"/>
    <input name="phone" type="number"/>
    <button type="submit">send</button>
</form>


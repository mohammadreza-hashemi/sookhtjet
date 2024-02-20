<?php

add_filter('template_include', 'show_page_login');
function show_page_login($page_form)
{
    global $wp;
    if ($wp->request === 'form') {
        include 'my_first_plugin/add_order_to_user_view.php';
    } else {
        return $page_form;
    }
}

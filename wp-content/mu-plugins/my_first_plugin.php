<?php

add_filter('template_include', 'show_page_login');
function show_page_login($page_form)
{
    global $wp;

    if ($wp->request === 'form') {
        include 'my_first_plugin/views/login-form.php';
    } else {
        return $page_form;
    }

}


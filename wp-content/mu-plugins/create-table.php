<?php
add_action('init', 'add_table_to_db');
function add_table_to_db()
{
//    global $wpdb;
//    $table_name=$wpdb->prefix.'custom_table';
//    $charset_collate = $wpdb->get_charset_collate();
//    $sql = "CREATE TABLE $table_name (
//        id mediumint(9) NOT NULL AUTO_INCREMENT,
//        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
//        name tinytext NOT NULL,
//        text text NOT NULL,
//        url varchar(55) DEFAULT '' NOT NULL,
//        PRIMARY KEY  (id)
//    ) $charset_collate;";
//
//    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
//    dbDelta($sql);
}


function send_order_data_post_request()
{

}

add_action('wp_ajax_learn_fetch_posts', 'wp_learn_ajax_fetch_post');

function wp_learn_ajax_fetch_post(){
//    $posts=get_users();
//    wp_send_json($posts);

    $data = wp_remote_post('https://localhost/woordpress/wp-json/wp/v2/posts', array(
        'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
        'body'        => json_encode(array(
            'name'=>'hashan',
            'last_name'=>'hashemi'
        )),
//        'method'      => 'GET',
//        'data_format' => 'body',
    ));

    echo var_dump($data);

    die()
;
}

do_action('wp_ajax_learn_fetch_posts');


<?php

/**
 * @package Hello_Dolly
 * @version 1.7.2
 */
/*

Plugin Name: Ttw Short URL
Plugin URI: https://dalathub.com
Description: Shorten than long URL
Author: Tinh Phan
Version: 1.0.0
Author URI: https://dalathub.com
*/

require_once(__DIR__ . '/includes/ttw_database.php');

if (is_admin()) {
    require_once __DIR__ . '/admin/main.php';
}else{
    require_once __DIR__ . '/public/main.php';
}


register_activation_hook(__FILE__, 'ttw_register_db_table');

function ttw_register_db_table()
{
    error_log(__METHOD__);
    global $table_prefix, $wpdb;
    $wp_track_table = $table_prefix. 'ttw_link';
    $charset_collate = $wpdb->get_charset_collate();
    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
    {
        $sql = " CREATE TABLE $wp_track_table (
            ID mediumint(9) NOT NULL AUTO_INCREMENT,
            token text NOT NULL,
            destination text NOT NULL,
            user_id mediumint(9),
            visitedcount mediumint(9),
            visitedmax mediumint(9),
            PRIMARY KEY (ID)
        ) $charset_collate;";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}

add_action('wp_ajax_nopriv_ttw_make_url', 'ttw_make_url');
add_action('wp_ajax_ttw_make_url', 'ttw_make_url');

function ttw_make_url()
{
    error_log(__METHOD__);
    $uID = get_current_user_id();
    $link = $_REQUEST['des'];
    $parse = parse_url($link);
    if(!isset($parse['scheme'])){
        $link = 'http://'.$link;
    }
    $db = new DatabaseHelper();
    $token = $db->create($link, $uID);
    $final = get_site_url().'/' . '?ttw='. $token;
    $response = array(
        'url' => $final,
    );

    error_log(print_r($response,true));
    wp_send_json($response);
    exit;
}


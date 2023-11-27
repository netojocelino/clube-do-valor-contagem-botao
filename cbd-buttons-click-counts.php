<?php
/**
 * Plugin Name: Buttons Click Counts
 * Plugin URI:  https://github.com/netojocelino/cbs-buttons-clicks-counts
 * Version:     1.0
 * Description: Count the clicks from a button with shortcode.
 * Author:      Jocelino Neto
 * Author URI:  https://linkedin.com/in/netojocelino/
 * License:     GPL v2 or later
 */


define('BCC_ROOTDIR', plugin_dir_path(__FILE__));

function bcc_init_db ()
{
    if (get_option('bcc_db_created')) return;

    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'cbs_buttons_clicks_counts';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(512) NOT NULL,
        metadata text NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,

        PRIMARY KEY  (id)
        ) $charset_collate;";
    dbDelta( $sql );

    update_option('bcc_db_created', true);
}

function bcc_uninstall ()
{
    update_option('bcc_db_created', false);
}

function add_button_click_count ()
{
    include BCC_ROOTDIR . '/views/button-html.php';

    return ob_get_clean();
}

register_activation_hook(__FILE__, 'bcc_init_db');
register_deactivation_hook(__FILE__, 'bcc_uninstall');

add_shortcode('count_button', 'add_button_click_count');

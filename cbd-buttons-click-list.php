<?php
/**
 * Plugin Name: Buttons Click List
 * Plugin URI:  https://github.com/netojocelino/cbs-buttons-clicks-counts
 * Version:     1.0
 * Description: List the clicks from a button with shortcode (depends on Buttons Click Counts and WP-cli).
 * Author:      Jocelino Neto
 * Author URI:  https://linkedin.com/in/netojocelino/
 * License:     GPL v2 or later
 */

function cbd_init_plugin () 
{
    bcc_list_init_db();
}

function bcc_list_init_db ()
{
    if (!get_option('bcc_db_created')) {
        throw new \Exception('É necessário possuir o plugin `Buttons Click Counts` intalado e habilitado.');
    }

    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'cbs_buttons_clicks_counts';

    $sql = "SELECT count(*) AS total
        FROM information_schema.tables
        WHERE table_schema = '{$wpdb->dbname}'
        AND table_name = '{$table_name}'";
    ob_start();
    $rows = $wpdb->get_results($sql);

    if (!count($rows) || !$rows[0]->total)
    {
        throw new \Exception('Tabela de Contagem é necessária, instale o plugin `Buttons Click Counts`');
    }
}
register_activation_hook(__FILE__, 'cbd_init_plugin');

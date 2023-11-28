<?php
/**
 * Plugin Name: Buttons Click List
 * Plugin URI:  https://github.com/netojocelino/clube-do-valor-contagem-botao
 * Text Domain: cdv-buttons-click-list
 * Version:     1.0
 * Description: List the clicks from a button with shortcode (depends on Buttons Click Counts and WP-cli).
 * Author:      Jocelino Neto
 * Author URI:  https://linkedin.com/in/netojocelino/
 * License:     GPL v2 or later
 */

function cdv_init_plugin () 
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
    $table_name = $wpdb->prefix . 'cdv_buttons_clicks_counts';

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

function bcc_get_grouped_rows ()
{
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'cdv_buttons_clicks_counts';

    $sql = "SELECT *, count(*) AS total
        FROM {$table_name}
        GROUP BY name
        ORDER BY total DESC";

    return $wpdb->get_results($sql);
}


function bcc_find_rows_by_name (string $name)
{
    $name = preg_replace('[^A-Za-z0-9]', '-', $name);

    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'cdv_buttons_clicks_counts';

    $sql = "SELECT base_table.*, (
            SELECT COUNT(*) FROM {$table_name} WHERE name = '{$name}' and id <= base_table.id
        ) AS position
        FROM {$table_name} AS base_table
        WHERE name = '{$name}' ORDER BY created_at DESC";

    return $wpdb->get_results($sql);
}

function list_clicks_menu ()
{

    function list_clicks_list_index ()
    {
        require_once(BCC_ROOTDIR . 'views/list.php');
    }

    function list_clicks_list_view ()
    {
        require_once(BCC_ROOTDIR . 'views/details.php');
    }

    add_menu_page('Contabilizador de Links',
        'Contador de Cliques',
        'manage_options',
        'list_clicks_list_index',
        'list_clicks_list_index',
    );

    add_submenu_page('list_clicks_list_index',
        'Detalhes',
        null,
        'manage_options', //capability
        'list_clicks_list_view', //menu slug
        'list_clicks_list_view' //function
    );

    function hidden_submenus () {
        remove_submenu_page( 'list_clicks_list_index', 'list_clicks_list_view' );
    }

    add_filter('submenu_file', 'hidden_submenus');

}

if (defined('WP_CLI') && WP_CLI):
function cdv_wp_list_all()
{
    global $wpdb;
    $table = $wpdb->prefix . 'cdv_buttons_clicks_counts';
    $rows = $wpdb->get_results(
        "SELECT id as click_number, created_at, name FROM $table ORDER BY id DESC LIMIT 15"
    );

    WP_CLI::line(sprintf(
        "%7s | %-22s | %-10s",
        'Cliques',
        'Data e Hora',
        'Categoria'
    ));

    foreach ($rows as $row) {
        $date = date_create($row->created_at);
        $date = date_format($date, 'd\/m\/Y \à\s H:i:s');

        WP_CLI::line(
            sprintf(
                "%7s | %22s | %10s",
                $row->click_number,
                $date,
                $row->name
            )
        );
    }
}
WP_CLI::add_command('buttons-click list-all', 'cdv_wp_list_all');
endif;

register_activation_hook(__FILE__, 'cdv_init_plugin');
add_action('admin_menu','list_clicks_menu');

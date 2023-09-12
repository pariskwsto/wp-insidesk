<?php
/*
Plugin Name:  WP Insidesk
Plugin URI:   https://github.com/pariskwsto/wp-insidesk
Description:  A wordpress plugin that displays information about the website and the server.
Author:       Paris Kostopoulos <pariskwsto@gmail.com>
Author URI:   https://pariskwsto.com
License:      MIT
License URI:  https://github.com/pariskwsto/wp-insidesk/blob/main/LICENSE
Text Domain:  wp_insidesk
Version:      1.0.0
*/

class Insidesk
{
    private static $instance;
    public static $_versionWoo = 0;

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->pluginDefines();
        self::init();
    }

    protected function pluginDefines()
    {
        define( 'WP_INSIDESK_VERSION', '1.0.0' );
        define( 'WP_INSIDESK_REQUIRED_WP_VERSION', '6.3.1' );
        define( 'WP_INSIDESK_TEXT_DOMAIN', 'wp_insidesk' );
        define( 'WP_INSIDESK_PLUGIN', __FILE__ );
        define( 'WP_INSIDESK_PLUGIN_BASENAME', plugin_basename( WP_INSIDESK_PLUGIN ) );
        define( 'WP_INSIDESK_PLUGIN_NAME', trim( dirname( WP_INSIDESK_PLUGIN_BASENAME ), '/' ) );
        define( 'WP_INSIDESK_PLUGIN_DIR', untrailingslashit( dirname( WP_INSIDESK_PLUGIN ) ) );
        define( 'WP_INSIDESK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    }

    protected function init()
    {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if (is_plugin_active( 'woocommerce/woocommerce.php')) {
            $pathPluginWoo = str_replace( 'wp-insidesk/', 'woocommerce/woocommerce.php', WP_INSIDESK_PLUGIN_DIR );
            $pathPluginWoo = get_plugin_data( $pathPluginWoo, $markup = true, $translate = true );
            Insidesk::$_versionWoo = (float)$pathPluginWoo['Version'];
        }
        
        include_once( 'inc/helpers/helpers.php' );

        add_action('admin_notices', array($this, 'showWpInsideskAdminNotice'));
    }

    public function showWpInsideskAdminNotice()
    {
        $current_user = wp_get_current_user();
        if ($current_user->user_login != 'insidesk') {
            return;
        }

        global $wpdb;
        date_default_timezone_set('Europe/Athens');
        $current_datetime = date("Y-m-d H:i:sA");
        $woocommerce_version = (Insidesk::$_versionWoo != 0) ? 'v'.Insidesk::$_versionWoo : "No woocommerce found.";
        $db_name = $wpdb->dbname;
        ?>
        <div class="notice">
            <p>
                <?php echo '
                    <p>
                        <b>Wordpress</b>: v'.get_bloginfo('version').' - 
                        <b>Woocommerce</b>: '.$woocommerce_version.'<br/>
                        <b>Database</b>: '.$db_name.'<br/>
                        <b>Server IP Address</b>: '.Helpers::getServerIpAdderss().' - 
                        <b>Server Time</b>: '.$current_datetime.'
                    </p>
                ';
                ?>
            </p>
        </div>
        <?php
    }
}

$Insidesk = Insidesk::instance();
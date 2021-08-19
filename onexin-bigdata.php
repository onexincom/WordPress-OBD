<?php
/**
 * Plugin Name: ONEXIN BigData
 * Description: ONEXIN BigData (referred to as OBD), open automatically batch cloud collection, analysis of massive Internet information for you, to capture, management, processing, and finishing as the help website more product useful information within a reasonable time. Powered by ONEXIN.
 * Plugin URI: https://github.com/onexincom/WordPress-OBD
 * Author: Onexin Inc.
 * Author URI: https://www.onexin.com
 * Version: 1.11.0
 * License: GPLv2 or later
 * Requires at least: 4.9
 * Requires PHP: 5.6
 *
 * @package onexin-bigdata
 */

// add "Settings" link to the plugin action page
add_filter( 'plugin_action_links', 'onexinBigDataPluginSettings', 10, 2);
add_action('admin_menu', 'onexinBigDataAddOpc');
// add "Doing" code to the website page
add_action('wp_footer', 'onexinBigDataAddDoing', 99);
register_activation_hook(__FILE__, 'onexinBigDataInstall');
//register_deactivation_hook (__FILE__, 'onexinBigDataUnInstall');


//----------------------TODO for api page-----------------------------------------------------
 
function onexinBigDataApi(){	
	// /api.php
	if(preg_match("/\/onexin-bigdata\/api\.php/", $_SERVER['REQUEST_URI'], $match)) {
		
		include plugin_dir_path( __FILE__ ) . 'api.php';
		exit();
	}
	
}
add_action( 'init', 'onexinBigDataApi');

//----------------------TODO for dashboard-----------------------------------------------------
function onexinBigDataAddOpc(){		
	if (current_user_can('manage_options')){
		load_plugin_textdomain( 'onexin-bigdata', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	
		add_options_page(__( 'OBD BigData', 'onexin-bigdata' ), __( 'OBD BigData', 'onexin-bigdata' ), 8, basename(__FILE__), 'onexinBigDataMenu');
	}
}
function onexinBigDataInstall(){ 
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

	$charset_collate = "DEFAULT CHARACTER SET ".( !empty($wpdb->charset) ? $wpdb->charset : "utf8");
	$charset_collate .= " COLLATE ".( !empty($wpdb->collate) ? $wpdb->collate : "utf8_general_ci");
			
	$my_sql = " CREATE TABLE `%s`(
			`bid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(255) DEFAULT NULL,
			`url` text NOT NULL,
			`k` varchar(32) NOT NULL DEFAULT '',
			`catid` varchar(20) NOT NULL DEFAULT '',
			`i` varchar(32) NOT NULL DEFAULT '',
			`resid` varchar(20) NOT NULL DEFAULT '',
			`dateline` int(10) unsigned NOT NULL DEFAULT '0',
			`cronpublishdate` int(10) unsigned NOT NULL DEFAULT '0',
			`ip` varchar(20) NOT NULL DEFAULT '',
			`status` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`bid`)
		) $charset_collate;";
		
	$sql = sprintf($my_sql, $wpdb->prefix . "plugin_onexin_bigdata");
	$wpdb->query($sql);
} 
function onexinBigDataUnInstall(){ 
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
	
	$table_name = $wpdb->prefix . "plugin_onexin_bigdata";
	$sql = "DROP TABLE $table_name;";
	$wpdb->query($sql);
	
	delete_option('onexin_bigdata_options');
}
function onexinBigDataCheckSql(){
	global $wpdb;
	$table_name = $wpdb->prefix . "plugin_onexin_bigdata";
	return ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != "") ? true : false;
}
function onexinBigDataPluginSettings( $links, $file ) {
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
	     $settings_link = '<a href="options-general.php?page='.end(explode('/', $this_plugin)).'&op=settings">' . __('Settings') . '</a>';
	     array_unshift( $links, $settings_link );
    }
	return $links;
}
function onexinBigDataVersion(){
	
	return ;
}
function onexinBigDataMenu(){
	$new_version = onexinBigDataVersion();
	//if ($new_version) echo '<div id="message" class="updated fade"><b><br/>There is a new version, ' . $new_version . '!<br/></b></div>';
	require_once __DIR__ .'/onexin_bigdata.inc.php';	
	return;
}


//----------------------TODO for website-----------------------------------------------------
function onexinBigDataAddDoing(){
	global $wpdb;
	
	$options = get_option( 'onexin_bigdata_options' );
//	if(!$options['isopen'] || $options['perpv']<=0) return '';
//	$h = gmdate('H', $_G['timestamp'] + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
//	if($options['worktime'] && in_array($h, explode('|', $options['worktime']))) return '';
//	if(mt_rand(1, $options['perpv'])!=1) return '';
	
	require_once __DIR__ .'/tpl/doing.tpl.php';
	return;
}

<?php

/**
 * ONEXIN BIG DATA For Wordpress 4.0+
 * ============================================================================
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @package    onexin_bigdata
 * @module     config
 * @date       2021-06-07
 * @author     King
 * @copyright  Copyright (c) 2021 Onexin Platform Inc. (http://www.onexin.com)
 */

/*
//--------------Tall us what you think!----------------------------------

load.wordpress.php

*/

//----------------Initialization---------------------------------

if (!defined('ABSPATH')) {
    exit('ABSPATH Denied');
}
if (!defined('OBD_CONTENT')) {
    define('OBD_CONTENT', true);
    define('OBD_CONTENT_DIR', __DIR__);
}
if (!defined('OBD_CHARSET')) {
    define('OBD_CHARSET', 'utf-8');
}
include_once OBD_CONTENT_DIR . '/load.config.php';
include_once OBD_CONTENT_DIR . '/onexin_bigdata.function.php';

//----------------FUNCTION FOR YOUR PHP---------------------------------

function onexin_bigdata_iplink($str)
{

    $s = explode('|', $str);

    $url = '';
    switch ($s[0]) {
        case 'wordpress':
            // wordpress|123
            $url = "../?p=" . $s[1];
            break;
        default:
            // other|123
            return '';
        break;
    }

    return $url;
}

//----------------DATEBASE FOR WORDPRESS---------------------------------

if (!class_exists('OBD')) {
    class OBD
    {
        
        public static function table($table)
        {
            global $wpdb;
            return $wpdb->prefix . $table;
        }

        public static function query($query)
        {
            global $wpdb;
            return $wpdb->query($query);
        }

        public static function update($table, $data_array, $where_clause)
        {
            global $wpdb;
            $wpdb->update(self::table($table), $data_array, $where_clause);
        }

        public static function insert($table, $data_array)
        {
            global $wpdb;
            $wpdb->insert(self::table($table), $data_array);
        }

        public static function fetch_all($query, $type = ARRAY_A)
        {
            global $wpdb;
            return $wpdb->get_results($query, $type);
        }

        public static function fetch_first($query, $type = ARRAY_A)
        {
            global $wpdb;
            return $wpdb->get_row($query, $type);
        }

        public static function result_first($sql)
        {
            global $wpdb;
            return $wpdb->get_var($sql);
        }

        public static function escape($str)
        {
            global $wpdb;
            return $wpdb->escape($str);
        }
    }

}

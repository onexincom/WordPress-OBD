<?php

/**
 * ONEXIN BIG DATA For Wordpress 4.0+
 * ============================================================================
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @package    onexin_bigdata
 * @module     api
 * @date       2016-11-26
 * @author     King
 * @copyright  Copyright (c) 2016 Onexin Platform Inc. (http://www.onexin.com)
 */

/*
//--------------Tall us what you think!----------------------------------
*/

// DEBUG
//onexin_bigdata_output("300");

// DOING
if (!empty($_GET['oid']) && defined('ABSPATH')) {
    $url = "http://we.onexin.com/apiocc.php?oid=" . intval($_GET['oid']) . "&_=" . time();
    $args = array('timeout' => 1);
    wp_remote_get($url, $args);
    exit;
}

if (!defined('OBD_CONTENT')) {
    include_once __DIR__ . '/load.wordpress.php';
}

//------------------------------------------------------------------------
//header("Content-type: text/html; charset=utf-8");
include_once OBD_CONTENT_DIR . '/onexin_bigdata.function.php';
$options = get_option('onexin_bigdata_options');
$_OBD = array_merge((array)$_OBD, (array)$options);

// for all
$_POST = onexin_bigdata_stripslashes($_POST);

$_GET = onexin_bigdata_charset($_GET);
$_POST = onexin_bigdata_charset($_POST);

// Sanitize A-Za-z0-9
if (!empty($_POST['k'])) {
    $_k = sanitize_key($_POST['k']);
}
if (!empty($_POST['import'])) {
    $_import = sanitize_key($_POST['import']);
}

// CHECK TOKEN
$k = sanitize_key($_GET['occhash']);
$t = sanitize_key($_GET['occtime']);
if ($k != md5(md5($_OBD['token']) . $t) || empty($_OBD['token']) || empty($t)) {
    $contentStr = empty($t) ? "Error signature" : "Invalid signature";
    onexin_bigdata_output("100", $contentStr);
}

//-----------------------------URL------------------------------------------

// save url
if (empty($_POST['bigdata'])) {
    if (empty($_POST['urls'])) {
        $wheresql = "`status` = '0'";
        if (!empty($_GET['rids'])) {
            $rids = onexin_bigdata_implode(explode(',', onexin_bigdata_sanitize($_GET['rids'])));
            $wheresql .= " AND `resid` IN ($rids)";
        }
        $urls = OBD::fetch_all("SELECT url,k,catid,i,resid FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE $wheresql ORDER BY bid ASC LIMIT 0,10");
        $urls = onexin_bigdata_charset($urls, true);
        if (!empty($urls)) {
            onexin_bigdata_output("200", $urls);
        } else {
            echo json_encode(array("status" => "400"));
        }
        exit;
    }
    
    // check json
    $_POST['urls'] = onexin_bigdata_sanitize($_POST['urls']);
    $_POST['urls'] = json_decode($_POST['urls'], true);
    if (!is_array($_POST['urls'])) {
        onexin_bigdata_output("100", "Unknow json");
    }
    
    // search ks
    $_POST['ks'] = array_keys($_POST['urls']);
    $ids = onexin_bigdata_implode($_POST['ks']);
    $urls = OBD::fetch_all("SELECT k FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE k IN ($ids)");
    foreach ($urls as $value) {
        unset($_POST['urls'][$value['k']]);
    }

    // insert data
    $timestamp = time();
    $urls = $inserts = array();
    foreach ($_POST['urls'] as $key => $val) {
        if (!empty($val['url'])) {
            $urls[] = " (
                            '" . OBD::escape(sanitize_text_field($val['name'])) . "', 
                            '" . OBD::escape(sanitize_url($val['url'])) . "', 
                            '" . OBD::escape(sanitize_key($val['k'])) . "', 
                            '" . OBD::escape(sanitize_key($val['resid'])) . "', 
                            '$timestamp', 
                            '" . OBD::escape(sanitize_text_field(!empty($val['catid']) ? $val['catid'] : $_POST['catid'])) . "', 
                            '" . OBD::escape(sanitize_key($_import)) . "'
                        )";
        }
    }

    if (!empty($urls)) {
        krsort($urls);
        OBD::query("INSERT INTO " . OBD::table('plugin_onexin_bigdata') . " (`name`, `url`, `k`, `resid`, `dateline`, `catid`, `i`) VALUES " . implode(',', $urls) . ";");
    }
    echo json_encode(array("status" => "300"));
    exit;
}

//-----------------------------POST------------------------------------------
// never post
if (!empty($_POST['k'])) {
    
    // Sanitize
    $_title = sanitize_text_field($_POST['title']);
    $_content = wp_kses($_POST['content'], 1);
    $_catid = sanitize_text_field($_POST['catid']);
    $_tags = sanitize_text_field($_POST['tags']);
    $_occurl = esc_url_raw($_POST['occurl']);
    $_occsite = sanitize_text_field($_POST['occsite']);
    
    // check empty
    if (empty($_title) || empty($_content) || esc_url_raw($_occurl) == 'http://') {
        OBD::update('plugin_onexin_bigdata', array('status' => 3), array('k' => $_k));
        echo json_encode(array("status" => "500"));
        exit;
    }

        OBD::query("UPDATE " . OBD::table('plugin_onexin_bigdata') . " SET status = '2', `name` = '" . OBD::escape($_title) . "' WHERE `k` = '$_k'");

        // check url or subject
        $count = OBD::result_first("SELECT COUNT(*) FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE `name` = '" . OBD::escape($_title) . "'");
    if ($count > 1) {
        OBD::query("UPDATE " . OBD::table('plugin_onexin_bigdata') . " SET status = '3' WHERE `k` = '$_k'");
        echo json_encode(array("status" => "500"));
        exit;
    }

    // filter
    if ($_OBD['filter_title']) {
        $_title = onexin_bigdata_filter($_title, $_OBD['filter_title']);
    }

    // fix title
    if ($_OBD['title_prefix']) {
        $_title = onexin_bigdata_randone($_OBD['title_prefix']) . $_title;
    }
    if ($_OBD['title_suffix']) {
        $_title = $_title . onexin_bigdata_randone($_OBD['title_suffix']);
    }

    // cronpublishdate
}

// publish
if (!empty($_import)) {
    $yourself_file = OBD_CONTENT_DIR . '/soeasy/publish.' . sanitize_key($_import) . '.php';
    if (file_exists($yourself_file)) {
        include_once $yourself_file;
    }
}
exit;

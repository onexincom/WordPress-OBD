<?php

/**
 * ONEXIN BIG DATA For Wordpress 4.0+
 * ============================================================================
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @package    onexin_bigdata
 * @module     onexin_bigdata
 * @date       2016-11-26
 * @author     King
 * @copyright  Copyright (c) 2016 Onexin Platform Inc. (http://www.onexin.com)
 */

/*
//--------------Tall us what you think!----------------------------------
*/
if (!defined('OBD_CONTENT')) {
    include_once __DIR__ . '/load.wordpress.php';
}

$_GET = onexin_bigdata_charset($_GET);
$_POST = onexin_bigdata_charset($_POST);
$timestamp = time();
// page $baseurl
$baseurl = esc_html('?page=onexin-bigdata.php');
// page $bid
$bid = !empty($_GET['bid']) ? sanitize_key($_GET['bid']) : 0;
// page $op
$op = isset($_GET['op']) ? sanitize_key($_GET['op']) : "";
//-------------------------------------------------------------------------

// check admin
$user_id = get_current_user_id();
if ($user_id != '1') {
    $op = 'readme';
}

if ($op == 'settings') {
    ### Form Processing
    if (onexin_bigdata_submitcheck('optionssubmit')) {
        // save settings
        $options = array(
              'isopen'             => '1'
            , 'isdelimiter'        => '1'
            , 'title_prefix'       => sanitize_text_field($_POST['title_prefix'])
            , 'title_suffix'       => sanitize_text_field($_POST['title_suffix'])
            , 'filter_title'       => sanitize_textarea_field($_POST['filter_title'])
            , 'filter_content'     => sanitize_textarea_field($_POST['filter_content'])
            , 'worktime'           => '23|00|01|02|03|04|05|06|07'
            , 'ignore'             => '1'
            , 'perpv'              => '1'
        );

        if (isset($_POST['isopen'])) {
            $options['isopen'] = (int)$_POST['isopen'];
        }
        if (isset($_POST['from_style2'])) {
            $options['from_style2'] = wp_kses($_POST['from_style2'], 1);
        }
        if (isset($_POST['portal_users'])) {
            $options['portal_users'] = sanitize_textarea_field($_POST['portal_users']);
        }
        if (isset($_POST['origviews'])) {
            $options['origviews'] = sanitize_text_field($_POST['origviews']);
        }
        if (isset($_POST['worktime'])) {
            $options['worktime'] = sanitize_text_field($_POST['worktime']);
        }

        if (isset($_POST['perpv'])) {
            $options['perpv'] = (int)$_POST['perpv'];
        }
        if (isset($_POST['oid'])) {
            $options['oid'] = (int)$_POST['oid'];
        }
        if (isset($_POST['token'])) {
            $options['token'] = sanitize_text_field($_POST['token']);
        }

        $update_views_queries = array();
        $update_views_text = array();
        update_option('onexin_bigdata_options', $options);

        echo '<div class="updated fade"><p>' . __('Updated')  . '</p></div>';
    } else {
        // get settings
        $options = get_option('onexin_bigdata_options');
        $options = array_merge((array)$_OBD, (array)$options);
    }

    include onexin_bigdata_template('onexin_bigdata:settings');
    exit();
} elseif ($op == 'manage') {
    if (onexin_bigdata_submitcheck('managesubmit')) {

        $url = !empty($_POST['url']) ? OBD::escape(sanitize_text_field($_POST['url'])) : '';
        $name = !empty($_POST['name']) ? OBD::escape(sanitize_text_field($_POST['name'])) : '';
        $catid = !empty($_POST['catid']) ? OBD::escape(sanitize_text_field($_POST['catid'])) : 0;
        $i = !empty($_POST['i']) ? OBD::escape(sanitize_text_field($_POST['i'])) : '';
        $status = !empty($_POST['status']) ? intval($_POST['status']) : 0;

        $url = str_replace('&amp;', '&', $url);

        // check bid
        if ($bid > 0) {
            OBD::query("UPDATE " . OBD::table('plugin_onexin_bigdata') . " SET name = '$name', url = '$url', catid = '$catid', i = '$i', status = '$status' WHERE bid='$bid'");
        } else {
            $dateline = $timestamp;
            $k = md5($url);

            // <bookmark add
            $arr = explode("\n", trim($url));
            $data = array();
            foreach ($arr as $val) {
                $val = trim($val);
                if (preg_match("/^http/", $val)) {
                    $v = explode('###', $val);
                    $ks = md5($v[0]);
                    $data[$ks]['name'] = OBD::escape($v[1]);
                    $data[$ks]['url'] = OBD::escape($v[0]);
                    $data[$ks]['k'] = $ks;
                }
            }

            $ids = onexin_bigdata_implode(array_keys($data));
            $query = OBD::fetch_all("SELECT k FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE k IN ($ids)");
            foreach ($query as $value) {
                unset($data[$value['k']]);
            }

            $urls = array();
            foreach ($data as $key => $val) {
                if (!empty($val['url'])) {
                    $urls[] = "('$val[name]', '$val[url]', '$val[k]', '$status', '$dateline', '$catid', '$i')";
                }
            }
            if (!empty($urls)) {
                OBD::query("INSERT INTO " . OBD::table('plugin_onexin_bigdata') . " (`name`, `url`, `k`, `status`, `dateline`, `catid`, `i`) VALUES " . implode(',', $urls) . ";");
            }
            // bookmark>
        }

        onexin_bigdata_output("200");
    } else {
        $res = OBD::fetch_first("SELECT * FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE bid='$bid'");
        // esc_html for /tpl/manage.tpl.php
        $res = (array) onexin_bigdata_htmlspecialchars($res);
        $res['url'] = str_replace('&amp;', '&', $res['url']);
    }

    include onexin_bigdata_template('onexin_bigdata:manage');
    exit();
} elseif ($op == 'readme') {
    include onexin_bigdata_template('onexin_bigdata:readme');
} elseif ($op == 'delete') {
    // delete
    OBD::query("DELETE FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE bid='$bid'");

    onexin_bigdata_output("200");
    exit();
} elseif ($op == 'bigdata') {
    $bids = !empty($_POST['bidarray']) ? onexin_bigdata_implode($_POST['bidarray']) : "'0'";

    if (onexin_bigdata_submitcheck('bigdatasubmit')) {
        // deletes
        OBD::query("DELETE FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE bid IN ($bids)");
    } elseif (onexin_bigdata_submitcheck('bigdatasubmit3')) {
        OBD::query("UPDATE " . OBD::table('plugin_onexin_bigdata') . " SET status = '3' WHERE bid IN ($bids)");
    } elseif (onexin_bigdata_submitcheck('bigdatasubmit0')) {
        OBD::query("UPDATE " . OBD::table('plugin_onexin_bigdata') . " SET status = '0' WHERE bid IN ($bids)");
    }

    onexin_bigdata_output("200");
    exit();
} else {
    // esc & sanitize
    $_status = OBD::escape(sanitize_key($_GET['status']));
    $_name = OBD::escape(sanitize_text_field($_GET['name']));
    $_resid = OBD::escape(sanitize_key($_GET['resid']));

    // stats page
    $daynum = !empty($_OBD['daynum']) ? $_OBD['daynum'] : 365;
    $wheresql = '1=1 ';
    $starttime  = !empty($_GET['starttime']) ? strtotime($_GET['starttime']) : $timestamp - $daynum * 24 * 3600;
    $endtime    = !empty($_GET['endtime']) ? strtotime($_GET['endtime']) : $timestamp;
    $wheresql .= ($bid > 0) ? " AND bid='$bid'" : '';
    $wheresql .= ($_status !== '') ? " AND status = '{$_status}'" : '';
    $wheresql .= (!empty($_name)) ? " AND (name like '%{$_name}%' OR url like '%{$_name}%' OR ip like '%{$_name}%')" : '';
    $wheresql .= (!empty($_resid)) ? " AND resid = '{$_resid}'" : '';
    $wheresql .= ($_status != '0') ? " AND dateline>='$starttime' AND dateline<'$endtime'" : "";
    
    // page
    $page = empty($_GET['paged']) ? 0 : intval($_GET['paged']);
    if ($page < 1) {
        $page = 1;
    }

    $perpage = empty($_GET['perpage']) ? 30 : intval($_GET['perpage']);
    $start = ($page - 1) * $perpage;

    // list
    $list = array();
    $multi = "";
    $count = OBD::result_first("SELECT COUNT(*) FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE $wheresql");
    if ($count) {
        $result = OBD::fetch_all("SELECT * FROM " . OBD::table('plugin_onexin_bigdata') . " WHERE $wheresql ORDER BY bid DESC LIMIT $start, $perpage");
        foreach ($result as $value) {
            $value['link'] = ($value['status'] == '1') ? onexin_bigdata_iplink($value['ip']) : "";
            $list[] = $value;
        }
        $list = onexin_bigdata_htmlspecialchars($list);
        $multi = onexin_bigdata_multi($count, $perpage, $page, $baseurl
            . (($_name) ? "&name=" . urlencode($_name) : "")
            . (($_status) ? "&status=" . intval($_status) : "")
            . (($bid > 0) ? "&bid=$bid" : "")
            . (($starttime) ? "&starttime=" . date('Y-m-d H:i', $starttime) : "")
            . (($endtime) ? "&endtime=" . date('Y-m-d H:i', $endtime) : ""));
    }

    include onexin_bigdata_template('onexin_bigdata:stats');
    $options = get_option('onexin_bigdata_options');
    include onexin_bigdata_template('onexin_bigdata:doing');
}

//------------------------------------------------------------------------------------------
// obd pagination
function onexin_bigdata_multi($num, $perpage, $curpage, $mpurl)
{

    global $_G;

    $totalpages = @ceil($num / $perpage);

    $mpurl .= strpos($mpurl, '?') !== false ? '&amp;' : '?';

    $paginate_links = '<div class="tablenav-pages">
<span class="displaying-num">' . $num . ' items</span>
<span class="pagination-links"><a class="first-page button disabled" title="Go to the first page" href="' . $mpurl . 'paged=1">«</a>
<a class="prev-page button disabled" title="Go to the previous page" href="' . $mpurl . 'paged=' . ($curpage > 2 ? $curpage - 1 : 1) . '">‹</a>
<span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Select Page</label>
<input class="current-page" title="Current page" type="text" name="paged" value="' . $curpage . '" size="2" onkeydown="if(event.keyCode==13) {window.location=\'' . $mpurl . 'paged=\'+this.value;}" /> / <span class="total-pages">' . $totalpages . '</span></span>';
    if ($curpage + 1 <= $totalpages) {
        $paginate_links .= ' <a class="next-page button" title="Go to the next page" href="' . $mpurl . 'paged=' . ($curpage + 1) . '">›</a>
<a class="last-page button" title="Go to the last page" href="' . $mpurl . 'paged=' . $totalpages . '">»</a></span>';
    }
    $paginate_links .= '</div><!--// end .pagination -->';
    return $paginate_links;
}

// obd get tpl
function onexin_bigdata_template($str)
{

    return OBD_CONTENT_DIR . '/tpl/' . str_replace('onexin_bigdata:', '', $str) . '.tpl.php';
}

// obd submit check
function onexin_bigdata_submitcheck($str)
{

    return isset($_REQUEST[$str]) ? true : false;
}

// obd esc_html
function onexin_bigdata_htmlspecialchars($string)
{

    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = onexin_bigdata_htmlspecialchars($val);
        }
    } else {
        $string = esc_html($string);
    }
    return $string;
}

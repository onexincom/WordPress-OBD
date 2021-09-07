<?php

/**
 * ONEXIN BIG DATA For Other 4.0+
 * ============================================================================
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @package    onexin_bigdata
 * @module     bigdata
 * @date       2021-06-04
 * @author     King
 * @copyright  Copyright (c) 2021 Onexin Platform Inc. (http://www.onexin.com)
 */

if (!defined('OBD_CONTENT')) {
    exit('Access Denied');
}

/*
//--------------Tall us what you think!----------------------------------
*/

// FILTER
function onexin_bigdata_filter($content, $replace)
{

    if (empty($replace)) {
        return $content;
    }
    preg_match_all("/(.+)\|\|\|([^\r]+)?/", $replace, $matches);
    $content = str_replace($matches[1], $matches[2], $content);
    $content = preg_replace("/\<img [^>]*\{clear\}[^>]*>/i", '', $content);
    return $content;
}

// sanitize
function onexin_bigdata_sanitize($string)
{

    if (empty($string)) {
        return $string;
    }
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[sanitize_text_field($key)] = onexin_bigdata_sanitize($val);
        }
    } else {
        $string = sanitize_text_field($string);
    }
    return $string;
}

// stripslashes
function onexin_bigdata_stripslashes($string)
{

    if (empty($string)) {
        return $string;
    }
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = onexin_bigdata_stripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}

// addslashes
function onexin_bigdata_addslashes($string, $force = 1)
{

    if (is_array($string)) {
        $keys = array_keys($string);
        foreach ($keys as $key) {
            $val = $string[$key];
            unset($string[$key]);
            $string[addslashes($key)] = onexin_bigdata_addslashes($val, $force);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}

// implode
function onexin_bigdata_implode($array)
{

    if (!empty($array)) {
        $array = array_map('addslashes', $array);
        return "'" . implode("','", is_array($array) ? $array : array($array)) . "'";
    } else {
        return 0;
    }
}

// OUTPUT
function onexin_bigdata_output($result = "200", $response = "")
{

    $output = array("status" => $result, "nonce" => md5(microtime() . mt_rand()));
    if (!empty($response)) {
        $output["response"] = $response;
    }
    echo (isset($_GET['callback'])) ? esc_url($_GET['callback']) . '(' . json_encode($output) . ')' : json_encode($output);
    exit();
}

// RANDONE
function onexin_bigdata_randone($text)
{

    $text = preg_replace("/^.*?--" . intval($_POST['catid']) . "--(.*?)\n.*?$/is", "\\1", $text . "\n--0--");
    $text = preg_replace("/\s*--\d+--\s*/is", "|", $text);
    $text = trim($text, '|');
    $text = str_replace(array("\t", "\r", "\n"), '', strip_tags($text));
    $_tmparr = array_filter(explode('|', $text));
    return !empty($_tmparr) ? $_tmparr[array_rand($_tmparr)] : '';
}

// CHARSET
function onexin_bigdata_charset($contents, $reverse = false)
{

    if (OBD_CHARSET == 'utf-8') {
        return $contents;
    }
    if (is_array($contents)) {
        foreach ($contents as $key => $val) {
            $contents[$key] = onexin_bigdata_charset($val, $reverse);
        }
    } else {
        if ($reverse) {
            $from = OBD_CHARSET;
            $to = 'utf-8';
        } else {
            $from = 'utf-8';
            $to = OBD_CHARSET;
        }

        $yourself_file = OBD_CONTENT_DIR . '/onexin_bigdata.encode.php';
        if (file_exists($yourself_file)) {
            include_once $yourself_file;
            $obdchinese = new OBDChinese($from, $to, true);
            $contents = $obdchinese->Convert($contents);
        } elseif (function_exists('iconv')) {
            $contents = iconv($from, $to . '//IGNORE', $contents);
        } elseif (function_exists('mb_convert_encoding')) {
            $contents = mb_convert_encoding($contents, $to, $from);
        }
    }
    return $contents;
}

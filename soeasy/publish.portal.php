<?php
/**
 * ONEXIN BIG DATA For Wordpress 4.0+
 * ============================================================================
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @package    onexin_bigdata
 * @module	   wordpress
 * @date	   2021-08-17
 * @author	   King
 * @copyright  Copyright (c) 2021 Onexin Platform Inc. (http://www.onexin.com)
 */
 
if(!defined('ABSPATH')) {
	exit('Access Denied');
}

/*
//--------------Tall us what you think!----------------------------------
*/
//set_time_limit(60);
ignore_user_abort();

//------------------------Sanitize--------------------------------

	$_POST['title'] = sanitize_text_field($_POST['title']);
	$_POST['content'] = wp_kses($_POST['content'], 1);
	$_POST['catid'] = sanitize_text_field($_POST['catid']);
	$_POST['tags'] = sanitize_text_field($_POST['tags']);
	$_POST['occurl'] = sanitize_text_field($_POST['occurl']);
	$_POST['occsite'] = sanitize_text_field($_POST['occsite']);

//------------------------TAG--------------------------------

	$catid = explode(' ', $_POST['catid']);
	$_POST['catid'] = $catid[0];//fid
	$_POST['tags'] = !empty($catid[1]) ? $catid[1] : '';//tag

//------------------------ONLYONE USERNAME--------------------------------

	$catid = explode('#', $_POST['catid']);
	$_POST['catid'] = $catid[0];//fid
	$vestarr = !empty($catid[1]) ? $catid[1] : $_OBD['portal_users'];//username
	
	$vest = addslashes(onexin_bigdata_randone($vestarr));
	
	$member = get_userdatabylogin($vest);
	$user_ID = !empty($member->ID) ? $member->ID : 1;
	
wp_set_current_user( $user_ID );

$_POST['user_ID'] = $user_ID;
//$_POST['post_author'] = $user_ID;

//-----------------------------FROM URL/SITENAME--------------------------------------------

	//if($_OBD['from_style2']){
		$_OBD['from_style2'] = str_replace(
			array('{occurl}', '{occsite}', '{occtitle}'), array($_POST['occurl'], $_POST['occsite'], $_POST['title']), 
				$_OBD['from_style2']);
		$_POST['content'] = str_replace('{OCC}', $_OBD['from_style2'], $_POST['content']);
	//}
	
//-----------------------------READY GO--------------------------------------------
		
		// <!--nextpage-->
		$_POST['content'] = preg_replace("/\<hr\>$/", '', $_POST['content']);	
		//if(!$_OBD['isdelimiter'])
		$_POST['content'] = str_replace('<hr>', '', $_POST['content']);
		
		// censor
		$_POST['content'] = onexin_bigdata_censor($_POST['content']);

		$_POST['post_title'] = sanitize_text_field($_POST['title']);
		$_POST['post_type'] = 'post';
		$_POST['comment_status'] = 'open';
		$_POST['original_post_status'] = 'auto-draft';
		$_POST['original_publish'] = 'Publish';
		$_POST['publish'] = 'Publish';
		$_POST['post_category'] = explode('|', $_POST['catid']);
		
		$_POST['tax_input']['post_tag'] = $_POST['tags'];//tag
		//$_POST['post_name'] = $_POST['title'];//slug
		
		include_once ABSPATH . 'wp-admin/includes/post.php';
		  
		$post_ID = wp_write_post();//edit_post();
	
	//print_r($post_ID);

	if($post_ID) {
		DB::update('plugin_onexin_bigdata', array('status' => 1, 'ip' => 'wordpress|'.$post_ID), array('k' => $_POST['k']));
	}
	
wp_logout();

onexin_bigdata_output("200", array("id"=>$post_ID));
exit;

//-----------------------------------------------
function onexin_bigdata_censor($content){
	global $_OBD;

	if($_OBD['filter_content']){
		$content = onexin_bigdata_filter($content, $_OBD['filter_content']);
	}
	return $content;
}

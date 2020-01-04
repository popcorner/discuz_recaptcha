<?php
/**
 *	[reCAPTCHA(cdc_recaptcha)] (C)2019-2099 Powered by popcorner.
 *  Licensed under the Apache License, Version 2.0
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
global $_G;
$domainlist = array('','www.google.com','www.recaptcha.net','recaptcha.net','recaptcha.google.cn');
$var = $_G['cache']['plugin']['cdc_recaptchav3'];
$postdata = array('secret'=>$var['privkey'],'response'=>$_GET['update'],'remoteip'=>$_G['clientip']);
$resp = dfsockopen('https://'.$domainlist[$var['domain']].'/recaptcha/api/siteverify',0,$postdata);
$rede = json_decode($resp,true);
$isallowed = 0;
if($rede['success']) {
	if($var['usescore']) {
		if($rede['score']*10 >= $var['threshold']) {
			$isallowed = 1;
		}
	} else {
		$isallowed = 1;
	}
}
include template('common/header_ajax');
if($isallowed) {
	$seccode = make_seccode();
	echo '<img src="static/image/common/check_right.gif" width="16" height="16" class="vm"> '.lang('plugin/cdc_recaptchav3','vsuccess').'<script reload="1">$(\'seccodeverify_'.$_GET['idhash'].'\').value=\''.$seccode.'\'</script>';
} else {
	echo '<img src="static/image/common/check_error.gif" width="16" height="16" class="vm"> '.lang('plugin/cdc_recaptchav3','vfail').' <a href="javascript:;" onclick="updateseccode(\''.$_GET['idhash'].'\');doane(event);" class="xi2">'.lang('plugin/cdc_recaptchav3','vretry').'</a>';
}
include template('common/footer_ajax');
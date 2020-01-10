<?php
/**
 *	[reCAPTCHA(cdc_recaptcha)] (C)2019-2099 Powered by popcorner.
 *  Licensed under the Apache License, Version 2.0
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function recaptchajsparams($mobile = 0) {
	global $_G;
	$domainlist = array('','www.google.com','www.recaptcha.net','recaptcha.net','recaptcha.google.cn');
	if(!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	$var = $_G['cache']['plugin']['cdc_recaptcha'];
	$return['lang'] = $var['cname']?dhtmlspecialchars($var['cname']):lang('plugin/cdc_recaptcha','captcha');
	$return['noie'] = lang('plugin/cdc_recaptcha','noie');

	if($var['hlang']) {
		$qrr['hl'] = $var['hlang'];
	}
	$qrr['onload'] = 'grec_ol';
	$gdomain = $var['domain'];
	$gdomain = $gdomain?intval($gdomain):2;
	$return['gurl'] = 'https://'.$domainlist[$gdomain].'/recaptcha/api.js?'.http_build_query($qrr);

	$addi = '';
	$addi .= $var['theme']?' data-theme="dark"':'';
	$addi .= $var['size']?' data-size="compact"':'';
	$addi .= intval($var['tabindex'])?(' data-tabindex="'.intval($var['tabindex']).'"'):'';
	if($var['helpicon']) {
		$helpicona[0] = ($var['helptype']==0 || $var['helptype']==3)?(' href="'.$var['helplink'].'"'):'';
		if(!$mobile) {
			$helpicona[1] = ($var['helptype']==1 || $var['helptype']==3)?' onmouseover="showTip(this)"':(($var['helptype']==2)?' onclick="showTip(this)"':'');
			$helpicona[1] .= $var['helptype']?(' tip="'.addslashes(dhtmlspecialchars($var['helpcont'])).'"'):'';
		} else {
			$helpicona[1] = '';
		}
		$helpicon = '<a class="xi2" style="margin-left:6px"'.$helpicona[0].'><img src="'.$_G['style']['imgdir'].'/info_small.gif" class="vm"'.$helpicona[1].'></a>';
	}
	$return['grecaptcha'] = '<input name="seccodehash" type="hidden" value="\' + idhash + \'" /><span id="checkseccodeverify_\' + idhash + \'" style="display:none"><img src="'.$_G['style']['imgdir'].'/check_right.gif" width="16" height="16" class="vm"></span><input name="seccodeverify" id="seccodeverify_\' + idhash + \'" type="hidden" value="\' + idhash + \'" /><div class="g-recaptcha" data-sitekey="'.$var['pubkey'].'"'.$addi.'></div>';
	$return['grecaptcha'] .= '<span id="\' + onloadid + \'">';
	$return['grecaptcha'] .= intval($var['loadicon'])?'<img src="'.$_G['style']['imgdir'].'/loading.gif" class="vm">':'';
	$return['grecaptcha'] .= '</span>';
	$return['grecaptcha'] .= (intval($var['helpicon'])==1)?$helpicon:'';
	if(!$mobile) {
		$return['grecaptcha'] .= $var['refresh']?'&nbsp;&nbsp;<a href="javascript:;" onclick="updateseccode(\\\'\' + idhash + \'\\\');doane(event);" class="xi2">'.lang('home/template','refresh').'</a>':'';
	}

	$return['failload'] = (intval($var['loadicon'])==2)?'<img src="'.$_G['style']['imgdir'].'/loading.gif" class="vm">':'';
	$return['failload'] .= (intval($var['helpicon'])==2)?$helpicon:'';
	$return['delaytime'] = intval($var['delaytime']);
	$return['refdelay'] = intval($var['refdelay']);
	$return['msgtype'] = $var['msgtype'];
	$return['autoref'] = $var['autoref'];
	return $return;
}

function recaptchaphpparams() {
	global $_G;
	if(!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	$var = $_G['cache']['plugin']['cdc_recaptcha'];
	if(!$var['pubkey'] || !$var['privkey']) {
		return array('','','');
	} else {
		if(substr($_G['setting']['jspath'],0,6)=='static') {
			$jspath = 'data/cache/';
		} else {
			$jspath = $_G['setting']['jspath'];
		}
		$return =  array('<script src="'.$jspath.'recaptcha.js?'.$_G['style']['verhash'].'" reload="1"></script><div id="recptc" class="','" style="display:none;">'.dhtmlspecialchars($var['errormsg']).'</div>','');
		if($var['usemobile']) {
			$return[2] = '<div id="recptc" style="display:none;">'.dhtmlspecialchars($var['errormsg']).'</div><script src="'.$jspath.'recaptcham.js?'.$_G['style']['verhash'].'"></script>';
		}
		return $return;
	}
}
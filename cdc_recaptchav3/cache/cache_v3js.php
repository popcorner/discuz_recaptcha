<?php
/**
 *	[reCAPTCHA(cdc_recaptcha)] (C)2019-2099 Powered by popcorner.
 *  Licensed under the Apache License, Version 2.0
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function build_cache_plugin_v3js() {
	global $_G;
	$recp = v3jsparams();
	ob_start();
	include template('cdc_recaptchav3:js');
	$message = ob_get_contents();
	ob_end_clean();
	write_js_to_cache('recaptchav3',$message);
}

function write_js_to_cache($name, $content) {
	$remove = array(
		array(
			'/(^|\r|\n)\/\*.+?\*\/(\r|\n)/is',
			"/([^\\\:]{1})\/\/.+?(\r|\n)/",
			'/\/\/note.+?(\r|\n)/i',
			'/\/\/debug.+?(\r|\n)/i',
			'/(^|\r|\n)(\s|\t)+/',
			'/(\r|\n)/',
		), array(
			'',
			'\1',
			'',
			'',
			'',
			'',
		)
	);
	$message = preg_replace($remove[0], $remove[1], $content);
	if(@$fp = fopen(DISCUZ_ROOT.'./data/cache/'.$name.'.js', 'w')) {
		fwrite($fp, $message);
		fclose($fp);
	} else {
		exit('Can not write to cache files, please check directory ./data/ and ./data/cache/ .');
	}
}

function v3jsparams() {
	global $_G;
	$domainlist = array('','www.google.com','www.recaptcha.net','recaptcha.net','recaptcha.google.cn');
	if(!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	$var = $_G['cache']['plugin']['cdc_recaptchav3'];
	$return['lang'] = $var['cname']?dhtmlspecialchars($var['cname']):lang('plugin/cdc_recaptchav3','captcha');
	$return['noie'] = lang('plugin/cdc_recaptcha','noie');
	$return['pubkey'] = $var['pubkey'];
	$return['loading'] = lang('plugin/cdc_recaptchav3','loading');
	$qrr['render'] = $var['pubkey'];

	if($var['hlang']) {
		$qrr['hl'] = $var['hlang'];
	}
	$qrr['onload'] = 'grec_ol';
	$gdomain = $var['domain'];
	$gdomain = $gdomain?intval($gdomain):2;
	$return['gurl'] = 'https://'.$domainlist[$gdomain].'/recaptcha/api.js?'.http_build_query($qrr);
	return $return;
}
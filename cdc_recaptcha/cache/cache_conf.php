<?php
/**
 *	[reCAPTCHA(cdc_recaptcha)] (C)2019-2099 Powered by popcorner.
 *  Licensed under the Apache License, Version 2.0
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function build_cache_plugin_conf() {
	global $_G;
	include_once DISCUZ_ROOT.'./source/plugin/cdc_recaptcha/lib.class.php';
	$recp = recaptchajsparams();
	ob_start();
	include template('cdc_recaptcha:js');
	$message = ob_get_contents();
	ob_end_clean();
	write_js_to_cache('recaptcha',$message);
	if(!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	if($_G['cache']['plugin']['cdc_recaptcha']['usemobile']) {
		$recp = recaptchajsparams(1);
		ob_start();
		include template('cdc_recaptcha:jsm');
		$message = ob_get_contents();
		ob_end_clean();
		write_js_to_cache('recaptcham',$message);
	}
	savecache('cdc_recaptcha',recaptchaphpparams());
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
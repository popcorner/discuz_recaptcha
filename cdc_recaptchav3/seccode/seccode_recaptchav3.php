<?php
/**
 *	[reCAPTCHA(cdc_recaptcha.seccode_recaptcha)] (C)2019-2099 Powered by popcorner.
 *	Version: 1.1.1
 *	Date: 2019-12-10 21:49
 *  Licensed under the Apache License, Version 2.0
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class seccode_recaptchav3 {

	public $version = '0.0.1';
	public $name = 'recaptchav3';
	public $description = '';
	public $copyright = 'popcorner';
	public $customname = '';

	public function check($value, $idhash, $seccheck, $fromjs, $modid) {
		return $seccheck['code'] == strtoupper($value);
	}

	public function make($idhash, $modid) {
		global $_G;
		if(!isset($_G['cache']['plugin'])) {
			loadcache('plugin');
		}
		$var = $_G['cache']['plugin']['cdc_recaptchav3'];
		if(!$var['pubkey'] || !$var['privkey']) {
			echo lang('plugin/cdc_recaptcha','nokey_error');
		} else {
			if(substr($_G['setting']['jspath'],0,6)=='static') {
				$jspath = 'data/cache/';
			} else {
				$jspath = $_G['setting']['jspath'];
			}
			echo '<div id="recptc" class="'.$idhash.'" style="display:none;">'.$modid.'</div><script src="'.$jspath.'recaptchav3.js?'.$_G['style']['verhash'].'" reload="1"></script>';
		}
	}
}
?>
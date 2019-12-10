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
class seccode_recaptcha {

	public $version = '1.1.1';
	public $name = 'recaptcha';
	public $description = '';
	public $copyright = 'popcorner';
	public $customname = '';
	public $domainlist = array('','www.google.com','www.recaptcha.net','recaptcha.net','recaptcha.google.cn');

	public function check($value, $idhash) {
		global $_G;
		if(!isset($_G['cache']['plugin'])) {
			loadcache('plugin');
		}
		if(!isset($_GET['g-recaptcha-response']) || !$_GET['g-recaptcha-response'] || !$_G['cache']['plugin']['cdc_recaptcha']['pubkey'] || !$_G['cache']['plugin']['cdc_recaptcha']['privkey']) {
			return false;
		}
		$gdomain = $_G['cache']['plugin']['cdc_recaptcha']['domain'];
		$gdomain = $gdomain?intval($gdomain):2;
		$postdata = array('secret'=>$_G['cache']['plugin']['cdc_recaptcha']['privkey'],'response'=>$_GET['g-recaptcha-response'],'remoteip'=>$_G['clientip']);
		$resp = dfsockopen('https://'.$this->domainlist[$gdomain].'/recaptcha/api/siteverify',0,$postdata);
		if(json_decode($resp,true)['success']) {
			return true;
		} else {
			return false;
		}
	}

	public function make($idhash) {
		global $_G;
		loadcache('cdc_recaptcha');
		if(!isset($_G['cache']['cdc_recaptcha']) || !$_G['cache']['cdc_recaptcha'][0]) {
			echo lang('plugin/cdc_recaptcha','nokey_error');
		} else {
			echo $_G['cache']['cdc_recaptcha'][0].$idhash.$_G['cache']['cdc_recaptcha'][1];
		}
	}
}
?>
<?php
/**
 *	[reCAPTCHA(cdc_recaptcha.seccode_recaptcha)] (C)2019-2099 Powered by popcorner.
 *	Version: 1.0.1
 *	Date: 2019-11-8 18:16
 *  Licensed under the Apache License, Version 2.0
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class seccode_recaptcha {

	public $version = '1.0.0';
	public $name = 'recaptcha';
	public $description = '';
	public $copyright = 'popcorner';
	public $customname = '';
	public $domainlist = array('','www.google.com','www.recaptcha.net','recaptcha.net','recaptcha.google.cn');

	public function check($value, $idhash) {
		global $_G;
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
		if(!$_G['cache']['plugin']['cdc_recaptcha']['pubkey'] || !$_G['cache']['plugin']['cdc_recaptcha']['privkey']) {
			echo lang('plugin/cdc_recaptcha','nokey_error');
		} else {
			echo '<script src="'.$_G['siteurl'].'source/plugin/cdc_recaptcha/template/captcha'.substr(currentlang(),0,2).'.js" reload="1"></script>';
			$gdomain = $_G['cache']['plugin']['cdc_recaptcha']['domain'];
			$gdomain = $gdomain?intval($gdomain):2;
			$return[0] = $this->domainlist[$gdomain];
			$return[1] = $_G['cache']['plugin']['cdc_recaptcha']['cname']?$_G['cache']['plugin']['cdc_recaptcha']['cname']:lang('plugin/cdc_recaptcha','captcha');
			$return[2] = $_G['cache']['plugin']['cdc_recaptcha']['pubkey'];
			$return[3] = $_G['cache']['plugin']['cdc_recaptcha']['theme']?true:false;
			$return[4] = $_G['cache']['plugin']['cdc_recaptcha']['size']?true:false;
			$return[5] = intval($_G['cache']['plugin']['cdc_recaptcha']['tabindex']);
			$return[6] = $_G['cache']['plugin']['cdc_recaptcha']['refresh']?true:false;
			$return[7] = $_G['cache']['plugin']['cdc_recaptcha']['hlang'];
			require_once DISCUZ_ROOT.'./source/plugin/mobile/json.class.php';
			echo '<div id="recptc" class="'.$idhash.'" style="display:none;">'.CJSON::encode($return).'</div>';
		}
	}
}
?>
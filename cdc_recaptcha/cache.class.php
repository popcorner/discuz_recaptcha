<?php
/**
 *	[reCAPTCHA(cdc_recaptcha)] (C)2019-2099 Powered by popcorner.
 *  Licensed under the Apache License, Version 2.0
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(isset($_GET['action']) && $_GET['action']=='plugins' && isset($_GET['operation']) && $_GET['operation']=='config' && isset($_GET['varsnew']['privkey'])) {
	updatecache('cdc_recaptcha:conf');
}

?>
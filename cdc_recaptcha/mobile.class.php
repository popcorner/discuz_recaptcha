<?php
/**
 *	[reCAPTCHA(cdc_recaptcha)] (C)2019-2099 Powered by popcorner.
 *  Licensed under the Apache License, Version 2.0
 */

class mobileplugin_cdc_recaptcha {
    function global_footer_mobile() {
        global $seccodecheck,$_G;
        $show = 0;
        if($_G['setting']['seccodedata']['type'] == 'cdc_recaptcha:recaptcha') {
            if(CURSCRIPT.CURMODULE == 'memberlogging') {
                list($seccodecheck) = seccheck('login');
                if($seccodecheck) {
                    $show = 1;
                }
            } elseif(CURSCRIPT == 'forum') {
                $modulelist = array('viewthread','post');
                if(in_array(CURMODULE,$modulelist) && $seccodecheck) {
                    $show = 1;
                }
            }
        }
        if($show) {
            loadcache('cdc_recaptcha');
            if($_G['cache']['cdc_recaptcha'][2]) {
                return ((IN_MOBILE == 1)?'<script src="'.$_G['setting']['jspath'].'mobile/jquery.min.js"></script>':'').$_G['cache']['cdc_recaptcha'][2];
            }
        }
    }
}
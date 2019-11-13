(function () {
    var idhash = $('recptc').className;
    var showid = 'seccode_' + idhash;
    if (BROWSER.ie && BROWSER.ie < 10) {
        $('v' + showid).innerHTML = '\u60a8\u7684\u700f\u89bd\u5668\u7248\u672c\u904e\u4f4e\uff0c\u7121\u6cd5\u8f09\u5165reCAPTCHA\u9a57\u8b49\u78bc\uff0c\u5efa\u8b70\u4f7f\u7528\u975eIE\u700f\u89bd\u5668\u3002';
    } else {
        var data = JSON.parse($('recptc').innerHTML);
        appendscript('https://' + data[0] + '/recaptcha/api.js' + (data[7] ? '?hl=' + data[7] : ''), '', 1);

        var sectpl = seccheck_tpl[idhash] != '' ? seccheck_tpl[idhash].replace(/<hash>/g, 'code' + idhash).replace(/_menu/g, '_hidden') : '';
        var sectplcode = sectpl != '' ? sectpl.split('<sec>') : Array('<br />', ': ', '<br />', '');
        var addi = '';
        if (data[3]) {
            addi += ' data-theme="dark"';
        }
        if (data[4]) {
            addi += ' data-size="compact"';
        }
        if (data[5]) {
            addi += 'data-tabindex="' + data[5] + '"';
        }
        var grecaptcha = '<div class="g-recaptcha" data-sitekey="' + data[2] + '"' + addi + '>Google reCAPTCHA\u9a57\u8b49\u78bc</div>';
        var string = sectplcode[0] + data[1] + sectplcode[1] + grecaptcha + (data[6] ? '<a href="javascript:;" onclick="updateseccode(\'' + idhash + '\');doane(event);" class="xi2">\u5237\u65b0</a>' : '') + sectplcode[2] + sectplcode[3];
        $(showid).innerHTML = string;
    }
})()
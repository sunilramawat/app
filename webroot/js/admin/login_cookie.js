/**
 * Remember Me Admin Js
 * @author: Jogendar singh
 * @created: 25-05-2015
 */
function rememberme() {
    if ($('#remember').is(':checked')) {

        setCookie('retail_adminloginemail', $('#UserEmail').val(), 14);
        setCookie('retail_adminloginpass', $('#UserUPassword').val(), 14);
    } else {
        setCookie('retail_adminloginemail', '', -1);
        setCookie('retail_adminloginpass', '', -1);
    }
}

function front_rememberme() {
    if ($('#remember').is(':checked')) {

        setCookie('retail_frontloginemail', $('#UserEmail').val(), 14);
        setCookie('retail_frontloginpass', $('#UserUPassword').val(), 14);
    } else {
        setCookie('retail_frontloginemail', '', -1);
        setCookie('retail_frontloginpass', '', -1);
    }
}

function checkcookies() {

    var em = getCookie("retail_adminloginemail");
    var ps = getCookie("retail_adminloginpass");

    if (em && ps) {
        $("#UserEmail").val(em);
        $("#UserUPassword").val(ps);
        $("#remember").attr('checked', 'checked');
        $(".icheckbox_square-blue ").addClass('checked');
        $(".icheckbox_square-blue ").attr('aria-checked', 'true');
    }
}

function checkfrontcookies() {

    var em = getCookie("retail_frontloginemail");
    var ps = getCookie("retail_frontloginpass");

    if (em && ps) {
        $("#UserEmail").val(em);
        $("#UserUPassword").val(ps);
        $("#remember").attr('checked', 'checked');
        $(".icheckbox_square-blue ").addClass('checked');
        $(".icheckbox_square-blue ").attr('aria-checked', 'true');
    }
}

function getCookie(c_name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return unescape(y);
        }
    }
    return '';
}
function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value + "; path=/";
}
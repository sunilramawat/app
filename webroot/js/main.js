// JavaScript Document
// Entered Number is Digit Or Not
function isNumber(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    {
        return false;
    }
    return true;
}
function alphaOnly(evt) {
    var keyCode = (evt.which) ? evt.which : evt.keyCode;
    //var valid = (keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && e.which <= 90) || (keyCode >= 97 && keyCode <= 122 || keyCode == 32 || keyCode == 95 || keyCode == 8);
    //alert(keyCode);
    if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && keyCode != 8 && keyCode != 9 && keyCode != 37 && keyCode != 39)
        return false;
    return true;

}

function phoneNumberOnly(evt) {
    
    var keyCode = (evt.which) ? evt.which : evt.keyCode;
    
    //var valid = (keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && e.which <= 90) || (keyCode >= 97 && keyCode <= 122 || keyCode == 32 || keyCode == 95 || keyCode == 8);
    if ((keyCode < 48 || keyCode > 57) &&  keyCode != 43 && keyCode != 45 && keyCode != 8 && keyCode != 9 && keyCode != 37 && keyCode != 39)
        return false;
    return true;

}

function numberOnly(evt) {
    
    var keyCode = (evt.which) ? evt.which : evt.keyCode;
    
    //var valid = (keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && e.which <= 90) || (keyCode >= 97 && keyCode <= 122 || keyCode == 32 || keyCode == 95 || keyCode == 8);
    
    if ((keyCode < 48 || keyCode > 57) && keyCode != 46 && keyCode != 8 && keyCode != 9 && keyCode != 37 && keyCode != 39)
        return false;
    return true;

}


$(document).ready(function () {
    //hide message box
    setTimeout('$(".alert, .custom-alert").slideUp(1000);', 10000);
    //start recaptcha
    $('#reload_captcha').click(function () {
        $('#reload_captcha').attr('src', APPLICATION_URL + 'img/loading.gif?y=' + Math.random() * 1000);
        $('#loading-image').show();
        $.ajax({url: APPLICATION_URL + 'cmspages/get_captcha_image',
            type: "POST",
            data: ({rand: (Math.random() * 1000)}),
            success: function (data) {
                $('#loading-image').hide();
                $('#security_image').attr('src', APPLICATION_URL + 'images/captcha/captcha.jpg?y=' + Math.random() * 1000);
                $('#reload_captcha').attr('src', APPLICATION_URL + 'img/loading_static.png?y=' + Math.random() * 1000);
            }});
    });
    $('#reload_captcha').trigger('click');
    //end recaptcha
});
function flashErrorMessage(message) {
    var _insert = $(document.createElement('div')).css('display', 'none');
    $('#CustomflashMessage').remove();
    _insert.attr('id', 'CustomflashMessage').addClass('alert alert-danger').text(message);
    _insert.insertBefore($("#wrapefooter")).fadeIn();
    setTimeout('$(".alert").slideUp(1000);', 10000);
}

function flashSuccessMessage(message) {
    var _insert = $(document.createElement('div')).css('display', 'none');
    $('#CustomflashMessage').remove();
    _insert.attr('id', 'CustomflashMessage').addClass('alert alert-success').text(message);
    _insert.insertBefore($("#wrapefooter")).fadeIn();
    setTimeout('$(".alert").slideUp(1000);', 10000);
}
